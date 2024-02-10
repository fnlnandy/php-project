<?php 
include_once("../../Models/Database/queries.php");

function PopulateSearchBarResults()
{
    $receivedData = json_decode(file_get_contents("php://input"), true);
    $nameToSearch = $receivedData['Nom'];
    $firstnameToSearch = $receivedData['Prenom'];
    $queryToExec = "SELECT * FROM EMPLOYE WHERE NOM LIKE '%[1]%' OR NOM LIKE '%[2]%' OR PRENOM LIKE '%[1]%' OR PRENOM LIKE '%[3]%';";
    $queryResult = ExecPreparedQuery($queryToExec, $nameToSearch.$firstnameToSearch, $nameToSearch, $firstnameToSearch);

    while ($row = $queryResult->fetch_assoc()) {
        echo "<td>";
            
        echo "</td>";
    }
}

/**
 * This file's main function
 */
PopulateSearchBarResults();
?>