<?php

namespace App\Http\Controllers;

use BlogApp\Interactor\ListPostsInteractor;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class ListPostsController extends Controller
{
    public function __invoke(ListPostsInteractor $listPosts): Response
    {
        return response($listPosts->handle());
    }
}
