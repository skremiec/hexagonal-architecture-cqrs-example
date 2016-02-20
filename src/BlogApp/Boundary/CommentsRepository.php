<?php declare(strict_types=1);

namespace BlogApp\Boundary;

use BlogApp\Entity\Comment;

interface CommentsRepository
{
    public function clear();

    public function add(Comment $comment);

    /**
     * @return Comment[]
     */
    public function getAll():array;

    public function getCountFor(string $postTitle):int;
}
