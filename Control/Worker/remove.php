<?php
include_once("../../Models/Database/queries.php");

class WorkerHelper
{
    public static function RemoveEntryFromDatabase()
    {
        $receivedData = XMLHttpRequest::DecodeJson();
        $possibelId = intval($receivedData['id']);

        if ($possibelId <= 0)
            return;

        SQLQuery::ExecPreparedQuery("DELETE FROM EMPLOYE WHERE NumEmp = [1];", $possibelId);
    }
}

WorkerHelper::RemoveEntryFromDatabase();
?>