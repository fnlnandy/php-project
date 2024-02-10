<?php 
include_once("../Models/table_helpers.php");

class WorkerPageConditions {
    /**
     * 
     */
    public static function PopulateWorkersBasedOnGet(): bool
    {
        if (!key_exists('searchBar', $_GET)) {
            return false;
        }
        if ($_GET['searchBar'] == "") {
            return false;
        }

        $queryToExecute = "SELECT * FROM EMPLOYE WHERE Nom LIKE '%[1]%' OR Prenom LIKE '%[1]%' ORDER BY NumEmp ASC;";
        $result = SQLQuery::ExecPreparedQuery($queryToExecute, $_GET['searchBar']);
        TableHelper::PopulateTableElementWithQueryResult($result, "NumEmp", "workerRow");
        return true;
    }

    /**
     * Basically will check for a couple of conditions to display
     * the table, for example if there was a name of a firstname searched
     * or if a checkbox for non-affected workers has been checked
     */
    public static function PopulateWorkersAfterConditions()
    {
        if (WorkerPageConditions::PopulateWorkersBasedOnGet())
            return;
        TableHelper::PopulateTableElementWithDatabseData("EMPLOYE", "NumEmp", "workerRow");
    }
}
?>