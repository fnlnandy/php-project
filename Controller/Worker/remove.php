<?php
include_once("../../Models/queries.php");
include_once("../../Models/table_helpers.php");

class WorkerRemove {
    public static function RemoveWorkerFromEmpAndAffect()
    {
        $dataReceived = XMLHttpRequest::DecodeJson();
        
        if (!SQLQuery::DoKeysExistInArray($dataReceived, "id"))
            return;

        $id = intval($dataReceived['id']);
        $queries = array("DELETE FROM EMPLOYE WHERE NumEmp = '[1]';",
                         "DELETE FROM AFFECTER WHERE NumEmp = '[1]';");
        
        foreach ($queries as $query) {
            SQLQuery::ExecPreparedQuery($query, $id);
        }
    }
}

WorkerRemove::RemoveWorkerFromEmpAndAffect();
?>