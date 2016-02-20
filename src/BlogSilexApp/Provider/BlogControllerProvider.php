<?php declare(strict_types=1);

namespace BlogSilexApp\Provider;

use BlogSilexApp\Controller\AddCommentController;
use BlogSilexApp\Controller\CreatePostController;
use BlogSilexApp\Controller\ListPostsController;
use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;

class BlogControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app):ControllerCollection
    {
        $app['create_post.controller'] = $app->share(function (Application $app) {
            return new CreatePostController($app['command_bus']);
        });

        $app['list_posts.controller'] = $app->share(function (Application $app) {
            return new ListPostsController($app['list_posts']);
        });

        $app['add_comment.controller'] = $app->share(function (Application $app) {
            return new AddCommentController($app['command_bus']);
        });

        /** @var ControllerCollection $controllers */
        $controllers = $app['controllers_factory'];

        $controllers->get('/', function (Application $app) {
            return $app->redirect($app['url_generator']->generate('list_posts'));
        });

        $controllers->get('/posts', 'list_posts.controller:listAction')->bind('list_posts');
        $controllers->post('/posts', 'create_post.controller:createAction')->bind('create_post');
        $controllers->post('/posts/{title}/comments', 'add_comment.controller:addAction')->bind('add_comment');

        return $controllers;
    }
}
