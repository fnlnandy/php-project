<?php

include_once("../../Models/queries.php");
include_once("../../Models/debug_util.php");
include_once("../../Dependencies/PHPMailer/PHPMailer.php");
include_once("../../Dependencies/PHPMailer/SMTP.php");
include_once("../../Dependencies/PHPMailer/Exception.php");
include_once("../tests.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * Container for functions executed
 * on submit of a valid Affectation form
 */
class Affectation {
    /**
     * Reformate the date object from the database into the expected
     * format for the attestation
     */
    private static function ReformateDate(string $date): string
    {
        Test::Test_Affectation_ReformateDate($date);
        $dateTimeObject = new DateTime($date);
        $formatted = $dateTimeObject->format('d/m/Y');
        
        return $formatted;
    }

    /**
     * Replaces the location of an employee to the previous one
     */
    public static function RewindEmployeeLoc(string $numEmp, string $oldLoc, string $newLoc): void
    {
        Test::Test_Affectation_RewindEmployee(array("oldLoc" => $oldLoc, "newLoc" => $newLoc, "numEmp" => $numEmp));
        $query = "UPDATE EMPLOYE SET Lieu = '[1]' WHERE NumEmp = '[2]' AND Lieu = '[3]';";

        SQLQuery::ExecPreparedQuery($query, $oldLoc, $numEmp, $newLoc);
    }

    /**
     * Checks if an affectation is the lastest one made on the employee,
     * if it isn't then it would be an expired one
     */
    public static function IsAffectationLatestForEmployee(string $numAffect, string $numEmp): bool
    {
        $query  = "SELECT * FROM AFFECTER WHERE NumEmp = '[1]' 
                   ORDER BY LENGTH(NumAffect) DESC, NumAffect DESC LIMIT 1;";
        $result = SQLQuery::ExecPreparedQuery($query, $numEmp);
        $lastAffectRow = SQLQuery::ProcessResultAsAssocArray($result, 'NumAffect');

        Test::Test_Affectation_IsLatest(array("row" => $lastAffectRow, "numAffect" => $numAffect));

        if (is_null($lastAffectRow))
            return false;

        return (intval($lastAffectRow['NumAffect']) == intval($numAffect)); // Returns if the given affectation is in fact the latest
    }

    /**
     * Fixes the current employee's location depending on the
     * affectation
     */
    public static function FixCurrentEmployeeLoc(string $numAffect, bool $rewind = true): void
    {
        // Check ID validity
        if (intval($numAffect) <= 0) {
            DebugUtil::LogIntoFile(__FILE__, __LINE__, "Invalid ID.".var_export($numAffect));
            return;
        }

        $query  = "SELECT * FROM AFFECTER WHERE NumAffect = '[1]';";
        $result = SQLQuery::ExecPreparedQuery($query, $numAffect);
        $currEmployeeRow = SQLQuery::ProcessResultAsAssocArray($result, 'NumEmp', 'AncienLieu', 'NouveauLieu');

        if (is_null($currEmployeeRow))
            return;

        Test::Test_Affectation_FixCurrEmployee(array("row" => $currEmployeeRow, "numAffect" => $numAffect));

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
        $lastAffectRow    = SQLQuery::ProcessResultAsAssocArray($lastAffectResult, 'NumAffect');

        if (!is_null($lastAffectRow))
            $newNumAffect = intval($lastAffectRow['NumAffect']) + 1; // New ID is thus the last + 1

        Test::Test_Affectation_GenerateNewId($newNumAffect);

        return $newNumAffect;
    }

    /**
     * Returns if the employee ID changed, if it
     * didn't, then we do not rewind the employee's
     * location
     */
    public static function IsNewEmployeeID(string $newEmpId, string $numAffect): bool
    {
        $query = "SELECT NumEmp FROM AFFECTER WHERE NumAffect = '[1]';";
        $result = SQLQuery::ExecPreparedQuery($query, $numAffect);
        $row = SQLQuery::ProcessResultAsAssocArray($result, 'NumEmp');

        if (is_null($row))
            return false;

        DebugUtil::Assert((intval($row['NumEmp']) > 0), "\$row['NumEmp'] is <= 0.");
        DebugUtil::Assert((intval($newEmpId) > 0), "\$newEmpId is <= 0.");
        Test::Test_Affectation_IsNewEmpID(array('row' => $row, 'id' => $newEmpId));

        return (intval($row['NumEmp']) != intval($newEmpId));
    }

    /**
     * Depends on the data sent from handler.js, this
     * function inserts or updates an entry
     */
    public static function InsertOrReplaceEntry()
    {
        $query        = "";
        $receivedData = XMLHttpRequest::DecodeJson(); // We get the date sent via AJAX in JSON format
        
        if (!SQLQuery::DoKeysExistInArray($receivedData, 'numAffect', 'editMode', 'notifyEmployee',
            'numEmp', 'ancienLieu', 'nouveauLieu', 'dateAffect', 'datePriseService'))
            return;
        if (SQLQuery::AreElementsEmpty($receivedData['numEmp'], $receivedData['ancienLieu'],
            $receivedData['nouveauLieu'], $receivedData['dateAffect'], $receivedData['datePriseService']))
            return;

        
        Test::Test_Affectation_InsertOrReplace($receivedData);

        $id           = intval($receivedData['numAffect']);                  // We get the ID, that will be checked if valid or not
        $editMode     = (intval($receivedData['editMode']) != 0);            // We also get the EditMode, in case the ID wasn't correctly made invalid for some reason
        $sendMail     = intval($receivedData["notifyEmployee"]);

        if ($editMode == false || $id <= 0) { // ID is invalid if it is <= 0 because our first ID must be 1
            $id    = Affectation::GenerateNewID();            // We generate a new ID since that field cannot be NULL
            $query = "INSERT INTO AFFECTER VALUES ('[1]', '[2]', '[3]', '[4]', '[5]', '[6]');";
        }
        else {
            $query = "UPDATE AFFECTER SET NumEmp='[2]', AncienLieu='[3]', NouveauLieu='[4]', DateAffect='[5]', DatePriseService='[6]' WHERE NumAffect='[1]';";
        }

        $dateAffect = new DateTime($receivedData['dateAffect']);
        $datePrServ = new DateTime($receivedData['datePriseService']);

        if (Affectation::IsNewEmployeeID($receivedData['numEmp'], $id))
            Affectation::FixCurrentEmployeeLoc($id);
        SQLQuery::ExecPreparedQuery($query,                 // Executes a prepared query, either
                            $id,                            // an INSERT or an UPDATE,
                            $receivedData['numEmp'],        // Parameters are already in the correct
                            $receivedData['ancienLieu'],    // order.
                            $receivedData['nouveauLieu'], 
                            $dateAffect->format("Y-m-d"), 
                            $datePrServ->format("Y-m-d"));
        Affectation::FixCurrentEmployeeLoc($id, false);
        
        if ($sendMail == 1)
            Affectation::SendEmailOnSubmit($receivedData['numEmp'], $receivedData['nouveauLieu'], $dateAffect->format("Y-m-d"), $datePrServ->format("Y-m-d"));
    }

    /**
     * Sends an email to the affected worker about his affectation
     */
    public static function SendEmailOnSubmit($numEmp, $location, $dateAffect, $datePrServ)
    {
        // We first get the employee data
        $result = SQLQuery::ExecPreparedQuery("SELECT * FROM EMPLOYE WHERE NumEmp = '[1]';", $numEmp);
        $employeeRow = SQLQuery::ProcessResultAsAssocArray($result);

        $result = SQLQuery::ExecPreparedQuery("SELECT * FROM LIEU WHERE IDLieu = '[1]';", $location);
        $locationRow = SQLQuery::ProcessResultAsAssocArray($result);

        if (is_null($employeeRow) || is_null($locationRow))
            return;

        $dateAffect = Affectation::ReformateDate($dateAffect);
        $datePrServ = Affectation::ReformateDate($datePrServ);

        $recipientEmail = $employeeRow["Mail"];
        $mailSubject    = "Notification d'affectation du ".strval($dateAffect).".";
        $mailContent = "<style>
                            * { color: white; }
                        </style>

                        <h1 style=\"font-size: 2em; color: white; text-decoration: underline;\">
                            Affectation du {$dateAffect}
                        </h1>
                        <p style=\"font-size: 1.5em; color: white;\">
                            Bonjour, <b>{$employeeRow["Civilite"]} {$employeeRow["Nom"]} {$employeeRow["Prenom"]}</b>.<br><br>
                            Nous vous informons que vous serez affecté(e) à <b>{$locationRow['Design']} ({$locationRow['Province']})</b>.<br><br>
                            Vous prendrez service à partir du <b>{$datePrServ}</b>.<br><br>
                        </p>
                        <table border=\"1\" style=\"text-align: center;\">
                            <tr>
                                <th>
                                    Nom et prénoms(s)
                                </th>
                                <th>
                                    Lieu d'affectation
                                </th>
                                <th>
                                    Date de prise de service
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <b>{$employeeRow["Nom"]} {$employeeRow["Prenom"]}</b>
                                </td>
                                <td>
                                    {$locationRow['Design']} ({$locationRow['Province']})
                                </td>
                                <td>
                                    {$datePrServ}
                                </td>
                            </tr>
                        </table>";
        $mailerObject   = new PHPMailer(true);

        try {
            // Mail sending prerequisites
            $mailerObject->SMTPDebug  = SMTP::DEBUG_SERVER;
            $mailerObject->isSMTP(true); // Set the protocol
            $mailerObject->Host       = "smtp.gmail.com";
            $mailerObject->SMTPAuth   = true;
            $mailerObject->Username   = "gestiondbproj2838@gmail.com";
            $mailerObject->Password   = "zhszwdhzivcgvqpk";
            $mailerObject->SMTPSecure = "ssl";
            $mailerObject->Port       = 465;

            // Sender and recipient
            $mailerObject->setFrom("gestiondbproj2838@gmail.com");
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
HTML::ForceRefresh();
?>