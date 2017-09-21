<?php
/**
 * Created by PhpStorm.
 * User: Ebbe
 * Date: 9/21/2017
 * Time: 2:45 PM
 */

namespace Hackernews\Services;

use mysqli;

/**
 * Class MySQLService
 *
 * @package Hackernews\Services
 */
class MySQLService
{
    /**
     * @return mysqli
     */
    public function getConnection() {
        return new mysqli(getenv("SERVERNAME"), getenv("USERNAME"), getenv("PASSWORD"), getenv("DBNAME"));
    }
}