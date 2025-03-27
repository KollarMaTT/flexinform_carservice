<?php

namespace App\Providers;

use App\Models\Car;
use App\Models\Client;
use App\Models\Service;
use App\Services\InsertDataService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
        if (Schema::hasTable('clients') && Schema::hasTable('cars') && Schema::hasTable('services')) {
            if (Client::count() === 0 && Car::count() === 0 && Service::count() === 0) {
                $insertDataService = $this->app->make(InsertDataService::class);
                $insertDataService->execute();
            }
        }
    }
}
