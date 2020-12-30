<?php declare(strict_types=1);

namespace BlogSilexApp;

use Psr\Container\ContainerInterface;
use Silex\Application;

class Container implements ContainerInterface
{
    /** @var Application */
    private $app;

    public static function create(): self
    {
        return new self();
    }

    public function __construct()
    {
        $values = require 'config.php';
        $this->app = new BlogWebApplication($values);
    }

    /**
     * @param string $id
     */
    public function has($id): bool
    {
        return $this->app->offsetExists($id);
    }

    /**
     * @param string $id
     */
    public function get($id)
    {
        return $this->app[$id];
    }
}
