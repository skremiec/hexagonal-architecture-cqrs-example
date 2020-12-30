<?php declare(strict_types=1);

namespace BlogSilexApp\Controller;

use BlogApp\Interactor\ListPostsInteractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ListPostsController
{
    /** @var ListPostsInteractor */
    private $listPosts;

    public function __construct(ListPostsInteractor $listPosts)
    {
        $this->listPosts = $listPosts;
    }

    public function listAction():HttpResponse
    {
        return JsonResponse::create($this->listPosts->handle());
    }
}
