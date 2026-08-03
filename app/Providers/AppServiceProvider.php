<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $view->with('settings', Setting::first() ?? new Setting([
                'app_name' => 'Laravel',
                'site_title' => 'Laravel Application'
            ]));

            // Share contact submissions count if table exists
            try {
                if (\Schema::hasTable('contact_submissions')) {
                    $view->with('unreadContactCount', \App\Models\ContactSubmission::where('is_read', false)->count());
                } else {
                    $view->with('unreadContactCount', 0);
                }
            } catch (\Exception $e) {
                $view->with('unreadContactCount', 0);
            }
        });

        // Register UserObserver
        User::observe(UserObserver::class);

        // Implicitly grant "Super Admin" role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        Gate::before(function ($user, $ability) {
            return $user->hasRole('super_admin') ? true : null;
        });
        
    }
}
