<?php

namespace App\Providers;

use App\Infrastructure\ModelCommentsRepository;
use App\Infrastructure\ModelPostsRepository;
use BlogApp\Boundary\CommentsRepository;
use BlogApp\Boundary\PostsRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PostsRepository::class, ModelPostsRepository::class);
        $this->app->bind(CommentsRepository::class, ModelCommentsRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
