<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GeminiService
{
    /**
     * Base Google Generative Language API endpoint.
     */
    protected string $base = 'https://generativelanguage.googleapis.com/v1beta';

    /**
     * Model & API key pulled from config/services.php
     */
    protected string $model;
    protected string $key;

    /**
     * Safety: default LIMIT to avoid returning massive result sets.
     * You can override via env AI_SQL_DEFAULT_LIMIT.
     */
    protected int $defaultLimit;

    public function __construct()
    {
        $this->model = (string) config('services.gemini.model');
        $this->key = (string) config('services.gemini.key');
        $this->defaultLimit = (int) (env('AI_SQL_DEFAULT_LIMIT', 200));

        if (empty($this->model) || empty($this->key)) {
            throw new \RuntimeException('Gemini model/key are not configured. Set services.gemini.model and services.gemini.key.');
        }
    }

    /* =========================================================================
       PUBLIC API
       ========================================================================= */

    public function runAiQueryAndAnswer(string $message, array $history = []): array
    {
        $schema = $this->getSchemaSummary();
        [$sql, $modelTextRaw] = $this->askModelForSql($message, $history, $schema);
        $sql = $this->validateAndNormalizeSelect($sql, $this->defaultLimit);
        $rows = DB::select(DB::raw($sql));

        return [
            'text' => $modelTextRaw !== '' ? $modelTextRaw : 'Query executed successfully.',
            'sql' => $sql,
            'db_data' => json_decode(json_encode($rows), true),
        ];
    }

    /* =========================================================================
       MODEL PROMPTING
       ========================================================================= */

    protected function buildUserMessage(string $rules, string $task): string
    {
        return "### Rules\n{$rules}\n\n### Task\n{$task}";
    }

    protected function toContents(array $history, string $userMessage): array
    {
        $contents = [];
        foreach ($history as $m) {
            if (!isset($m['role'], $m['content']))
                continue;
            $role = $m['role'] === 'assistant' ? 'model' : 'user';
            $contents[] = [
                'role' => $role,
                'parts' => [['text' => (string) $m['content']]],
            ];
        }
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $userMessage]],
        ];
        return $contents;
    }

    protected function askModelForSql(string $userMessage, array $history, string $schemaSummary): array
    {
        $rules = <<<RULES
You are a SQL assistant for a Laravel/MySQL app.

- Output exactly ONE SQL statement.
- It MUST be a single SELECT statement (no INSERT/UPDATE/DELETE/DDL; no multi-statements).
- Use only tables/columns that exist in the provided schema.
- Prefer ANSI SQL compatible with MySQL 8.
- If no LIMIT is present, it's okay; the server may append a LIMIT.
- Return the SQL inside a fenced code block like:
```sql
SELECT ...
```
After the SQL block, add at most 1–2 short lines explaining what it does.

Do not include any other commentary, markdown, or prose before the code block.
RULES;

        $task = <<<PROMPT
User request:
{$userMessage}

Database schema (tables and some columns):
{$schemaSummary}

Follow the rules. Provide exactly one SQL SELECT that answers the user. Then a brief explanation.
PROMPT;

        $contents = $this->toContents($history, $this->buildUserMessage($rules, $task));

        $url = "{$this->base}/models/{$this->model}:generateContent?key={$this->key}";
        $res = Http::timeout(60)->post($url, ['contents' => $contents]);

        if (!$res->ok()) {
            throw new \RuntimeException("Gemini error: " . $res->body());
        }

        $data = $res->json();
        $parts = $data['candidates'][0]['content']['parts'] ?? [];
        $full = '';
        foreach ($parts as $p) {
            $full .= $p['text'] ?? '';
        }

        $sql = $this->extractSqlFromText($full);
        if (!$sql) {
            throw new \RuntimeException('Could not extract SQL from model response.');
        }

        $explanation = $this->extractExplanationAfterSql($full);

        return [$sql, trim($explanation)];
    }

    protected function extractSqlFromText(string $text): ?string
    {
        if (preg_match('/```sql\s*(.*?)```/is', $text, $m)) {
            return trim($m[1]);
        }
        if (preg_match('/\bselect\b[\s\S]*?(?:;|$)/i', $text, $m)) {
            return trim(rtrim($m[0], ';'));
        }
        return null;
    }

    protected function extractExplanationAfterSql(string $text): string
    {
        if (preg_match('/```sql\s*.*?```(.*)$/is', $text, $m)) {
            return trim($m[1]);
        }
        return '';
    }

    /* =========================================================================
       SAFETY: VALIDATE & NORMALIZE SQL
       ========================================================================= */

    protected function validateAndNormalizeSelect(string $sql, int $defaultLimit): string
    {
        $candidate = trim($sql);

        if (preg_match('/;.*\S/s', $candidate)) {
            throw new \RuntimeException('Multiple statements are not allowed.');
        }

        $candidate = preg_replace('/--.*$/m', '', $candidate);
        $candidate = preg_replace('/\/\*.*?\*\//s', '', $candidate);
        $candidate = trim($candidate);

        if (!preg_match('/^\s*select\b/i', $candidate)) {
            throw new \RuntimeException('Only SELECT statements are allowed.');
        }

        $blocked = ['insert', 'update', 'delete', 'drop', 'alter', 'create', 'grant', 'revoke', 'truncate'];
        foreach ($blocked as $kw) {
            if (preg_match('/\b' . preg_quote($kw, '/') . '\b/i', $candidate)) {
                throw new \RuntimeException("Disallowed keyword detected: {$kw}");
            }
        }

        if (preg_match('/\binformation_schema\b|\bmysql\b|\bperformance_schema\b/i', $candidate)) {
            throw new \RuntimeException('Access to system schemas is not allowed.');
        }

        if (!preg_match('/\blimit\b/i', $candidate)) {
            $candidate .= ' LIMIT ' . $defaultLimit;
        }

        return rtrim($candidate, ';');
    }

    /* =========================================================================
       SCHEMA SNAPSHOT
       ========================================================================= */

    protected function getSchemaSummary(): string
    {
        $tables = DB::select("SHOW TABLES");
        if (!$tables) {
            return "No tables found.";
        }

        $tableNames = [];
        foreach ($tables as $t) {
            $arr = (array) $t;
            $tableNames[] = array_values($arr)[0];
        }

        $lines = [];
        foreach ($tableNames as $tbl) {
            $cols = DB::select("SHOW COLUMNS FROM `{$tbl}`");
            $colNames = array_map(fn($c) => $c->Field ?? '', $cols);
            $colNames = array_values(array_filter($colNames));
            $preview = implode(', ', array_slice($colNames, 0, 8));
            $lines[] = "- {$tbl} (cols: {$preview}" . (count($colNames) > 8 ? ', …' : '') . ")";
        }

        return implode("\n", $lines);
    }
}