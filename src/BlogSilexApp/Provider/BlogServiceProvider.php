<?php declare(strict_types=1);

namespace BlogSilexApp\Provider;

use BlogApp\CommandHandler\AddCommentCommandHandler;
use BlogApp\CommandHandler\CreatePostCommandHandler;
use BlogApp\Infrastructure\DatabaseCommentsRepository;
use BlogApp\Infrastructure\DatabasePostsRepository;
use BlogApp\Infrastructure\FilesystemPostsQueryRepository;
use BlogApp\Query\ListPostsQuery;
use BlogApp\Projector\PostsListProjector;
use BlogApp\Command\AddCommentCommand;
use BlogApp\Command\CreatePostCommand;
use League\Tactician\Setup\QuickStart as CommandBus;
use PDO;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

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

        $app['posts.query_repository'] = $app->share(function (Application $app) {
            return new FilesystemPostsQueryRepository($app['data_dir'] . '/posts.json');
        });

        $app['comments.repository'] = $app->share(function (Application $app) {
            return new DatabaseCommentsRepository($app['database.pdo']);
        });

        $app['create_post'] = $app->share(function (Application $app) {
            return new CreatePostCommandHandler($app['posts.repository'], $app['event_dispatcher']);
        });

        $app['list_posts'] = $app->share(function (Application $app) {
            return new ListPostsQuery($app['posts.query_repository']);
        });

        $app['add_comment'] = $app->share(function (Application $app) {
            return new AddCommentCommandHandler($app['posts.repository'], $app['comments.repository'], $app['event_dispatcher']);
        });

        $app['posts_list.projector'] = $app->share(function (Application $app) {
            return new PostsListProjector($app['posts.query_repository']);
        });

        $app['command_bus'] = $app->share(function (Application $app) {
            return CommandBus::create([
                AddCommentCommand::class => $app['add_comment'],
                CreatePostCommand::class => $app['create_post'],
            ]);
        });

        $app['event_dispatcher'] = $app->share(function (Application $app) {
            $dispatcher = new EventDispatcher();
            $dispatcher->addSubscriber($app['posts_list.projector']);

            return $dispatcher;
        });
    }

    public function boot(Application $app)
    {
        $app->mount('/', new BlogControllerProvider());
    }
}
