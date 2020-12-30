<?php declare(strict_types=1);

namespace BlogApp\Request;

use DateTime;

class CreatePostRequest
{
    /** @var string */
    private $title;

    /** @var string */
    private $content;

    /** @var string */
    private $createdAt;

    public function __construct(string $title, string $content)
    {
        $this->title = trim($title);
        $this->content = trim($content);
        $this->createdAt = date(DateTime::ATOM);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
}
