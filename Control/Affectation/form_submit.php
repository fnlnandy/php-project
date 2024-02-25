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
     * If needed, i.e. when adding a new entry, the user
     * isn't expected to specify the next affectation ID,
     * and adding AUTO INCREMENT can't be done since NumAffect
     * is a VACHAR, hence we have to generate a new ID each time
     */
    public static function GenerateNewID() : int
    {
        $res    = SQLQuery::ExecQuery("SELECT * FROM AFFECTER ORDER BY LENGTH(NumAffect) DESC, NumAffect DESC LIMIT 1;"); // Last ID in the table
        $realId = intval($res->fetch_assoc()['NumAffect']) + 1;                      // New ID is thus the last + 1

        return $realId;
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
            $id = Affectation::GenerateNewID();            // We generate a new ID since that field cannot be NULL
            $query = "INSERT INTO AFFECTER VALUES ('[1]', '[2]', '[3]', '[4]', '[5]', '[6]');";
        }
        else {
            $query = "UPDATE AFFECTER SET NumEmp='[2]', AncienLieu='[3]', NouveauLieu='[4]', DateAffect='[5]', DatePriseService='[6]' WHERE NumAffect='[1]';";
        }

        $dateAffect = new DateTime($receivedData['dateAffect']);
        $datePrServ = new DateTime($receivedData['datePriseService']);

        SQLQuery::ExecPreparedQuery($query,                 // Executes a prepared query, either
                            $id,                            // an INSERT or an UPDATE,
                            $receivedData['numEmp'],        // Parameters are already in the correct
                            $receivedData['ancienLieu'],    // order.
                            $receivedData['nouveauLieu'], 
                            $dateAffect->format("Y-m-d"), 
                            $datePrServ->format("Y-m-d"));
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
        $row = $result->fetch_assoc();
        $dest = $row["Mail"];
        $subject = "Notification d'affectation du ".strval($dateAffect);
        $message = "Bonjour, ".$row["Nom"]." ".$row["Prenom"].", nous vous informons que vous serez affecté à ".$location.", vous prendrez service à partir du ".$datePrServ.".";

        $mail = new PHPMailer(true);

        try {
            // Mail sending prerequisites
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = "definitelynotandy01@gmail.com";
            $mail->Password = "gkwtdvvgwxinusqx";
            $mail->SMTPSecure = "ssl";
            $mail->Port = 465;

            // Sender and recipient
            $mail->setFrom("definitelynotandy01@gmail.com");
            $mail->addAddress($dest);

            // Mail content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;

            DebugUtil::LogIntoFile(__FILE__, __LINE__, $dest);
            $mail->send();
            DebugUtil::LogIntoFile(__FILE__, __LINE__, "Message sent");
        } catch (Exception $e) {
            DebugUtil::LogIntoFile(__FILE__, __LINE__, "Message could not be sent, error: { $mail->ErrorInfo }");
        }
    }
}

/**
 * This file's main function
 */
Affectation::InsertOrReplaceEntry();
?>