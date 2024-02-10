<?php
include_once("../../Models/Database/queries.php");

class LocationHelper
{
    public static function RemoveEntryFromDatabase()
    {
        $receivedData = XMLHttpRequest::DecodeJson();
        $possibelId = intval($receivedData['id']);

        if ($possibelId <= 0)
            return;

        SQLQuery::ExecPreparedQuery("DELETE FROM LIEU WHERE IDLieu = [1];", $possibelId);
    }
}

LocationHelper::RemoveEntryFromDatabase();
?>