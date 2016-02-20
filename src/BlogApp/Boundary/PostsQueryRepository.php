<?php declare(strict_types=1);

namespace BlogApp\Boundary;

interface PostsQueryRepository
{
    public function getAll():array;

    public function add(array $data);

    public function findByTitle(string $postTitle):array;

    public function update(string $postTitle, array $postData);
}
