<?php

namespace Hackernews\Access;

use Hackernews\Entity\Hanesst;
use Hackernews\Exceptions\UpdateException;
use Hackernews\Services\DB;
use Mockery\Exception;
use PDOException;

/**
 * Class TestAccess
 * @package Hackernews\Access
 */
class TestAccess
{

    /**
     * @param int $hanesst_id
     * @throws UpdateException
     */
    public function persistHanesstId(int $hanesst_id)
    {
        try {
            $stmt = DB::conn()->prepare("UPDATE hanesst SET hanesst_id = :id WHERE id = 1");

            $count = $stmt->execute([
                "id" => $hanesst_id
            ]);

            $stmt = null;

            if ($count != 1) {
                throw new UpdateException("No rows where updated, please check if a value is present in the database", 7);
            }
        } catch (PDOException $e) {
            throw new Exception("Error occurred attempting to persist hanesst ID.");
        }
    }

    /**
     * @return Hanesst
     */
    public function latestHanesst()
    {
        try {
            $stmt = DB::conn()->prepare("SELECT h.hanesst_id AS hanesst_id FROM hanesst AS h WHERE id = 1");

            $stmt->execute();
            $row = $stmt->fetch();

            $stmt = null;

            $haneest = new Hanesst(
                $row['hanesst_id']
            );

            return $haneest;
        } catch (PDOException $e) {
            throw new Exception("Error occurred attempting to retrieve latest hanesst ID.");
        }
    }

}