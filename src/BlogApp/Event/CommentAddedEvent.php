<?php declare(strict_types=1);

namespace BlogApp\Event;

use BlogApp\Command\AddCommentCommand;
use Symfony\Component\EventDispatcher\Event;

class CommentAddedEvent extends Event
{
    const NAME = 'commend_added';

    /** @var AddCommentCommand */
    private $request;

    public function __construct(AddCommentCommand $request)
    {
        $this->request = $request;
    }

    public function getPostTitle():string
    {
        return $this->request->getPostTitle();
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
