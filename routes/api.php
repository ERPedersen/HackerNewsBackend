<?php

use Hackernews\Controllers\IndexController;

$app->get( "/", IndexController::class . ':index' );