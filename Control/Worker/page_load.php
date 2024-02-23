<?php 
include_once("../Models/table_helpers.php");

/**
 * Basic container to avoid *eventual* naming
 * conflicts
 */
class WorkerPageConditions {
    /**
     * Verifies if a value, specifically an ID, is present inside a result
     */
    public static function IsNumEmpInResult(array $toCheck, mysqli_result $subject): bool
    {
        if (!key_exists('NumEmp', $toCheck))
            return false;

        $subject->data_seek(0); // Because we're fetching, we need the cursor at the beginning
        
        while ($row = $subject->fetch_assoc()) {
            if ($row['NumEmp'] == $toCheck['NumEmp'])
                return true;
        }

        return false;
    }
    /**
     * 
     */
    public static function GetEntriesInAffection(): bool|mysqli_result
    {   
        $queryToExecute = "SELECT * FROM EMPLOYE WHERE NumEmp IN (SELECT NumEmp FROM AFFECTER);";
        $result = SQLQuery::ExecQuery($queryToExecute);

        return $result;
    }

    /**
     * 
     */
    public static function GetEntriesNotInAffectation()
    {
        $queryToExecute = "SELECT * FROM EMPLOYE WHERE NumEmp NOT IN (SELECT NumEmp FROM AFFECTER);";
        $result = SQLQuery::ExecQuery($queryToExecute);
    
        return $result;
    }

    /**
     * 
     */
    public static function GetMatchingEntriesToSearchbar(): bool|mysqli_result
    {
        if (!key_exists('searchBar', $_GET)) {
            return false;
        }
        if ($_GET['searchBar'] == "") {
            return false;
        }

        $queryToExecute = "SELECT * FROM EMPLOYE WHERE Nom LIKE '%[1]%' OR Prenom LIKE '%[1]%';";
        $result = SQLQuery::ExecPreparedQuery($queryToExecute, $_GET['searchBar']);
        return $result;
    }

    /**
     * 
     */
    public static function PopulateWorkersAfterTaskConditions()
    {
        $mainResults = SQLQuery::ExecQuery("SELECT * FROM EMPLOYE ORDER BY NumEmp ASC;");
        $counter = 0;
        $entriesInSearchBar = WorkerPageConditions::GetMatchingEntriesToSearchbar();
        $entriesAffected = WorkerPageConditions::GetEntriesInAffection();
        $entriesUnaffected = WorkerPageConditions::GetEntriesNotInAffectation();

        while ($rowInDatabase = $mainResults->fetch_assoc()) {
            if (key_exists('searchBar', $_GET)          && $_GET['searchBar'] != ""            && !WorkerPageConditions::IsNumEmpInResult($rowInDatabase, $entriesInSearchBar)) {
                continue;
            }
            if (key_exists('showAffectedOnes', $_GET)   && $_GET['showAffectedOnes'] == 'on'   && !WorkerPageConditions::IsNumEmpInResult($rowInDatabase, $entriesAffected)) {
                continue;
            }
            if (key_exists('showUnaffectedOnes', $_GET) && $_GET['showUnaffectedOnes'] == 'on' && !WorkerPageConditions::IsNumEmpInResult($rowInDatabase, $entriesUnaffected)) {
                continue;
            }

            $counter = $rowInDatabase['NumEmp'];

            echo "<tr class=\"workerRow\" onclick=\"UpdateDataTracker(".strval($counter).", true)\">";

            foreach ($rowInDatabase as $column) {
                echo "<td>".$column."</td>";
            }

            echo "</tr>";
        }
    }
}
?>