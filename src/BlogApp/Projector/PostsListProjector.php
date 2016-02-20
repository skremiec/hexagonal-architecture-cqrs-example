<?php declare(strict_types=1);

namespace BlogApp\Projector;

use BlogApp\Boundary\PostsQueryRepository;
use BlogApp\Event\CommentAddedEvent;
use BlogApp\Event\PostCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PostsListProjector implements EventSubscriberInterface
{
    /** @var PostsQueryRepository */
    private $postsQueryRepository;

    public function __construct(PostsQueryRepository $postsQueryRepository)
    {
        $this->postsQueryRepository = $postsQueryRepository;
    }

    public static function getSubscribedEvents():array
    {
        return [
            PostCreatedEvent::NAME => 'onPostCreated',
            CommentAddedEvent::NAME => 'onCommentAdded',
        ];
    }

    public function onPostCreated(PostCreatedEvent $event)
    {
        $this->postsQueryRepository->add([
            'title' => $event->getTitle(),
            'content' => $event->getContent(),
            'created_at' => $event->getCreatedAt(),
            'comments_count' => 0,
        ]);
    }

    public function onCommentAdded(CommentAddedEvent $event)
    {
        $postData = $this->postsQueryRepository->findByTitle($event->getPostTitle());
        $postData['comments_count'] += 1;

        $this->postsQueryRepository->update($event->getPostTitle(), $postData);
    }
}
