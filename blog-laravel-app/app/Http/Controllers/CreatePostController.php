<?php

namespace App\Http\Controllers;

use BlogApp\Exception\AlreadyExistsException;
use BlogApp\Interactor\CreatePostInteractor;
use BlogApp\Request\CreatePostRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use InvalidArgumentException;

class CreatePostController extends Controller
{
    public function __invoke(Request $request, CreatePostInteractor $createPost): Response
    {
        try {
            $createPost->handle(new CreatePostRequest(
                $request->json('title', ''),
                $request->json('content', '')
            ));
        } catch (InvalidArgumentException $exception) {
            return response(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (AlreadyExistsException $exception) {
            return response(['error' => $exception->getMessage()], Response::HTTP_CONFLICT);
        }

        return response(status: Response::HTTP_CREATED);
    }
}
