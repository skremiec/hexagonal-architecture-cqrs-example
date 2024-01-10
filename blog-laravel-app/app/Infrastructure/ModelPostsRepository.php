<?php declare(strict_types=1);

namespace App\Infrastructure;

use App\Models\Post as PostModel;
use BlogApp\Boundary\PostsRepository;
use BlogApp\Entity\Post;

class ModelPostsRepository implements PostsRepository
{
    public function clear(): void
    {
        PostModel::query()->delete();
    }

    public function add(Post $post): void
    {
        $model = new PostModel([
            'title' => $post->getTitle(),
            'content' => $post->getContent(),
            'created_at' => $post->getCreatedAt(),
        ]);

        $model->save();
    }

    /**
     * @return Post[]
     */
    public function getAll(): array
    {
        return PostModel::all()
            ->map(fn (PostModel $post) => new Post($post['title'], $post['content'], $post['created_at']->toString()))
            ->all();
    }

    public function exists(string $title): bool
    {
        return PostModel::query()->where('title', '=', $title)->exists();
    }
}
