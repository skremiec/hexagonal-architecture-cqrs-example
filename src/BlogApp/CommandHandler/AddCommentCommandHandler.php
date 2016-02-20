<?php declare(strict_types=1);

namespace BlogApp\CommandHandler;

use BlogApp\Boundary\CommentsRepository;
use BlogApp\Boundary\PostsRepository;
use BlogApp\Entity\Comment;
use BlogApp\Event\CommentAddedEvent;
use BlogApp\Exception\NotFoundException;
use BlogApp\Command\AddCommentCommand;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as EventDispatcher;

class AddCommentCommandHandler
{
    /** @var PostsRepository */
    private $postsRepository;

    /** @var CommentsRepository */
    private $commentsRepository;

    /** @var EventDispatcher */
    private $eventDispatcher;

    public function __construct(PostsRepository $postsRepository, CommentsRepository $commentsRepository, EventDispatcher $eventDispatcher)
    {
        $this->postsRepository = $postsRepository;
        $this->commentsRepository = $commentsRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function handle(AddCommentCommand $request)
    {
        if (!$this->postsRepository->exists($request->getPostTitle())) {
            throw new NotFoundException(sprintf('There is no post titled "%s"', $request->getPostTitle()));
        }

        $comment = new Comment($request->getPostTitle(), $request->getContent(), $request->getCreatedAt());

        $this->commentsRepository->add($comment);

        $this->eventDispatcher->dispatch(CommentAddedEvent::NAME, new CommentAddedEvent($request));
    }
}
