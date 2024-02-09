<?php
include_once("../../Models/Database/queries.php");

/**
 * Removes an entry having the id : $id
 * from AFFECTER if $id is > 0 (i.e. if it's valid)
 * and if the entry exists at all
 */
function RemoveEntryFromDatabase()
{
    $data = json_decode(file_get_contents("php://input"), true);
    $realId = intval($data["id"]);

    if ($realId <= 0) {
        return;
    }

    $query = "DELETE FROM AFFECTER WHERE NumAffect = [1];";
    ExecPreparedQuery($query, $realId);
}

/**
 * This file's main function
 */
RemoveEntryFromDatabase();
?>