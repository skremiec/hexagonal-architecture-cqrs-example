<?php declare(strict_types=1);

namespace BlogApp\Interactor;

use BlogApp\Boundary\CommentsRepository;
use BlogApp\Boundary\PostsRepository;
use BlogApp\Entity\Comment;
use BlogApp\Exception\NotFoundException;
use BlogApp\Request\AddCommentRequest;

class AddCommentInteractor
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

    public function handle(AddCommentRequest $request)
    {
        if (!$this->postsRepository->exists($request->getPostTitle())) {
            throw new NotFoundException(sprintf('There is no post titled "%s"', $request->getPostTitle()));
        }

        $comment = new Comment($request->getPostTitle(), $request->getContent(), $request->getCreatedAt());

        $this->commentsRepository->add($comment);
    }
}
