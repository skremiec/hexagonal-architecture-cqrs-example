<?php declare(strict_types=1);

namespace BlogSilexApp;

use BlogSilexApp\Provider\BlogCommandServiceProvider;
use BlogSilexApp\Provider\BlogServiceProvider;
use Silex\Application as Container;
use Symfony\Component\Console\Application;

class BlogConsoleApplication extends Application
{
    /** @var Container */
    private $container;

    public function __construct(array $values)
    {
        parent::__construct('Blog Application', '1.0.0');

        $this->container = new Container($values);

        $this->container->register(new BlogServiceProvider());
        $this->container->register(new BlogCommandServiceProvider());
        $this->add($this->container['create_post.console_command']);
    }
}
