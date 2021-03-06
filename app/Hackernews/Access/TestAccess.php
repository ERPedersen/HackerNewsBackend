<?php

namespace Hackernews\Access;

use Hackernews\Entity\Hanesst;
use Hackernews\Exceptions\UpdateException;
use Hackernews\Services\DB;

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
    public function updateHanesstId(int $hanesst_id)
    {
        $stmt = DB::conn()->prepare("UPDATE hanesst SET hanesst_id = :id WHERE id = 1");

        $count = $stmt->execute([
            "id" => $hanesst_id
        ]);

        if ($count != 1) {
            throw new UpdateException("No rows where updated, please check if a value is present in the database", 7);
        }
    }

    /**
     * @return Hanesst
     */
    public function getHanesstId()
    {
        $stmt = DB::conn()->prepare("SELECT h.hanesst_id AS hanesst_id FROM hanesst AS h WHERE id = 1");

        $stmt->execute();
        $row = $stmt->fetch();

        $haneest = new Hanesst(
            $row['hanesst_id']
        );

        return $haneest;
    }

}