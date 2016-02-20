<?php declare(strict_types=1);

namespace BlogApp\Infrastructure;

use BlogApp\Boundary\CommentsRepository;
use BlogApp\Entity\Comment;
use Everzet\PersistedObjects\InMemoryRepository;
use Everzet\PersistedObjects\ObjectIdentifier;

class InMemoryCommentsRepository implements CommentsRepository, ObjectIdentifier
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

    public function add(Comment $comment)
    {
        $this->repository->save($comment);
    }

    /**
     * @return Comment[]
     */
    public function getAll():array
    {
        return $this->repository->getAll();
    }

    public function getCountFor(string $postTitle):int
    {
        return count(array_filter($this->getAll(), function (Comment $comment) use ($postTitle) {
            return $comment->getPostTitle() === $postTitle;
        }));
    }

    public function getIdentity($object):string
    {
        return serialize($object);
    }
}
