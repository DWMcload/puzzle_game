<?php

namespace App\Providers;

use App\Services\DictionaryApi;
use App\Services\DictionaryApiInterface;
use App\Services\PuzzleService;
use App\Services\PuzzleServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PuzzleServiceInterface::class, PuzzleService::class);
        $this->app->bind(DictionaryApiInterface::class, DictionaryApi::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
