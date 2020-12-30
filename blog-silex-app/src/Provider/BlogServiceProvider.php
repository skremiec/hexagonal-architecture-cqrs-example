<?php declare(strict_types=1);

namespace BlogSilexApp\Provider;

use BlogApp\Interactor\AddCommentInteractor;
use BlogApp\Interactor\CreatePostInteractor;
use BlogApp\Interactor\ListPostsInteractor;
use BlogSilexApp\Controller\AddCommentController;
use BlogSilexApp\Controller\CreatePostController;
use BlogSilexApp\Controller\ListPostsController;
use BlogSilexApp\Infrastructure\DatabaseCommentsRepository;
use BlogSilexApp\Infrastructure\DatabasePostsRepository;
use PDO;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\BootableProviderInterface;
use Silex\Application;

class BlogServiceProvider implements ServiceProviderInterface, BootableProviderInterface
{
    public function register(Container $container)
    {
        $container['database.pdo'] = function (Container $container) {
            return new PDO($container['database.dsn']);
        };

        $container['posts.repository'] = function (Container $container) {
            return new DatabasePostsRepository($container['database.pdo']);
        };

        $container['comments.repository'] = function (Container $container) {
            return new DatabaseCommentsRepository($container['database.pdo']);
        };

        $container['create_post'] = function (Container $container) {
            return new CreatePostInteractor($container['posts.repository']);
        };

        $container['list_posts'] = function (Container $container) {
            return new ListPostsInteractor($container['posts.repository'], $container['comments.repository']);
        };

        $container['add_comment'] = function (Container $container) {
            return new AddCommentInteractor($container['posts.repository'], $container['comments.repository']);
        };

        $container['create_post.controller'] = function (Container $container) {
            return new CreatePostController($container['create_post']);
        };

        $container['list_posts.controller'] = function (Container $container) {
            return new ListPostsController($container['list_posts']);
        };

        $container['add_comment.controller'] = function (Container $container) {
            return new AddCommentController($container['add_comment']);
        };

    }

    public function boot(Application $app)
    {
        $app->get('/', function (Application $app) {
            return $app->redirect($app['url_generator']->generate('list_posts'));
        });

        $app->get('/posts', 'list_posts.controller:listAction')->bind('list_posts');
        $app->post('/posts', 'create_post.controller:createAction')->bind('create_post');
        $app->post('/posts/{title}/comments', 'add_comment.controller:addAction')->bind('add_comment');
    }
}
