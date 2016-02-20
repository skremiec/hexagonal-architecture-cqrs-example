<?php declare(strict_types=1);

namespace BlogSilexApp\Provider;

use BlogSilexApp\Command\CreatePostConsoleCommand;
use Silex\Application;
use Silex\ServiceProviderInterface;

class BlogCommandServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['create_post.console_command'] = $app->share(function (Application $app) {
            return new CreatePostConsoleCommand($app['create_post']);
        });
    }

    public function boot(Application $app)
    {
    }
}
