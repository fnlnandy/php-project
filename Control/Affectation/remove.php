<?php
include_once("../../Models/Database/queries.php");

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

RemoveEntryFromDatabase();
?>