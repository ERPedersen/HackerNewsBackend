<?php

require 'vendor/autoload.php';

use Slim\App as App;
use Slim\Container;

$container = new Container();
$app = new App($container);

require 'routes/api.php';

$app->run();