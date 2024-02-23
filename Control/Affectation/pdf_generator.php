<?php 
include_once("../../Models/queries.php");

class AffectPDFGen
{
    public static function TryGeneratePDF()
    {
        $dataReceived = XMLHttpRequest::DecodeJson();
        
        if (!key_exists("id", $dataReceived)) {
            echo "uh";
        }
        else {
            echo "ih";
        }
    }
}

AffectPDFGen::TryGeneratePDF();
?>