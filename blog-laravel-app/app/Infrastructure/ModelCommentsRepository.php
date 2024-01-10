<?php declare(strict_types=1);

namespace App\Infrastructure;

use App\Models\Comment as CommentModel;
use BlogApp\Boundary\CommentsRepository;
use BlogApp\Entity\Comment;

class ModelCommentsRepository implements CommentsRepository
{
    public function clear(): void
    {
        CommentModel::query()->delete();
    }

    public function add(Comment $comment): void
    {
        $model = new CommentModel([
            'post_title' => $comment->getPostTitle(),
            'content' => $comment->getContent(),
            'created_at' => $comment->getCreatedAt(),
        ]);

        $model->save();
    }

    /**
     * @return Comment[]
     */
    public function getAll(): array
    {
        return CommentModel::all()
            ->map(fn (CommentModel $comment) => new Comment($comment['post_title'], $comment['content'], $comment['created_at']->toString()))
            ->all();
    }

    public function getCountFor(string $postTitle): int
    {
        return CommentModel::query()->where('post_title', '=', $postTitle)->count();
    }
}
