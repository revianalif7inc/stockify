<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\CategoryRepositoryInterface;
use App\Repositories\CategoryRepository;
use App\Interfaces\SupplierRepositoryInterface;
use App\Repositories\SupplierRepository;
use App\Interfaces\ProductRepositoryInterface;
use App\Repositories\ProductRepository;
use App\Interfaces\StockMovementRepositoryInterface;
use App\Repositories\StockMovementRepository;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(SupplierRepositoryInterface::class, SupplierRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        
        // Siapkan binding untuk Tahap 4 (Manajemen Stok)
       $this->app->bind(StockMovementRepositoryInterface::class, StockMovementRepository::class);
    }
    
    // ...
}