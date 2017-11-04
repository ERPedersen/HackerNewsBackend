<?php

require 'vendor/autoload.php';
require 'config/setup.php';

use Slim\App as App;
use Slim\Container;
use Dotenv\Dotenv;

$dotenv = new Dotenv(__DIR__);
$dotenv->load();

$container = new Container();
$container['settings']['displayErrorDetails'] = true;


$app = new App($container);

require 'routes/api.php';
$app->run();