<?php

namespace Hackernews\Access;

use Exception;
use Hackernews\Entity\User;
use Hackernews\Exceptions\DuplicateUserException;
use Hackernews\Services\DB;
use PDOException;

/**
 * Class UserAccess
 *
 * @package Hackernews\Access
 */
class UserAccess implements IUserAccess
{
    /**
     * @var DB
     */
    private $db;

    /**
     * UserAccess constructor.
     *
     * @para
     * @param \Hackernews\Services\DB $db
     */
    public function __construct(DB $db = null)
    {
        $this->db = $db ? $db : new DB();
    }

    /**
     * @param String $username
     * @param String $password
     * @return \Hackernews\Entity\User
     * @throws \Exception
     */
    public function verifyUser(String $username, String $password)
    {
        $stmt = DB::conn()->prepare('SELECT id, email, pass, karma, alias FROM users WHERE email = :email AND pass = :pass');
        $stmt->execute(['email' => $username, 'pass' => $password]);
        $row = $stmt->fetch();

        if ($row) {
            $user = new User($row['id'], $row['email'], $row['karma'], $row['alias']);

            return $user;
        } else {
            throw new Exception("Mismatching credentials", 1);
        }
    }

    /**
     * @param String $email
     * @return \Hackernews\Entity\User
     */
    public function getUserByEmail(String $email)
    {
        $stmt = DB::conn()->prepare('SELECT id, email, karma, alias FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();
        $user = new User($row['id'], $row['email'], $row['karma'], $row['alias']);

        return $user;
    }

    /**
     * @param int $id
     * @return \Hackernews\Entity\User
     * @internal param String $email
     */
    public function getUserById(int $id)
    {
        $stmt = DB::conn()->prepare('SELECT id, email, karma, alias FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        $user = new User($row['id'], $row['email'], $row['karma'], $row['alias']);

        return $user;
    }

    /**
     * @param String $alias
     * @return \Hackernews\Entity\User
     */
    public function getUserByAlias(String $alias)
    {
        $stmt = DB::conn()->prepare('SELECT id, email, karma, alias FROM users WHERE alias = :alias');
        $stmt->execute(['alias' => $alias]);
        $row = $stmt->fetch();
        $user = new User($row['id'], $row['email'], $row['karma'], $row['alias']);

        return $user;
    }

    /**
     * @param String $email
     * @param String $password
     * @param String $alias
     * @return mixed|void
     * @throws \Exception
     */
    public function createUser(String $email, String $password, String $alias)
    {
        try {
            DB::conn()->prepare("INSERT INTO users (email, pass, alias) VALUES (:email, :pass, :alias)")->execute([
                "email" => $email,
                "pass" => $password,
                "alias" => $alias,
            ]);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new DuplicateUserException("A user already exists with that e-mail or alias", 7);
            } else {
                throw $e;
            }
        }
    }
}