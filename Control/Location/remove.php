<?php
include_once("../../Models/Database/queries.php");

class LocationHelper
{
    public static function RemoveEntryFromDatabase()
    {
        $receivedData = json_decode(file_get_contents("php://input"), true);
        $possibelId = intval($receivedData['id']);

        if ($possibelId <= 0)
            return;

        ExecPreparedQuery("DELETE FROM LIEU WHERE IDLieu = [1];", $possibelId);
    }
}

LocationHelper::RemoveEntryFromDatabase();
?>