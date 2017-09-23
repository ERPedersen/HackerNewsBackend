<?php
/**
 * This file is responsible for mapping API routes to the appropriate
 * controllers.
 */

use Hackernews\Http\Controllers\AdminController;
use Hackernews\Http\Controllers\IndexController;
use Hackernews\Http\Controllers\AuthController;
use Hackernews\Http\Controllers\SignUpController;
use Hackernews\Http\Middleware\EnforceAuthentication;
use Hackernews\Http\Middleware\AllowCrossOrigin;

$app->get("/", IndexController::class.':index');

$app->group("/api", function () use ($app) {
    $app->get("", IndexController::class.':index');
    $app->post("/login", AuthController::class.':authenticate');
    $app->get("/admin", AdminController::class.':admin')->add(new EnforceAuthentication());
    $app->post("/signup", SignUpController::class.':signUp');
})->add(new AllowCrossOrigin());