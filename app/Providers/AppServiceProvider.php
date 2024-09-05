<?php

namespace App\Providers;

use App\Facades\UploadServiceFacade;
use App\Repositories\ArticleRepositoryImpl;
use App\Repositories\ArticleRepositoryInterface;
use App\Repositories\ClientRepositoryImpl;
use App\Services\ArticleServiceImpl;
use App\Services\ArticleServiceInterface;
use App\Services\ClientServiceImpl;
use App\Services\UploadService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ArticleRepositoryInterface::class, ArticleRepositoryImpl::class);
        $this->app->singleton(ArticleServiceInterface::class, ArticleServiceImpl::class);
        $this->app->singleton('clientRepository', function () {
            return new ClientRepositoryImpl();
        });
        $this->app->singleton('clientService', function () {
            return new ClientServiceImpl();
        });

        $this->app->singleton('uploadService', function ($app) {
            return new UploadService();
        });

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
