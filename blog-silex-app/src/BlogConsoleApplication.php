<?php declare(strict_types=1);

namespace BlogSilexApp;

use BlogSilexApp\Command\CreatePostConsoleCommand;
use BlogSilexApp\Provider\BlogServiceProvider;
use Pimple\Container;
use Symfony\Component\Console\Application;

class BlogConsoleApplication extends Application
{
    public function __construct(array $values)
    {
        parent::__construct('Blog Application');

        $container = new Container($values);
        $container->register(new BlogServiceProvider());

        $this->add(new CreatePostConsoleCommand($container['create_post']));
    }
}
