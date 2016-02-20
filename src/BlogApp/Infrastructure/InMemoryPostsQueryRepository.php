<?php declare(strict_types=1);

namespace BlogApp\Infrastructure;

use BlogApp\Boundary\PostsQueryRepository;

class InMemoryPostsQueryRepository implements PostsQueryRepository
{
    private $posts = [];

    public function getAll():array
    {
        return $this->posts;
    }

    public function add(array $data)
    {
        $this->posts[] = $data;
    }

    public function findByTitle(string $postTitle):array
    {
        foreach ($this->posts as $postData) {
            if ($postData['title'] === $postTitle) {
                return $postData;
            }
        }
    }

    public function update(string $postTitle, array $postData)
    {
        foreach ($this->posts as &$post) {
            if ($post['title'] === $postTitle) {
                $post = $postData;
                break;
            }
        }
    }
}
