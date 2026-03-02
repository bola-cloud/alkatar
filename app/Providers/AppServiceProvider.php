<?php

namespace App\Providers;

use App\Models\Language;
use App\Models\Menu;
use App\Models\Setting;
use App\Models\Admin\Product;
use App\Models\Admin\Category;
use App\Observers\ProductObserver;
use App\Observers\CategoryObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Paginator::useBootstrap();

        // Register observers for deletion protection
        Product::observe(ProductObserver::class);
        Category::observe(CategoryObserver::class);
        \App\Models\UserSubscription::observe(\App\Observers\UserSubscriptionObserver::class);

        if (file_exists(storage_path('installed'))) {

            try {
                $language = Setting::where('slug', 'default_language')->first();
                if ($language) {
                    $locale = $language->value;

                    $lang = Language::where('locale', $locale)->first();

                    // Determine the HTML display locale separately from the DB locale.
                    // Some installations keep a legacy DB locale (e.g. 'fr') for column
                    // naming, but the rendered HTML and direction should reflect the
                    // true display language (e.g. 'ar' for RTL languages).
                    $htmlLocale = $lang->direction === 'rtl' ? 'ar' : $locale;

                    if (!session()->has('APP_LOCALE')) {
                        session([
                            'APP_LOCALE' => $locale,
                            'HTML_LANG' => $htmlLocale,
                            'lang_dir' => $lang->direction,
                        ]);
                    }

                    $all_menus = Menu::where('is_static', INACTIVE)->with('submenus')->orderBy('order')->get();
                    $allsettings = allsetting();
                    view()->share(['all_menus' => $all_menus, 'allsettings' => $allsettings]);
                }
            } catch (\Exception $e) {
                //
            }
        }
    }
}
