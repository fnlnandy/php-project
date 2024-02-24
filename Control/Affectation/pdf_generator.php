<?php 

include_once("../../Models/queries.php");
include_once("../../Dependencies/fpdf/fpdf.php");

/**
 * Affectation PDF Generator container
 */
class AffectPDFGen {
    /**
     * Tries to generate a PDF File, creates a 'PDFs'
     * directory and the filename depends on the current Affectation ID
     */
    public static function TryGeneratePDFFile()
    {
        $dataReceived = XMLHttpRequest::DecodeJson();

        // Checking if there's any ID, i.e. if the data received is valid
        if (!key_exists("id", $dataReceived) || intval($dataReceived["id"]) <= 0) {
            return;
        } else {
            $id = $dataReceived["id"];
            $result = SQLQuery::ExecPreparedQuery("SELECT * FROM AFFECTER WHERE NumAffect = '[1]';", $id);
            
            if (!$result || is_null($result)) {
                return;
            }
            
            // Affectation data loading
            $row = $result->fetch_assoc();
            $employee = SQLQuery::ExecPreparedQuery("SELECT * FROM EMPLOYE WHERE NumEmp = '[1]';", $row['NumEmp']);
            
            if (!$employee || is_null($employee)) {
                return;
            }

            // Employee data loading
            $employeeData = $employee->fetch_assoc();
            $location = SQLQuery::ExecPreparedQuery("SELECT * FROM LIEU WHERE IDLieu = '[1]';", $row['AncienLieu']);

            if (!$location || is_null($location)) {
                return;
            }

            // Old location data loading
            $locationData = $location->fetch_assoc();

            $attestDate = $row['DateAffect'];
            $name = $employeeData['Nom'];
            $firstName = $employeeData['Prenom'];
            $civility = $employeeData['Civilite'];
            $position = $employeeData['Poste'];
            $oldLoc = $locationData['Design'];
            
            $location = SQLQuery::ExecPreparedQuery("SELECT * FROM LIEU WHERE IDLieu = '[1]';", $row['NouveauLieu']);

            if (!$location || is_null($location)) {
                return;
            }

            // New location data loading
            $locationData = $location->fetch_assoc();

            $newLoc = $locationData['Design'];
            $priseServ = $row['DatePriseService'];

            $pdfTitle = "Arrêté N {$id} du {$attestDate}";
            $pdfContent = array(
                "{$civility} {$name} {$firstName}, qui occupe le poste : {$position} à {$oldLoc},",
                "est affecté(e) à {$newLoc} pour compter de la date de prise de service {$priseServ}.",
                "",
                "Le présent communiqué sera enregistré et communiqué partout où besoin sera."
            );
            
            $attest = new FPDF();
            $attest->AddPage();
            $attest->SetFont('Arial', 'B', 16);
            // Converts 'accents' and the likes to ISO-8859-1 so it's not messed up in the file
            $attest->Cell(0, 10, mb_convert_encoding($pdfTitle, 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

            $attest->SetFont('Arial', '', 14);

            // Writing each line in $pdfContent as it doesn't automatically
            // guess we're overflowing the current line
            foreach ($pdfContent as $line) {
                $attest->Cell(0, 10, mb_convert_encoding($line, 'ISO-8859-1', 'UTF-8'), 0, 1);
            }

            // Creates the PDF directory if it doesn't exist already
            mkdir("../../PDFs");
            // Output file to write to
            $attest->Output("../../PDFs/arrete_{$id}.pdf", 'F', true);
        }
    }
}

/**
 * This file's main function
 */
AffectPDFGen::TryGeneratePDFFile();
?>