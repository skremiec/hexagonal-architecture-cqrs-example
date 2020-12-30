<?php declare(strict_types=1);

namespace BlogApp\Infrastructure;

use BlogApp\Boundary\CommentsRepository;
use BlogApp\Entity\Comment;

class InMemoryCommentsRepository implements CommentsRepository
{
    /** @var Comment[] */
    private $comments = [];

    public function clear()
    {
        $this->comments = [];
    }

    public function add(Comment $comment)
    {
        $this->comments[serialize($comment)] = $comment;
    }

    /**
     * @return Comment[]
     */
    public function getAll(): array
    {
        return $this->comments;
    }

    public function getCountFor(string $postTitle): int
    {
        return count(array_filter($this->comments, function (Comment $comment) use ($postTitle) {
            return $comment->getPostTitle() === $postTitle;
        }));
    }
}
