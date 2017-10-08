<?php

use Hackernews\Http\Controllers\AdminController;
use Hackernews\Http\Controllers\CommentController;
use Hackernews\Http\Controllers\IndexController;
use Hackernews\Http\Controllers\AuthController;
use Hackernews\Http\Controllers\PostController;
use Hackernews\Http\Controllers\TestController;
use Hackernews\Http\Controllers\UserController;
use Hackernews\Http\Middleware\EnforceAuthentication;
use Hackernews\Http\Middleware\AllowCrossOrigin;
use Hackernews\Http\Middleware\ValidateCreatePostCredentials;
use Hackernews\Http\Middleware\ValidateLoginCredentials;
use Hackernews\Http\Middleware\ValidatePaginationCredentials;
use Hackernews\Http\Middleware\ValidateSignUpCredentials;

$app->group("", function () use ($app) {

    $app->get("/", IndexController::class . ':index');

    $app->post("/new", TestController::class . ':postTest');

    $app->post("/login", AuthController::class . ':authenticate')
        ->add(new ValidateLoginCredentials());

    $app->post("/signup", AuthController::class . ':signUp')
        ->add(new ValidateSignUpCredentials());

    $app->get("/profile", UserController::class . ':getUserData')
        ->add(new EnforceAuthentication());

    $app->get("/admin", AdminController::class . ':admin')
        ->add(new EnforceAuthentication());

    $app->post("/post", PostController::class . ':createPost')
        ->add(new ValidateCreatePostCredentials())
        ->add(new EnforceAuthentication());

    $app->get("/post", PostController::class . ':getPosts')
        ->add(new ValidatePaginationCredentials());

    $app->get("/post/{slug}", PostController::class . ':getPost');

    $app->get("/comments/{id}", CommentController::class . ':getComments')
        ->add(new ValidatePaginationCredentials());

})->add(new AllowCrossOrigin());