<?php declare(strict_types=1);

namespace BlogSilexApp\Controller;

use BlogApp\Exception\AlreadyExistsException;
use BlogApp\Command\CreatePostCommand;
use InvalidArgumentException;
use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class CreatePostController
{
    /** @var CommandBus */
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function createAction(HttpRequest $request):HttpResponse
    {
        $data = json_decode($request->getContent(), true) + array_fill_keys(['title', 'content'], '');

        try {
            $this->commandBus->handle(new CreatePostCommand($data['title'], $data['content']));
        } catch (InvalidArgumentException $exception) {
            return JsonResponse::create(['error' => $exception->getMessage()], HttpResponse::HTTP_BAD_REQUEST);
        } catch (AlreadyExistsException $exception) {
            return JsonResponse::create(['error' => $exception->getMessage()], HttpResponse::HTTP_CONFLICT);
        }

        return HttpResponse::create()->setStatusCode(HttpResponse::HTTP_CREATED);
    }
}
