<?php 
include_once("../Models/table_helpers.php");

/**
 * Containers for basic functions loaded before
 * the worker page, are used to limit which workers
 * are displayed according to if they were affected and/or
 * are matching the current search's pattern
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
     * Gets all the employees that have all been affected
     */
    public static function GetEntriesInAffection(): bool|mysqli_result
    {   
        $queryToExecute = "SELECT * FROM EMPLOYE WHERE NumEmp IN (SELECT NumEmp FROM AFFECTER);";
        $result = SQLQuery::ExecQuery($queryToExecute);

        return $result;
    }

    /**
     * Gets all the employees that have not been affected
     */
    public static function GetEntriesNotInAffectation()
    {
        $queryToExecute = "SELECT * FROM EMPLOYE WHERE NumEmp NOT IN (SELECT NumEmp FROM AFFECTER);";
        $result = SQLQuery::ExecQuery($queryToExecute);
    
        return $result;
    }

    /**
     * Gets all the employees that match the pattern specified
     * in the searchbar
     */
    public static function GetMatchingEntriesToSearchbar(): bool|mysqli_result
    {
        if (!key_exists('worker-search-bar', $_GET)) {
            return false;
        }
        if ($_GET['worker-search-bar'] == "") {
            return false;
        }

        $queryToExecute = "SELECT * FROM EMPLOYE WHERE Nom LIKE '%[1]%' OR Prenom LIKE '%[1]%' OR CONCAT(Nom, ' ', Prenom) LIKE '%[1]%' OR CONCAT(Prenom, ' ', Nom) LIKE '%[1]%' 
                OR LOWER(Nom) LIKE '%[2]%' OR LOWER(Prenom) LIKE '%[2]%' OR LOWER(CONCAT(Nom, ' ', Prenom)) LIKE '%[2]%' OR LOWER(CONCAT(Prenom, ' ', Nom)) LIKE '%[2]%';";
        $cleanString = str_replace("+", " ", $_GET['worker-search-bar']); // To remove the '+' character in the string, which is supposed to be a space
        $lowerCase = strtolower($cleanString);
        $result = SQLQuery::ExecPreparedQuery($queryToExecute, $cleanString, $lowerCase); 

        return $result;
    }

    /**
     * Populates the employees list according
     * to different conditions
     */
    public static function PopulateWorkersAfterTaskConditions()
    {
        $mainResults = SQLQuery::ExecQuery("SELECT * FROM EMPLOYE ORDER BY LENGTH(NumEmp) ASC, NumEmp ASC;");
        $counter = 0;
        $entriesInSearchBar = WorkerPageConditions::GetMatchingEntriesToSearchbar();
        $entriesAffected = WorkerPageConditions::GetEntriesInAffection();
        $entriesUnaffected = WorkerPageConditions::GetEntriesNotInAffectation();

        while ($rowInDatabase = $mainResults->fetch_assoc()) {
            // If the key doesn't exist in one of the arrays that match
            // the current searching conditions, automatically skip
            if (key_exists('worker-search-bar', $_GET)          && $_GET['worker-search-bar'] != ""            && !WorkerPageConditions::IsNumEmpInResult($rowInDatabase, $entriesInSearchBar)) {
                continue;
            }
            if (key_exists('search-bar-show-affected', $_GET)   && $_GET['search-bar-show-affected'] == 'on'   && !WorkerPageConditions::IsNumEmpInResult($rowInDatabase, $entriesAffected)) {
                continue;
            }
            if (key_exists('search-bar-show-unaffected', $_GET) && $_GET['search-bar-show-unaffected'] == 'on' && !WorkerPageConditions::IsNumEmpInResult($rowInDatabase, $entriesUnaffected)) {
                continue;
            }

            // Printing the rows with the correct call to UpdateDataTracker
            $counter = $rowInDatabase['NumEmp'];
            $secondQuery = SQLQuery::ExecPreparedQuery("SELECT Design, Province FROM LIEU WHERE IDLieu = '[1]';", $rowInDatabase['Lieu']);

            if (!$secondQuery || is_null($secondQuery))
                continue;

            $locData = $secondQuery->fetch_assoc();

            if (is_null($locData) || !key_exists('Design', $locData) || !key_exists('Province', $locData))
                continue;

            echo "<tr class=\"worker-table-row\" onclick=\"UpdateDataTracker(".strval($counter).", true)\">";

            echo "<td>{$rowInDatabase['NumEmp']}</td>";
            echo "<td>{$rowInDatabase['Civilite']}</td>";
            echo "<td>{$rowInDatabase['Nom']}</td>";
            echo "<td>{$rowInDatabase['Prenom']}</td>";
            echo "<td>{$rowInDatabase['Mail']}</td>";
            echo "<td>{$rowInDatabase['Poste']}</td>";
            echo "<td>{$locData['Design']} ({$locData['Province']})</td>";

            echo "</tr>";
        }
    }
}
?>