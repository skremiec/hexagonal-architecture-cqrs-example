<?php declare(strict_types=1);

namespace BlogSilexApp\Provider;

use BlogApp\Interactor\AddCommentInteractor;
use BlogApp\Interactor\CreatePostInteractor;
use BlogApp\Infrastructure\DatabaseCommentsRepository;
use BlogApp\Infrastructure\DatabasePostsRepository;
use BlogApp\Interactor\ListPostsInteractor;
use PDO;
use Silex\Application;
use Silex\ServiceProviderInterface;

class BlogServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['database.pdo'] = $app->share(function (Application $app) {
            return new PDO($app['database.dsn']);
        });

        $app['posts.repository'] = $app->share(function (Application $app) {
            return new DatabasePostsRepository($app['database.pdo']);
        });

        $app['comments.repository'] = $app->share(function (Application $app) {
            return new DatabaseCommentsRepository($app['database.pdo']);
        });

        $app['create_post'] = $app->share(function (Application $app) {
            return new CreatePostInteractor($app['posts.repository']);
        });

        $app['list_posts'] = $app->share(function (Application $app) {
            return new ListPostsInteractor($app['posts.repository'], $app['comments.repository']);
        });

        $app['add_comment'] = $app->share(function (Application $app) {
            return new AddCommentInteractor($app['posts.repository'], $app['comments.repository']);
        });
    }

    public function boot(Application $app)
    {
        $app->mount('/', new BlogControllerProvider());
    }
}
