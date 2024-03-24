<?php 

include_once("../../Models/queries.php");
include_once("../../Models/debug_util.php");
include_once("../../Dependencies/fpdf/fpdf.php");

/**
 * Affectation PDF Generator container
 */
class AffectPDFGen {
    /**
     * Reformate the date object from the database into the expected
     * format for the attestation
     */
    private static function ReformateDate(string $date): string
    {
        $dateTimeObject = new DateTime($date);
        $formatted = $dateTimeObject->format('d/m/Y');

        DebugUtil::Assert(($date != ""), "\$date is empty.");
        
        return $formatted;
    }
    /**
     * Helper function to get both the old location and the new location's
     * database entries from the current affectation's data
     */
    private static function GetLocationsData(array|null $affectationData): array|null
    {
        $query = "SELECT * FROM LIEU WHERE IDLieu = '[1]';";

        if (!SQLQuery::DoKeysExistInArray($affectationData, 'AncienLieu', 'NouveauLieu'))
            return null;

        $oldLocResult = SQLQuery::ExecPreparedQuery($query, $affectationData['AncienLieu']);
        $newLocResult = SQLQuery::ExecPreparedQuery($query, $affectationData['NouveauLieu']);

        $oldLocRow = SQLQuery::ProcessResultAsAssocArray($oldLocResult, 'Design', 'Province');
        $newLocRow = SQLQuery::ProcessResultAsAssocArray($newLocResult, 'Design', 'Province');

        return array( 
            "oldLoc" => $oldLocRow,
            "newLoc" => $newLocRow,
            );
    }

    /**
     * Helper function to get the database entry of the current employee
     * that's getting affected
     */
    private static function GetEmployeeData(array|null $affectationData): array|null
    {
        $query = "SELECT * FROM EMPLOYE WHERE NumEmp = '[1]';";

        if (!SQLQuery::DoKeysExistInArray($affectationData, 'NumEmp'))
            return null;

        $result = SQLQuery::ExecPreparedQuery($query, $affectationData['NumEmp']);
        $employeeRow = SQLQuery::ProcessResultAsAssocArray($result, 'Nom', 'Prenom', 'Civilite', 'Poste');
        return $employeeRow;
    }

    /**
     * Helper function to get the current affectation's database
     * entry from the id received from JavaScript
     */
    private static function GetAffectationData(string $numAffect): array|null
    {
        $query = "SELECT * FROM AFFECTER WHERE NumAffect = '[1]';";
        $result = SQLQuery::ExecPreparedQuery($query, $numAffect);
        $affectationRow = SQLQuery::ProcessResultAsAssocArray($result, 'NumAffect', 'NumEmp', 'AncienLieu', 'NouveauLieu', 'DateAffect', 'DatePriseService');
        return $affectationRow;
    }

    /**
     * Tries to generate a PDF File, creates a 'PDFs'
     * directory and the filename depends on the current Affectation ID
     */
    public static function TryGeneratePDFFile(): void
    {
        $dataReceived = XMLHttpRequest::DecodeJson();

        // Checking if there's any ID, i.e. if the data received is valid
        if (!SQLQuery::DoKeysExistInArray($dataReceived, "id", "realPath") || intval($dataReceived["id"]) <= 0) {
            DebugUtil::LogIntoFile(__FILE__, __LINE__, "Keys don't exist");
            return;
        }

        // Data from the database
        $numAffect    = $dataReceived["id"];
        $affectRow    = AffectPDFGen::GetAffectationData($numAffect);
        $employeeRow  = AffectPDFGen::GetEmployeeData($affectRow);
        $locationsRow = AffectPDFGen::GetLocationsData($affectRow);

        $attestationDate   = AffectPDFGen::ReformateDate($affectRow['DateAffect']);
        $affectPSDate      = AffectPDFGen::ReformateDate($affectRow['DatePriseService']);
        $employeeName      = $employeeRow['Nom'];
        $employeeFirstName = $employeeRow['Prenom'];
        $employeeCivility  = $employeeRow['Civilite'];
        $employeeWork      = $employeeRow['Poste'];
        $oldLocDesign      = $locationsRow['oldLoc']['Design'];
        $oldLocProvince    = $locationsRow['oldLoc']['Province'];
        $newLocDesign      = $locationsRow['newLoc']['Design'];
        $newLocProvince    = $locationsRow['newLoc']['Province'];

        try {
            $pdfTitle       = "Arrêté N {$numAffect} du {$attestationDate}";
            $pdfContent = "{$employeeCivility} {$employeeName} {$employeeFirstName}, qui occupe le poste : {$employeeWork}".
                        " à {$oldLocDesign} ({$oldLocProvince}), est affecté(e) à {$newLocDesign} ({$newLocProvince})".
                        " pour compter de la date de prise de service : {$affectPSDate}.".
                        "\n\n".
                        "Le présent communiqué sera enregistré et communiqué partout où besoin sera.";
            
            $attestationPDFFile = new FPDF();
            $attestationPDFFile->AddPage();                 // So we're able to start writing
            $attestationPDFFile->SetFont('Arial', 'B', 16);
            $attestationPDFFile->Cell(0, 10, mb_convert_encoding($pdfTitle, 'ISO-8859-1', 'UTF-8'), 0, 1, 'C'); // Convert accents etc...
            $attestationPDFFile->SetFont('Arial', '', 14);

            $attestationPDFFile->MultiCell(0, 10, mb_convert_encoding($pdfContent, 'ISO-8859-1', 'UTF-8'), 0); // Writing line by line as it doesn't
                                                                                                    // automatically check for text overflows

            $attestationPDFFile->Output("../../PDFs/".$dataReceived['realPath'], 'F', true); // Output file
        } catch (Exception $e) {
            echo $e;
        }
    }
}

/**
 * This file's main function
 */
AffectPDFGen::TryGeneratePDFFile();
?>