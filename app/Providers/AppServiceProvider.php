<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Master\MasterRepository;
use App\Repositories\Master\MasterRepositoryImplement;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            MasterRepository::class,
            MasterRepositoryImplement::class
        );
        //
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
