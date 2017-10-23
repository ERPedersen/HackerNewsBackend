<?php

namespace Hackernews\Access;

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
    public function persistHanesstId(int $hanesst_id) : void {
        $stmt = DB::conn()->prepare("UPDATE hanesst SET hanesst_id = :id WHERE id = 1");

        $count = $stmt->execute([
            "id" => $hanesst_id
        ]);

        if($count != 1) {
            throw new UpdateException("No rows where updated, please check if a value is present in the database", 7);
        }
    }

}