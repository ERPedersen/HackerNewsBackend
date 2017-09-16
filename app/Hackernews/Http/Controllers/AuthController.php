<?php

namespace Hackernews\Http\Controllers;

use Hackernews\Http\Handlers\ResponseHandler;
use Hackernews\Services\TokenService;
use Slim\Http\Request;
use Slim\Http\Response;

class AuthController
{
    public function authenticate(Request $request, Response $response)
    {
        $json = $request->getParsedBody();

        $username = $json['username'];
        $password = $json['password'];

        if ($username === 'test' && $password === 'test') {
            $tokenService = new TokenService();

            $token = $tokenService->sign([
                "username" => $username,
            ]);

            return $response->withJson(ResponseHandler::success([
                "success" => true,
                "token" => $token,
            ]));
        }

        return $response->withStatus(401)->withJson([
            "success" => false,
            "message" => "Wrong password",
        ]);
    }
}