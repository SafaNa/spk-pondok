<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $customPublic = base_path('../public_html/latee-putri.kkkmilenteng.my.id');
        if (is_dir($customPublic)) {
            $this->app->usePublicPath($customPublic);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        try {
            if (Schema::hasTable('settings')) {
                View::share('appSetting', Setting::first());
            }
        } catch (\Exception $e) {
            // Ignore during initial migration
        }
    }
}
