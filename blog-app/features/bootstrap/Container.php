<?php

declare(strict_types=1);

namespace BlogApp;

use BlogApp\Infrastructure\InMemoryCommentsRepository;
use BlogApp\Infrastructure\InMemoryPostsRepository;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    /** @var array */
    private $services;

    public static function create(): self
    {
        return new self();
    }

    public function __construct()
    {
        $this->services = [
            'posts.repository' => new InMemoryPostsRepository(),
            'comments.repository' => new InMemoryCommentsRepository(),
        ];
    }

    /**
     * @param string $id
     */
    public function has($id): bool
    {
        return array_key_exists($id, $this->services);
    }

    /**
     * @param string $id
     */
    public function get($id)
    {
        return $this->services[$id];
    }
}
