<?php
/**
 * Created by PhpStorm.
 * User: Ebbe
 * Date: 9/21/2017
 * Time: 2:45 PM
 */

namespace Hackernews\Services;

use PDO;

/**
 * Class MySQLService
 *
 * @package Hackernews\Services
 */
class DB
{
    private static $pdo;

    public static function conn()
    {
        if (! isset(self::$pdo)) {

            $dbHost = getenv("DB_HOST");
            $dbSchema = getenv("DB_SCHEMA");
            $dbUser = getenv("DB_USER");
            $dbPass = getenv("DB_PASS");
            $dbCharset = 'utf8';

            $dbString = "mysql:host=$dbHost;dbname=$dbSchema;charset=$dbCharset";

            $dbOptions = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            self::$pdo = new PDO($dbString, $dbUser, $dbPass, $dbOptions);
        }

        return self::$pdo;
    }

    public static function close() {
        self::$pdo = null;
    }
}