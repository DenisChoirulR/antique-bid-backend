<?php

namespace App\Providers;

use App\Repositories\BidRepository\BidRepository;
use App\Repositories\BidRepository\Interfaces\BidRepositoryInterface;
use App\Repositories\ItemRepository\Interfaces\ItemRepositoryInterface;
use App\Repositories\ItemRepository\ItemRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BidRepositoryInterface::class, BidRepository::class);
        $this->app->bind(ItemRepositoryInterface::class, ItemRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
