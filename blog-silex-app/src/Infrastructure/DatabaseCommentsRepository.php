<?php declare(strict_types=1);

namespace BlogSilexApp\Infrastructure;

use BlogApp\Boundary\CommentsRepository;
use BlogApp\Entity\Comment;
use PDO;

class DatabaseCommentsRepository implements CommentsRepository
{
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->pdo->exec('CREATE TABLE IF NOT EXISTS comments (post_title TEXT, content TEXT, created_at TEXT)');
    }

    public function clear()
    {
        $this->pdo->exec('DELETE FROM comments');
    }

    public function add(Comment $comment)
    {
        $this->pdo
            ->prepare('INSERT INTO comments VALUES (:post_title, :content, :created_at)')
            ->execute([
                'post_title' => $comment->getPostTitle(),
                'content' => $comment->getContent(),
                'created_at' => $comment->getCreatedAt(),
            ]);
    }

    /**
     * @return Comment[]
     */
    public function getAll(): array
    {
        return array_map(
            [$this, 'createFromArray'],
            $this->pdo->query('SELECT * FROM comments')->fetchAll(PDO::FETCH_ASSOC)
        );
    }

    public function getCountFor(string $postTitle): int
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) as count FROM comments WHERE post_title = :post_title');
        $stmt->execute(['post_title' => $postTitle]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) $result['count'];
    }

    private function createFromArray(array $data): Comment
    {
        return new Comment($data['post_title'], $data['content'], $data['created_at']);
    }
}
