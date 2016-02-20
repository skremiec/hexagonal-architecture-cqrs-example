<?php declare(strict_types=1);

namespace BlogApp\Request;

use DateTime;

class AddCommentRequest
{
    /** @var string */
    private $postTitle;

    /** @var string */
    private $content;

    /** @var string */
    private $createdAt;

    public function __construct(string $postTitle, string $content)
    {
        $this->postTitle = $postTitle;
        $this->content = $content;
        $this->createdAt = date(DateTime::ATOM);
    }

    public function getPostTitle():string
    {
        return $this->postTitle;
    }

    public function getContent():string
    {
        return $this->content;
    }

    public function getCreatedAt():string
    {
        return $this->createdAt;
    }
}
