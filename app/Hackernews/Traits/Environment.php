<?php


namespace Hackernews\Traits;


use Dotenv\Dotenv;

trait Environment
{

    public function __construct()
    {
        $envFile = __DIR__ . '/../../../.env';

        if (file_exists($envFile)) {
            (new Dotenv(dirname($envFile)))->load();
        } else {
            // Environment variables will be loaded in another way.
        }
    }

}