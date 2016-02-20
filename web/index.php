<?php

require_once __DIR__ . '/../vendor/autoload.php';

$values = require_once __DIR__ . '/../app/config.php';

$app = new BlogSilexApp\BlogWebApplication($values);
$app->run();
