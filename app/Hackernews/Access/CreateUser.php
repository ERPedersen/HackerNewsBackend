<?php
/**
 * Created by PhpStorm.
 * User: Ebbe
 * Date: 9/21/2017
 * Time: 12:41 PM
 */

namespace Hackernews\Access;
use Exception;
use Hackernews\Services\MySQLService;

/**
 * Class CreateUser
 *
 * @package Hackernews\Access
 */
class CreateUser implements ICreateUser
{
    /**
     * @param String $email
     * @param String $password
     * @param String $alias
     * @return string
     * @throws Exception
     */
    public function createUser(String $email, String $password, String $alias)
    {
        $MySQL = new MySQLService();
        $conn = $MySQL->getConnection();

        try{
            $sql = "INSERT INTO users (email, pass, alias) VALUES ('" . $email . "', '" . $password . "', '" . $alias . "')";
            $conn->query($sql);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $conn->close();
        }

        return "user created";
    }
}