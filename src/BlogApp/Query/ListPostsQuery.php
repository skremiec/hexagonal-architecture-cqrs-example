<?php declare(strict_types=1);

namespace BlogApp\Query;

use BlogApp\Boundary\PostsQueryRepository;

class ListPostsQuery
{
    /** @var PostsQueryRepository */
    private $postsQueryRepository;

    public function __construct(PostsQueryRepository $postsQueryRepository)
    {
        $this->postsQueryRepository = $postsQueryRepository;
    }

    public function handle():array
    {
        return $this->postsQueryRepository->getAll();
    }
}
