<?php
include_once("../../Models/Database/queries.php");

/**
 * If needed, i.e. when adding a new entry, the user
 * isn't expected to specify the next affectation ID,
 * and adding AUTO INCREMENT can't be done since NumAffect
 * is a VACHAR, hence we have to generate a new ID each time
 */
function GenerateNewId() : int
{
    $res = ExecQuery("SELECT * FROM AFFECTER ORDER BY NumAffect DESC LIMIT 1;"); // Last ID in the table
    $realId = intval($res->fetch_assoc()['NumAffect']) + 1;                      // New ID is thus the last + 1

    return $realId;
}

/**
 * Depends on the data sent from handler.js, this
 * function inserts or updates an entry
 */
function InsertOrUpdateAffectTable()
{
    $query = "";
    $receivedData = json_decode(file_get_contents("php://input"), true); // We get the date sent via AJAX in JSON format
    $id = intval($receivedData['numAffect']);                            // We get the ID, that will be checked if valid or not
    $editMode = (intval($receivedData['editMode']) != 0);                // We also get the EditMode, in case the ID wasn't correctly made invalid for some reason

    if ($editMode == false || $id <= 0) { // ID is invalid if it is <= 0 because our first ID must be 1
        $id = GenerateNewId();            // We generate a new ID since that field cannot be NULL
        $query = "INSERT INTO AFFECTER VALUES ('[1]', '[2]', '[3]', '[4]', '[5]', '[6]');";
    }
    else {
        $query = "UPDATE AFFECTER SET NumEmp='[2]', AncienLieu='[3]', NouveauLieu='[4]', DateAffect='[5]', DatePriseService='[6]' WHERE NumAffect='[1]';";
    }

    $dateAffect = new DateTime($receivedData['dateAffect']);
    $datePrServ = new DateTime($receivedData['datePriseService']);

    ExecPreparedQuery($query,                           // Executes a prepared query, either
                        $id,                            // an INSERT or an UPDATE,
                        $receivedData['numEmp'],        // Parameters are already in the correct
                        $receivedData['ancienLieu'],    // order.
                        $receivedData['nouveauLieu'], 
                        $dateAffect->format("Y-m-d"), 
                        $datePrServ->format("Y-m-d"));
}

/**
 * This file's main function
 */
InsertOrUpdateAffectTable();
?>