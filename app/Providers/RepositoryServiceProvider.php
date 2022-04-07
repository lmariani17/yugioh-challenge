<?php

namespace App\Providers;

use App\Repositories\CardRepositoryInterface;
use App\Repositories\ImageRepositoryInterface;
use App\Repositories\SubtypeRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\Eloquent\CardRepository;
use App\Repositories\Eloquent\ImageRepository;
use App\Repositories\Eloquent\SubtypeRepository;
use App\Repositories\Eloquent\UserRepository;
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
        $this->app->bind(SubtypeRepositoryInterface::class, SubtypeRepository::class);
        $this->app->bind(ImageRepositoryInterface::class, ImageRepository::class);
        $this->app->bind(CardRepositoryInterface::class, CardRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }
}
