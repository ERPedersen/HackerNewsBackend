<<<<<<< Updated upstream
<?php

require 'vendor/autoload.php';

use Slim\App as App;
use Slim\Container;
use Dotenv\Dotenv;

$dotenv = new Dotenv(__DIR__);
$dotenv->load();

$container = new Container();
$container['settings']['displayErrorDetails'] = true;


$app = new App($container);

require 'routes/api.php';

=======
<?php

require 'vendor/autoload.php';

use Slim\App as App;
use Slim\Container;
use Dotenv\Dotenv;

$dotenv = new Dotenv(__DIR__);
$dotenv->load();

$container = new Container();
$container['settings']['displayErrorDetails'] = true;

$app = new App($container);

require 'routes/api.php';

>>>>>>> Stashed changes
$app->run();