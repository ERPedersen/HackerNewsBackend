<?php

use Hackernews\Http\Controllers\AdminController;
use Hackernews\Http\Controllers\IndexController;
use Hackernews\Http\Controllers\AuthController;
use Hackernews\Http\Middleware\Authentication;

$app->group("/api", function () use ($app) {
    $app->get("", IndexController::class.':index');
    $app->post("/login", AuthController::class.':authenticate');
    $app->get("/admin", AdminController::class.':admin')->add(new Authentication());
});