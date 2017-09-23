<?php

use Hackernews\Http\Controllers\AdminController;
use Hackernews\Http\Controllers\IndexController;
use Hackernews\Http\Controllers\AuthController;
use Hackernews\Http\Controllers\PostController;
use Hackernews\Http\Middleware\EnforceAuthentication;
use Hackernews\Http\Middleware\AllowCrossOrigin;
use Hackernews\Http\Middleware\ValidateCreatePostCredentials;
use Hackernews\Http\Middleware\ValidateLoginCredentials;
use Hackernews\Http\Middleware\ValidateSignUpCredentials;

$app->group("", function () use ($app) {

    $app->get("/", IndexController::class.':index');

    $app->post("/login", AuthController::class.':authenticate')
        ->add(new ValidateLoginCredentials());

    $app->post("/signup", AuthController::class.':signUp')
        ->add(new ValidateSignUpCredentials());

    $app->get("/admin", AdminController::class.':admin')
        ->add(new EnforceAuthentication());

    $app->post("/post", PostController::class.':createPost')
        ->add(new ValidateCreatePostCredentials())
        ->add(new EnforceAuthentication());

})->add(new AllowCrossOrigin());