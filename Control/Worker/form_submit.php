<?php
include_once("../../Models/queries.php");

/**
 * Containers with functions called on submit
 * of a valid worker form
 */
class Worker {
    /**
     * If needed, i.e. when adding a new entry, the user
     * isn't expected to specify the next location ID,
     * and adding AUTO INCREMENT can't be done since IDLieu
     * is a VACHAR, hence we have to generate a new ID each time
     */
    public static function GenerateNewID()
    {
        $lastIdQuery = SQLQuery::ExecQuery("SELECT * FROM EMPLOYE ORDER BY NumEmp DESC LIMIT 1;");
        $realId      = intval($lastIdQuery->fetch_assoc()['NumEmp']) + 1;

        return $realId;
    }

    /**
     * Depends on the data sent from handler.js, this
     * function inserts or updates an entry
     */
    public static function InsertOrReplaceEntry()
    {
        $queryToExec = "";
        $receivedData = XMLHttpRequest::DecodeJson();
        $possibleId = intval($receivedData['NumEmp']);
        $isEditMode = (intval($receivedData['editMode']) != 0);

        if ($isEditMode == false || $possibleId <= 0) {
            $possibleId = Worker::GenerateNewID();
            $queryToExec = "INSERT INTO EMPLOYE VALUES('[1]', '[2]', '[3]', '[4]', '[5]', '[6]', '[7]');";
        }
        else {
            $queryToExec = "UPDATE EMPLOYE SET Civilite='[2]', Nom='[3]', Prenom='[4]', Mail='[5]', Poste='[6]', Lieu='[7]' WHERE NumEmp='[1]';";
        }

        SQLQuery::ExecPreparedQuery($queryToExec, $possibleId, $receivedData['Civilite'], $receivedData['Nom'],
                        $receivedData['Prenom'], $receivedData['Mail'], $receivedData['Poste'], $receivedData['Lieu']);
        header("Refresh:0");
    }
}

/**
 * This file's main function
 */
Worker::InsertOrReplaceEntry();
?>