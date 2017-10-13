<?php

use Hackernews\Http\Controllers\AdminController;
use Hackernews\Http\Controllers\CommentController;
use Hackernews\Http\Controllers\IndexController;
use Hackernews\Http\Controllers\AuthController;
use Hackernews\Http\Controllers\PostController;
use Hackernews\Http\Controllers\UserController;
use Hackernews\Http\Middleware\CheckIsLoggedIn;
use Hackernews\Http\Middleware\EnforceAuthentication;
use Hackernews\Http\Middleware\AllowCrossOrigin;
use Hackernews\Http\Middleware\ValidateCreatePostCredentials;
use Hackernews\Http\Middleware\ValidateCredentials;
use Hackernews\Http\Middleware\ValidateKarmaPoints;
use Hackernews\Http\Middleware\ValidateLoginCredentials;
use Hackernews\Http\Middleware\ValidatePaginationCredentials;
use Hackernews\Http\Middleware\ValidateSignUpCredentials;

$app->group("", function () use ($app) {

    $app->get("/", IndexController::class . ':index');

    $app->post("/login", AuthController::class . ':authenticate')
        ->add(new ValidateLoginCredentials());

    $app->post("/signup", AuthController::class . ':signUp')
        ->add(new ValidateSignUpCredentials());

    $app->get("/profile", UserController::class . ':getUserData')
        ->add(new EnforceAuthentication());

    $app->get("/admin", AdminController::class . ':admin')
        ->add(new EnforceAuthentication());

    $app->post("/hackerpost", PostController::class . ':createPost')
        ->add(new ValidateCreatePostCredentials())
        ->add(new EnforceAuthentication());

    $app->get("/hackerpost", PostController::class . ':getPosts')
        ->add(new ValidatePaginationCredentials())
        ->add(new CheckIsLoggedIn());

    $app->get("/hackerpost/{slug}", PostController::class . ':getPost')
        ->add(new CheckIsLoggedIn());

    $app->get("/comments/{id}", CommentController::class . ':getComments')
        ->add(new ValidatePaginationCredentials());

    $app->post("/upvotepost", PostController::class . ':upvotePost')
        ->add(new ValidateCredentials())
        ->add(new EnforceAuthentication());

    $app->post("/downvotepost", PostController::class . ':downvotePost')
        ->add(new ValidateKarmaPoints())
        ->add(new ValidateCredentials())
        ->add(new EnforceAuthentication());

    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    });

})->add(new AllowCrossOrigin());

