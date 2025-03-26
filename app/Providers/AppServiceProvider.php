<?php

namespace App\Providers;

use App\Models\Car;
use App\Models\Client;
use App\Models\Service;
use App\Services\InsertDataService;
use Artisan;
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
        if (Client::count() === 0 && Car::count() === 0 && Service::count() === 0) {
            $myService = $this->app->make(InsertDataService::class);
            $myService->execute();
        }
    }
}
