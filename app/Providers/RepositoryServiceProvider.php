<?php

namespace App\Providers;

use App\Repositories\book\BooksRepository;
use App\Repositories\book\BooksService;
use App\Repositories\book\interfaces\BooksRepositoryInterface;
use Illuminate\Support\ServiceProvider;



class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
       $this->app->bind(BooksRepositoryInterface::class,BooksService::class);
       $this->app->bind(BooksRepositoryInterface::class,BooksRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
