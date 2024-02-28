<?php

include_once("../../Models/queries.php");
include_once("../../Models/debug_util.php");
include_once("../../Dependencies/PHPMailer/PHPMailer.php");
include_once("../../Dependencies/PHPMailer/SMTP.php");
include_once("../../Dependencies/PHPMailer/Exception.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * Container for functions executed
 * on submit of a valid Affectation form
 */
class Affectation {
    /**
     * Replaces the location of an employee to the previous one
     */
    public static function RewindEmployeeLoc($numEmp, $oldLoc, $newLoc)
    {
        $query = "UPDATE EMPLOYE SET Lieu = '[1]' WHERE NumEmp = '[2]' AND Lieu = '[3]';";
        SQLQuery::ExecPreparedQuery($query, $oldLoc, $numEmp, $newLoc);
    }

    /**
     * Checks if an affectation is the lastest one made on the employee,
     * if it isn't then it would be an expired one
     */
    public static function IsAffectationLatestForEmployee($numAffect, $numEmp)
    {
        $query  = "SELECT * FROM AFFECTER WHERE NumEmp = '[1]' 
                   ORDER BY LENGTH(NumAffect) DESC, NumAffect DESC LIMIT 1;";
        $result = SQLQuery::ExecPreparedQuery($query, $numEmp);

        if (!SQLQuery::IsResultValid($result))                            // Check if not null and not false
            return false;

        $lastAffectRow = $result->fetch_assoc();

        if (!SQLQuery::DoKeysExistInArray($lastAffectRow, 'NumAffect'))    // Check if the needed key is present
            return false;

        return (intval($lastAffectRow['NumAffect']) == intval($numAffect)); // Returns if the given affectation is in fact the latest
    }

    /**
     * Fixes the current employee's location depending on the
     * affectation
     */
    public static function FixCurrentEmployeeLoc($numAffect, bool $rewind = true)
    {
        // Check ID validity
        if (intval($numAffect) <= 0) {
            DebugUtil::LogIntoFile(__FILE__, __LINE__, "Invalid ID.".var_export($numAffect));
            return;
        }

        $query  = "SELECT * FROM AFFECTER WHERE NumAffect = '[1]';";
        $result = SQLQuery::ExecPreparedQuery($query, $numAffect);

        // Check if result is null or false
        if (!SQLQuery::IsResultValid($result)) {
            DebugUtil::LogIntoFile(__FILE__, __LINE__, "Result is invalid.");
            return;
        }

        $currEmployeeRow = $result->fetch_assoc();
        
        // Check if the needed keys are present
        if (!SQLQuery::DoKeysExistInArray($currEmployeeRow, "NumEmp", "AncienLieu", "NouveauLieu")) {
            DebugUtil::LogIntoFile(__FILE__, __LINE__, "Keys don't exist in array.");
            return;
        }

        // Check if the current affectation is the latest to date
        if (Affectation::IsAffectationLatestForEmployee($numAffect, $currEmployeeRow['NumEmp'])) {
            if ($rewind) {
                Affectation::RewindEmployeeLoc($currEmployeeRow['NumEmp'], $currEmployeeRow['AncienLieu'], $currEmployeeRow['NouveauLieu']);
            }
            else { // Not rewinding because we're updating the new employee
                Affectation::RewindEmployeeLoc($currEmployeeRow['NumEmp'], $currEmployeeRow['NouveauLieu'], $currEmployeeRow['AncienLieu']);
            }
        }
    }

    /**
     * If needed, i.e. when adding a new entry, the user
     * isn't expected to specify the next affectation ID,
     * and adding AUTO INCREMENT can't be done since NumAffect
     * is a VACHAR, hence we have to generate a new ID each time
     */
    public static function GenerateNewID() : int
    {
        $lastAffectResult = SQLQuery::ExecQuery("SELECT * FROM AFFECTER ORDER BY LENGTH(NumAffect) DESC, NumAffect DESC LIMIT 1;"); // Last ID in the table
        $newNumAffect     = 1;
        $lastAffectRow    = $lastAffectResult->fetch_assoc();

        if (SQLQuery::DoKeysExistInArray($lastAffectRow, 'NumAffect'))
            $newNumAffect = intval($lastAffectRow['NumAffect']) + 1; // New ID is thus the last + 1

        return $newNumAffect;
    }

    /**
     * Depends on the data sent from handler.js, this
     * function inserts or updates an entry
     */
    public static function InsertOrReplaceEntry()
    {
        $query        = "";
        $receivedData = XMLHttpRequest::DecodeJson(); // We get the date sent via AJAX in JSON format
        $id           = intval($receivedData['numAffect']);                  // We get the ID, that will be checked if valid or not
        $editMode     = (intval($receivedData['editMode']) != 0);            // We also get the EditMode, in case the ID wasn't correctly made invalid for some reason

        if ($editMode == false || $id <= 0) { // ID is invalid if it is <= 0 because our first ID must be 1
            $id    = Affectation::GenerateNewID();            // We generate a new ID since that field cannot be NULL
            $query = "INSERT INTO AFFECTER VALUES ('[1]', '[2]', '[3]', '[4]', '[5]', '[6]');";
        }
        else {
            $query = "UPDATE AFFECTER SET NumEmp='[2]', AncienLieu='[3]', NouveauLieu='[4]', DateAffect='[5]', DatePriseService='[6]' WHERE NumAffect='[1]';";
        }

        $dateAffect = new DateTime($receivedData['dateAffect']);
        $datePrServ = new DateTime($receivedData['datePriseService']);

        Affectation::FixCurrentEmployeeLoc($id);
        SQLQuery::ExecPreparedQuery($query,                 // Executes a prepared query, either
                            $id,                            // an INSERT or an UPDATE,
                            $receivedData['numEmp'],        // Parameters are already in the correct
                            $receivedData['ancienLieu'],    // order.
                            $receivedData['nouveauLieu'], 
                            $dateAffect->format("Y-m-d"), 
                            $datePrServ->format("Y-m-d"));
        Affectation::FixCurrentEmployeeLoc($id, false);
        Affectation::SendEmailOnSubmit($receivedData['numEmp'], $receivedData['nouveauLieu'], $dateAffect->format("Y-m-d"), $datePrServ->format("Y-m-d"));
        header("Refresh:0");
    }

    /**
     * Sends an email to the affected worker about his affectation
     */
    public static function SendEmailOnSubmit($numEmp, $location, $dateAffect, $datePrServ)
    {
        // We first get the employee data
        $result = SQLQuery::ExecPreparedQuery("SELECT * FROM EMPLOYE WHERE NumEmp = '[1]';", $numEmp);
        
        if (!$result || is_null($result)) {
            die("Error in query.");
        }

        // Now we get his email, to send the mail too and all his basic personal infos
        $employeeRow    = $result->fetch_assoc();
        $recipientEmail = $employeeRow["Mail"];
        $mailSubject    = "Notification d'affectation du ".strval($dateAffect).".";
        $mailContent    = "Bonjour, {$employeeRow["Civilite"]} {$employeeRow["Nom"]} {$employeeRow["Prenom"]}, nous vous informons que vous serez affecté à ".$location.", à compter de la date de prise de service du ".$datePrServ.".";
        $mailerObject   = new PHPMailer(true);

        try {
            // Mail sending prerequisites
            $mailerObject->SMTPDebug  = SMTP::DEBUG_SERVER;
            $mailerObject->isSMTP(true); // Set the protocol
            $mailerObject->Host       = "smtp.gmail.com";
            $mailerObject->SMTPAuth   = true;
            $mailerObject->Username   = "definitelynotandy01@gmail.com";
            $mailerObject->Password   = "gkwtdvvgwxinusqx";
            $mailerObject->SMTPSecure = "ssl";
            $mailerObject->Port       = 465;

            // Sender and recipient
            $mailerObject->setFrom("definitelynotandy01@gmail.com");
            $mailerObject->addAddress($recipientEmail);

            // Mail content
            $mailerObject->isHTML(true);
            $mailerObject->Subject = $mailSubject;
            $mailerObject->Body    = $mailContent;

            $mailerObject->send();
            DebugUtil::LogIntoFile(__FILE__, __LINE__, "Message sent");
        } catch (Exception $e) {
            DebugUtil::LogIntoFile(__FILE__, __LINE__, "Message could not be sent, error: { $mailerObject->ErrorInfo }");
        }
    }
}

/**
 * This file's main function
 */
Affectation::InsertOrReplaceEntry();
?>