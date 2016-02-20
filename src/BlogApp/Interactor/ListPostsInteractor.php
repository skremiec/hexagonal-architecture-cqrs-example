<?php declare(strict_types=1);

namespace BlogApp\Interactor;

use BlogApp\Boundary\CommentsRepository;
use BlogApp\Boundary\PostsRepository;
use BlogApp\Entity\Post;

class ListPostsInteractor
{
    /** @var PostsRepository */
    private $postsRepository;

    /** @var CommentsRepository */
    private $commentsRepository;

    public function __construct(PostsRepository $postsRepository, CommentsRepository $commentsRepository)
    {
        $this->postsRepository = $postsRepository;
        $this->commentsRepository = $commentsRepository;
    }

    /**
     * @return array
     */
    public function handle():array
    {
        return array_map(function (Post $post) {
            return [
                'title' => $post->getTitle(),
                'content' => $post->getContent(),
                'created_at' => $post->getCreatedAt(),
                'comments_count' => $this->commentsRepository->getCountFor($post->getTitle())
            ];
        }, $this->postsRepository->getAll());
    }
}
