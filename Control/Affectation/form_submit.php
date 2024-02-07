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

    if ($id <= 0) {
        $id = GetNewId();
        $query = "INSERT INTO AFFECTER VALUES ('[1]', '[2]', '[3]', '[4]', '[5]', '[6]');";
    }
    else {
        $query = "UPDATE AFFECTER SET NumEmp=[2], AncienLieu=[2], NouveauLieu=[3], DateAffect=[4], DatePriseService=[3] WHERE NumAffect=[1];";
    }
    
    var_dump($query);
    var_dump($_POST);
    $id = $_POST['formNumAffect'];


    var_dump($_POST['formDateAffect']);
    var_dump($_POST['formPriseService']);

    $dateAffect = new DateTime($_POST['formDateAffect']);
    $datePrServ = new DateTime($_POST['formPriseService']);
    ExecPreparedQuery($query,
                        $id,
                        $_POST['formNumEmp'], 
                        $_POST['formAncienLieu'], 
                        $_POST['formNouveauLieu'], 
                        $dateAffect->format("Y-m-d"), 
                        $datePrServ->format("Y-m-d"));
}

InsertOrUpdateAffectTable();
?>