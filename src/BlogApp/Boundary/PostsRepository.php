<?php declare(strict_types=1);

namespace BlogApp\Boundary;

use BlogApp\Entity\Post;

interface PostsRepository
{
    public function clear();

    public function add(Post $post);

    /**
     * @return Post[]
     */
    public function getAll():array;

    public function exists(string $title):bool;
}
