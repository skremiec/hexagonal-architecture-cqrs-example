<?php declare(strict_types=1);

use BlogApp\Boundary\CommentsRepository;
use BlogApp\Boundary\PostsRepository;
use BlogApp\Infrastructure\DatabaseCommentsRepository;
use BlogApp\Infrastructure\DatabasePostsRepository;

class DatabaseContext extends BaseContext
{
    /** @var PDO */
    private $pdo;

    public function __construct()
    {
        $this->pdo = new PDO('sqlite://' . realpath('.') . '/tmp/database.db');

        parent::__construct();
    }

    protected function createPostsRepository():PostsRepository
    {
        return new DatabasePostsRepository($this->pdo);
    }

    protected function createCommentsRepository():CommentsRepository
    {
        return new DatabaseCommentsRepository($this->pdo);
    }
}
