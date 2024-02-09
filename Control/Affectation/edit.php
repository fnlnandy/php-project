<?php 
include_once("../../Models/Database/queries.php");

function GetData()
{
    $receivedData = json_decode(file_get_contents("php://input"), true);

    if (!array_key_exists('id', $receivedData))
        return 0;

    $query = "SELECT * FROM AFFECTER WHERE NumAffect = [1];";
    $result = ExecPreparedQuery($query, $receivedData['id']);

    $currRow = $result->fetch_assoc();
    return $currRow;
}

echo GetData();
?>