<?php

namespace App\Providers;

use App\Repository\CardRepositoryInterface;
use App\Repository\Eloquent\CardRepository;
use App\Repository\Eloquent\ImageRepository;
use App\Repository\Eloquent\SubtypeRepository;
use App\Repository\ImageRepositoryInterface;
use App\Repository\SubtypeRepositoryInterface;
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
    }
}
