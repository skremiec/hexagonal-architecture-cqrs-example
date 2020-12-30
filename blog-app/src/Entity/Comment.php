<?php declare(strict_types=1);

namespace BlogApp\Entity;

class Comment
{
    /** @var string */
    private $postTitle;

    /** @var string */
    private $content;

    /** @var string */
    private $createdAt;

    public function __construct(string $postTitle, string $content, string $createdAt)
    {
        $this->postTitle = $postTitle;
        $this->content = $content;
        $this->createdAt = $createdAt;
    }

    public function getPostTitle(): string
    {
        return $this->postTitle;
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
