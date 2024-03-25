<?php
include_once("../../Models/queries.php");
include_once("../../Models/table_helpers.php");

/**
 * Container with a function called on removal of a worker
 */
class WorkerRemove {
    /**
     * Handles removing a worker from the database, since affectations
     * also depend on the employee's number: we remove them as well.
     */
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

/**
 * This file's main and only callback
 */
WorkerRemove::RemoveWorkerFromEmpAndAffect();
?>