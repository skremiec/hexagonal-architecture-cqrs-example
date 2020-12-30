<?php declare(strict_types=1);

namespace BlogSilexApp;

use BlogSilexApp\Provider\BlogServiceProvider;
use Silex\Application;
use Silex\Provider\ServiceControllerServiceProvider;

class BlogWebApplication extends Application
{
    public function __construct(array $values = [])
    {
        parent::__construct($values);

        $this->register(new ServiceControllerServiceProvider());
        $this->register(new BlogServiceProvider());
    }
}
