<?php declare(strict_types=1);

namespace BlogSilexApp\Controller;

use BlogApp\Interactor\CreatePostInteractor;
use BlogApp\Exception\AlreadyExistsException;
use BlogApp\Request\CreatePostRequest;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class CreatePostController
{
    /** @var CreatePostInteractor */
    private $createPost;

    public function __construct(CreatePostInteractor $createPost)
    {
        $this->createPost = $createPost;
    }

    public function createAction(HttpRequest $request):HttpResponse
    {
        $data = json_decode($request->getContent(), true) + array_fill_keys(['title', 'content'], '');

        try {
            $this->createPost->handle(new CreatePostRequest($data['title'], $data['content']));
        } catch (InvalidArgumentException $exception) {
            return JsonResponse::create(['error' => $exception->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        } catch (AlreadyExistsException $exception) {
            return JsonResponse::create(['error' => $exception->getMessage()], HttpResponse::HTTP_CONFLICT);
        }

        return HttpResponse::create()->setStatusCode(HttpResponse::HTTP_CREATED);
    }
}
