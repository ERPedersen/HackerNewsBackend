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
use Hackernews\Http\Middleware\ValidateVoteCommentCredentials;
use Hackernews\Http\Middleware\ValidateVotePostCredentials;
use Hackernews\Http\Middleware\ValidateKarmaPoints;
use Hackernews\Http\Middleware\ValidateLoginCredentials;
use Hackernews\Http\Middleware\ValidatePaginationCredentials;
use Hackernews\Http\Middleware\ValidateSignUpCredentials;
use Hackernews\Http\Middleware\ValidateCreateCommentCredentials;

$app->group("", function () use ($app) {

    $app->get("/", IndexController::class . ':index');

    $app->post("/login", AuthController::class . ':authenticate')
        ->add(new ValidateLoginCredentials());

    $app->post("/sign-up", AuthController::class . ':signUp')
        ->add(new ValidateSignUpCredentials());

    $app->get("/profile", UserController::class . ':getUserData')
        ->add(new EnforceAuthentication());

    $app->get("/admin", AdminController::class . ':admin')
        ->add(new EnforceAuthentication());

    $app->group('/posts', function () use ($app) {
	    $app->get("", PostController::class . ':getPosts')
	        ->add(new ValidatePaginationCredentials())
	        ->add(new CheckIsLoggedIn());

	    $app->post("", PostController::class . ':createPost')
	        ->add(new ValidateCreatePostCredentials())
	        ->add(new EnforceAuthentication());

	    $app->get("/{slug}", PostController::class . ':getPost')
	        ->add(new CheckIsLoggedIn());

	    $app->post("/upvote", PostController::class . ':upvotePost')
	        ->add(new ValidateVotePostCredentials())
	        ->add(new EnforceAuthentication());

	    $app->post("/downvote", PostController::class . ':downvotePost')
	        ->add(new ValidateKarmaPoints())
	        ->add(new ValidateVotePostCredentials())
	        ->add(new EnforceAuthentication());
    });

    $app->group('/comments', function() use ($app) {
	    $app->post("", CommentController::class . ':createComment')
	        ->add(new ValidateCreateCommentCredentials())
	        ->add(new EnforceAuthentication());

		$app->get("/{id}", CommentController::class . ':getComments')
		    ->add(new ValidatePaginationCredentials());
		$app->post('/upvote', CommentController::class . ':upvoteComment')
            ->add(new ValidateVoteCommentCredentials())
            ->add(new EnforceAuthentication());
        $app->post('/downvote', CommentController::class . ':downvoteComment')
            ->add(new ValidateKarmaPoints())
            ->add(new ValidateVoteCommentCredentials())
            ->add(new EnforceAuthentication());
    });

    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    });

})->add(new AllowCrossOrigin());

