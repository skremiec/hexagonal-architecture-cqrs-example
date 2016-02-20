<?php declare(strict_types=1);

namespace BlogApp\Entity;

use InvalidArgumentException;

class Post
{
    /** @var string */
    private $title;

    /** @var string */
    private $content;

    /** @var string */
    private $createdAt;

    public function __construct(string $title, string $content, string $createdAt)
    {
        if (empty($title)) {
            throw new InvalidArgumentException('Title must not be empty.');
        }

        $this->title = $title;
        $this->content = $content;
        $this->createdAt = $createdAt;
    }

    public function getTitle():string
    {
        return $this->title;
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
