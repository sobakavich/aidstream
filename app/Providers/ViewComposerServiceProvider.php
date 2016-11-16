<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(
            [
                'app',
            ],
            function ($view) {
                $view->with('currentUser', auth()->user());
                $view->with('loggedInUser', auth()->user());
            }
        );
        view()->composer(
            [
                'Activity.index',
                'settings.publishingSettings',
                'settings.defaultValues',
                'settings.activityElementsChecklist',
                'Organization.show'
            ],
            function ($view) {
                if (auth()->user()->isAdmin()) {
                    $view->with('currentUser', auth()->user());
                    $view->with('loggedInUser', auth()->user());
                    $view->with('completedSteps', auth()->user()->userOnBoarding->settings_completed_steps);
                }
            }
        );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
