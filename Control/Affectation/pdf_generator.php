<?php 
include_once("../../Models/queries.php");
// include_once("../../Models/debug_util.php");
include_once("../../Dependencies/fpdf/fpdf.php");

class AffectPDFGen {
    public static function TryGeneratePDFFile()
    {
        $dataReceived = XMLHttpRequest::DecodeJson();

        if (!key_exists("id", $dataReceived) || intval($dataReceived["id"]) <= 0) {
            return;
        } else {
            $id = $dataReceived["id"];
            $result = SQLQuery::ExecPreparedQuery("SELECT * FROM AFFECTER WHERE NumAffect = '[1]';", $id);
            
            if (!$result || is_null($result)) {
                return;
            }
            
            $row = $result->fetch_assoc();
            $employee = SQLQuery::ExecPreparedQuery("SELECT * FROM EMPLOYE WHERE NumEmp = '[1]';", $row['NumEmp']);
            
            if (!$employee || is_null($employee)) {
                return;
            }

            $employeeData = $employee->fetch_assoc();
            $location = SQLQuery::ExecPreparedQuery("SELECT * FROM LIEU WHERE IDLieu = '[1]';", $row['AncienLieu']);

            if (!$location || is_null($location)) {
                return;
            }

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
            $attest->Cell(0, 10, mb_convert_encoding($pdfTitle, 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

            $attest->SetFont('Arial', '', 14);

            foreach ($pdfContent as $line) {
                $attest->Cell(0, 10, mb_convert_encoding($line, 'ISO-8859-1', 'UTF-8'), 0, 1);
            }

            mkdir("../../PDFs");
            $attest->Output("../../PDFs/arrete_{$id}.pdf", 'F', true);
            echo "FINISHED\n";
        }
    }
}

AffectPDFGen::TryGeneratePDFFile();
?>