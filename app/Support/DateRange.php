<?php

namespace App\Support;

use Carbon\Carbon;

/**
 * DateRange helper to resolve a start/end Carbon pair for standard ranges.
 * Supported ranges: today, week (Mon–Sun), month, year, custom
 */
class DateRange
{
    /**
     * Resolve a date range.
     *
     * @param string $range One of: today|week|month|year|custom
     * @param string|null $start Y-m-d for custom
     * @param string|null $end Y-m-d for custom
     * @return array{start: Carbon, end: Carbon}
     */
    public static function resolve(string $range, ?string $start = null, ?string $end = null): array
    {
        $now = Carbon::now();

        switch ($range) {
            case 'today':
                $startAt = $now->copy()->startOfDay();
                $endAt = $now->copy()->endOfDay();
                break;
            case 'week':
                // ISO week: Monday start, Sunday end
                $startAt = $now->copy()->startOfWeek(Carbon::MONDAY);
                $endAt = $now->copy()->endOfWeek(Carbon::SUNDAY);
                break;
            case 'month':
                $startAt = $now->copy()->startOfMonth();
                $endAt = $now->copy()->endOfMonth();
                break;
            case 'year':
                $startAt = $now->copy()->startOfYear();
                $endAt = $now->copy()->endOfYear();
                break;
            case 'custom':
                // Expecting Y-m-d strings; default to today if missing (validation should protect this)
                $startAt = Carbon::parse($start ?? $now->toDateString())->startOfDay();
                $endAt = Carbon::parse($end ?? $now->toDateString())->endOfDay();
                break;
            default:
                // Fallback to month for safety
                $startAt = $now->copy()->startOfMonth();
                $endAt = $now->copy()->endOfMonth();
        }

        return ['start' => $startAt, 'end' => $endAt];
    }
}
