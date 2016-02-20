<?php declare(strict_types=1);

namespace BlogApp\Infrastructure;

use BlogApp\Boundary\PostsRepository;
use BlogApp\Entity\Post;
use PDO;

class DatabasePostsRepository implements PostsRepository
{
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;

        $this->pdo->exec('CREATE TABLE IF NOT EXISTS posts (title TEXT, content TEXT, created_at TEXT)');
    }

    public function clear()
    {
        $this->pdo->exec('DELETE FROM posts');
    }

    public function add(Post $post)
    {
        $this->pdo
            ->prepare('INSERT INTO posts VALUES (:title, :content, :created_at)')
            ->execute([
            'title' => $post->getTitle(),
            'content' => $post->getContent(),
            'created_at' => $post->getCreatedAt(),
        ]);
    }

    /**
     * @return Post[]
     */
    public function getAll():array
    {
        return array_map(
            [$this, 'createFromArray'],
            $this->pdo->query('SELECT * FROM posts')->fetchAll(PDO::FETCH_ASSOC)
        );
    }

    public function exists(string $title):bool
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM posts WHERE title = :title');
        $stmt->execute(['title' => $title]);
        $data = $stmt->fetch(PDO::FETCH_COLUMN);

        return (bool) $data;
    }

    private function createFromArray(array $data):Post
    {
        return new Post($data['title'], $data['content'], $data['created_at']);
    }
}
