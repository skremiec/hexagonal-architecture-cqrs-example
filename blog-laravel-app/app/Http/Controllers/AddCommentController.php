<?php

namespace App\Http\Controllers;

use BlogApp\Exception\NotFoundException;
use BlogApp\Interactor\AddCommentInteractor;
use BlogApp\Request\AddCommentRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class AddCommentController extends Controller
{
    public function __invoke(Request $request, string $postTitle, AddCommentInteractor $addComment): Response
    {
        try {
            $addComment->handle(new AddCommentRequest(
                $postTitle,
                $request->getContent()
            ));
        } catch (NotFoundException $exception) {
            return response(['error' => $exception->getMessage()], Response::HTTP_NOT_FOUND);
        }

        return response(status: Response::HTTP_CREATED);
    }
}
