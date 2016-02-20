<?php declare(strict_types=1);

use BlogApp\Boundary\CommentsRepository;
use BlogApp\Boundary\PostsRepository;
use BlogApp\Infrastructure\InMemoryCommentsRepository;
use BlogApp\Infrastructure\InMemoryPostsRepository;

class InMemoryContext extends BaseContext
{
    protected function createPostsRepository():PostsRepository
    {
        return new InMemoryPostsRepository();
    }

    protected function createCommentsRepository():CommentsRepository
    {
        return new InMemoryCommentsRepository();
    }
}
