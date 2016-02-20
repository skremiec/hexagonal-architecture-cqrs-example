<?php declare(strict_types=1);

namespace BlogApp\Infrastructure;

use BlogApp\Boundary\PostsQueryRepository;

class FilesystemPostsQueryRepository implements PostsQueryRepository
{
    /** @var string */
    private $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function getAll():array
    {
        if (!file_exists($this->filename)) {
            return [];
        }

        return json_decode(file_get_contents($this->filename), true);
    }

    public function add(array $data)
    {
        $this->save(array_merge($this->getAll(), [$data]));
    }

    public function findByTitle(string $postTitle):array
    {
        foreach ($this->getAll() as $postData) {
            if ($postData['title'] === $postTitle) {
                return $postData;
            }
        }
    }

    public function update(string $postTitle, array $postData)
    {
        $data = $this->getAll();

        foreach ($data as &$post) {
            if ($post['title'] === $postTitle) {
                $post = $postData;
            }
        }

        $this->save($data);
    }

    private function save(array $data)
    {
        file_put_contents($this->filename, json_encode($data, JSON_PRETTY_PRINT));
    }
}
