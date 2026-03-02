<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Models\Language;

class  Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Allow admin/front to use any locale defined in the `languages` table
        // Fallback to the common locales list if DB is unavailable.
        try {
            $dbLocales = Language::pluck('locale')->toArray();
            $avail_able_locales = !empty($dbLocales) ? $dbLocales : ['en', 'fr', 'ar'];
        } catch (\Throwable $e) {
            $avail_able_locales = ['en', 'fr', 'ar'];
        }

        $locale = session('APP_LOCALE');
        $locale = in_array($locale, $avail_able_locales) ? $locale : config('app.locale');
        app()->setLocale($locale);
        return $next($request);
    }
}
