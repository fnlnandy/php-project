<?php
include_once("../../Models/Database/queries.php");

function GetNewId() : int
{
    // Get the very last entry
    $res = ExecQuery("SELECT * FROM AFFECTER ORDER BY NumAffect DESC LIMIT 1;");

    $realId = intval($res->fetch_assoc()['NumAffect']) + 1;

    return $realId;
}


$query = "INSERT INTO AFFECTER VALUES ([1], [2], [3], [4], [5], [6]);";
var_dump($query);
var_dump($_POST);
$id = $_POST['formNumAffect'];

if ($id == -1) {
    $id = GetNewId();
}

ExecPreparedQuery($query,
                    $id,
                    $_POST['formNumEmp'], 
                    $_POST['formAncienLieu'], 
                    $_POST['formNouveauLieu'], 
                    $_POST['formDateAffect'], 
                    $_POST['formPriseService']);
?>