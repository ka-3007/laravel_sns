<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;

class FirebaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $firebaseConfig = config('firebase');

        $this->app->singleton('firebase.storage', function ($app) use ($firebaseConfig) {
            $factory = (new Factory)
                ->withServiceAccount($firebaseConfig);

            return $factory->createStorage();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
