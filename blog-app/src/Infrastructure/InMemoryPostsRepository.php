<?php declare(strict_types=1);

namespace BlogApp\Infrastructure;

use BlogApp\Boundary\PostsRepository;
use BlogApp\Entity\Post;

class InMemoryPostsRepository implements PostsRepository
{
    /** @var Post[] */
    private $posts = [];

    public function clear()
    {
        $this->posts = [];
    }

    public function add(Post $post)
    {
        $this->posts[serialize($post)] = $post;
    }

    /**
     * @return Post[]
     */
    public function getAll(): array
    {
        return $this->posts;
    }

    public function exists(string $title): bool
    {
        foreach ($this->posts as $post) {
            if ($post->getTitle() === $title) {
                return true;
            }
        }

        return false;
    }
}
