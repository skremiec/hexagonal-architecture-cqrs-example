<?php declare(strict_types=1);

namespace BlogApp\Event;

use BlogApp\Command\CreatePostCommand;
use Symfony\Component\EventDispatcher\Event;

class PostCreatedEvent extends Event
{
    const NAME = 'post_created';

    /** @var CreatePostCommand */
    private $request;

    public function __construct(CreatePostCommand $request)
    {
        $this->request = $request;
    }

    public function getTitle():string
    {
        return $this->request->getTitle();
    }

    public function getContent():string
    {
        return $this->request->getContent();
    }

    public function getCreatedAt():string
    {
        return $this->request->getCreatedAt();
    }
}
