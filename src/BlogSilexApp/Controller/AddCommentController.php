<?php declare(strict_types=1);

namespace BlogSilexApp\Controller;

use BlogApp\Interactor\AddCommentInteractor;
use BlogApp\Exception\NotFoundException;
use BlogApp\Request\AddCommentRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class AddCommentController
{
    /** @var AddCommentInteractor */
    private $addComment;

    public function __construct(AddCommentInteractor $addComment)
    {
        $this->addComment = $addComment;
    }

    public function addAction(HttpRequest $request):HttpResponse
    {
        try {
            $this->addComment->handle(
                new AddCommentRequest($request->attributes->get('title'), $request->getContent())
            );
        } catch (NotFoundException $exception) {
            return JsonResponse::create(['error' => $exception->getMessage()])->setStatusCode(HttpResponse::HTTP_BAD_REQUEST);
        }

        return HttpResponse::create()->setStatusCode(HttpResponse::HTTP_CREATED);
    }
}
