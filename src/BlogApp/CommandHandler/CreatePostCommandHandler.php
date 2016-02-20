<?php declare(strict_types=1);

namespace BlogApp\CommandHandler;

use BlogApp\Boundary\PostsRepository;
use BlogApp\Entity\Post;
use BlogApp\Event\PostCreatedEvent;
use BlogApp\Exception\AlreadyExistsException;
use BlogApp\Command\CreatePostCommand;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as EventDispatcher;

class CreatePostCommandHandler
{
    /** @var PostsRepository */
    private $postsRepository;

    /** @var EventDispatcher */
    private $eventDispatcher;

    public function __construct(PostsRepository $postsRepository, EventDispatcher $eventDispatcher)
    {
        $this->postsRepository = $postsRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function handle(CreatePostCommand $request)
    {
        if ($this->postsRepository->exists($request->getTitle())) {
            throw new AlreadyExistsException(sprintf('There already is a post titled "%s"', $request->getTitle()));
        }

        $post = new Post($request->getTitle(), $request->getContent(), $request->getCreatedAt());

        $this->postsRepository->add($post);

        $this->eventDispatcher->dispatch(PostCreatedEvent::NAME, new PostCreatedEvent($request));
    }
}
