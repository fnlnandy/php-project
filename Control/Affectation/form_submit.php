<?php
include_once("../../Models/Database/queries.php");

function GetNewId() : int
{
    // Get the very last entry
    $res = ExecQuery("SELECT * FROM AFFECTER ORDER BY NumAffect DESC LIMIT 1;");

    $realId = intval($res->fetch_assoc()['NumAffect']) + 1;

    return $realId;
}

function InsertOrUpdateAffectTable()
{
    $query = "";
    $receivedData = json_decode(file_get_contents("php://input"), true);
    $id = intval($receivedData['numAffect']);
    $editMode = (intval($receivedData['editMode']) != 0);

    if ($editMode == false || $id <= 0) {

        $id = GetNewId();
        $query = "INSERT INTO AFFECTER VALUES ('[1]', '[2]', '[3]', '[4]', '[5]', '[6]');";
    }
    else {
        $query = "UPDATE AFFECTER SET NumEmp='[2]', AncienLieu='[3]', NouveauLieu='[4]', DateAffect='[5]', DatePriseService='[6]' WHERE NumAffect='[1]';";
    }

    $dateAffect = new DateTime($receivedData['dateAffect']);
    $datePrServ = new DateTime($receivedData['datePriseService']);
    ExecPreparedQuery($query,
                        $id,
                        $receivedData['numEmp'], 
                        $receivedData['ancienLieu'], 
                        $receivedData['nouveauLieu'], 
                        $dateAffect->format("Y-m-d"), 
                        $datePrServ->format("Y-m-d"));
}

InsertOrUpdateAffectTable();
?>