<?php declare(strict_types=1);

namespace BlogApp\Infrastructure;

use BlogApp\Boundary\PostsRepository;
use BlogApp\Entity\Post;
use Everzet\PersistedObjects\InMemoryRepository;
use Everzet\PersistedObjects\ObjectIdentifier;

class InMemoryPostsRepository implements PostsRepository, ObjectIdentifier
{
    /** @var InMemoryRepository */
    private $repository;

    public function __construct()
    {
        $this->repository = new InMemoryRepository($this);
    }

    public function clear()
    {
        $this->repository->clear();
    }

    public function add(Post $post)
    {
        $this->repository->save($post);
    }

    /**
     * @return Post[]
     */
    public function getAll():array
    {
        return $this->repository->getAll();
    }

    public function exists(string $title):bool
    {
        foreach ($this->getAll() as $post) {
            if ($post->getTitle() === $title) {
                return true;
            }
        }

        return false;
    }

    public function getIdentity($object):string
    {
        return serialize($object);
    }
}
