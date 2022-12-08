<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use RiotAPI\Base\BaseAPI;
use RiotAPI\LeagueAPI\LeagueAPI;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(LeagueAPI::class, function() {
            return new LeagueAPI([
                BaseAPI::SET_KEY => env('RGAPI'),
                BaseAPI::SET_REGION => 'tr',
            ]);
        });

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
