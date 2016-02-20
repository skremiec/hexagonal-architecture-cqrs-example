<?php declare(strict_types=1);

namespace BlogSilexApp\Controller;

use BlogApp\Query\ListPostsQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ListPostsController
{
    /** @var ListPostsQuery */
    private $listPosts;

    public function __construct(ListPostsQuery $listPosts)
    {
        $this->listPosts = $listPosts;
    }

    public function listAction():HttpResponse
    {
        return JsonResponse::create($this->listPosts->handle());
    }
}
