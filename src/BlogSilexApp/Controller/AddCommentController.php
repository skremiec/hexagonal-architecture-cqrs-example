<?php declare(strict_types=1);

namespace BlogSilexApp\Controller;

use BlogApp\Exception\NotFoundException;
use BlogApp\Command\AddCommentCommand;
use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class AddCommentController
{
    /** @var CommandBus */
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function addAction(HttpRequest $request):HttpResponse
    {
        try {
            $this->commandBus->handle(
                new AddCommentCommand($request->attributes->get('title'), $request->getContent())
            );
        } catch (NotFoundException $exception) {
            return JsonResponse::create(['error' => $exception->getMessage()])->setStatusCode(HttpResponse::HTTP_BAD_REQUEST);
        }

        return HttpResponse::create()->setStatusCode(HttpResponse::HTTP_CREATED);
    }
}
