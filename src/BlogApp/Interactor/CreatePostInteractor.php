<?php declare(strict_types=1);

namespace BlogApp\Interactor;

use BlogApp\Boundary\PostsRepository;
use BlogApp\Entity\Post;
use BlogApp\Exception\AlreadyExistsException;
use BlogApp\Request\CreatePostRequest;

class CreatePostInteractor
{
    /** @var PostsRepository */
    private $postsRepository;

    public function __construct(PostsRepository $postsRepository)
    {
        $this->postsRepository = $postsRepository;
    }

    public function handle(CreatePostRequest $request)
    {
        if ($this->postsRepository->exists($request->getTitle())) {
            throw new AlreadyExistsException(sprintf('There already is a post titled "%s"', $request->getTitle()));
        }

        $this->postsRepository->add(new Post($request->getTitle(), $request->getContent(), $request->getCreatedAt()));
    }
}
