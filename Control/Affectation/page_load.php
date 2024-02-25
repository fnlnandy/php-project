<?php 
include_once("../Models/table_helpers.php");

class AffectationPageLoadConditions
{
    /**
     * Verifies if NumAffect is in $subject
     */
    public static function IsNumAffectInResult(array $toCheck, mysqli_result $subject): bool
    {
        if (!key_exists('NumAffect', $toCheck))
            return false;

        $subject->data_seek(0); // Because we're fetching, we need the cursor at the beginning
        
        while ($row = $subject->fetch_assoc()) {
            if ($row['NumAffect'] == $toCheck['NumAffect'])
                return true;
        }

        return false;
    }
    
    /**
     * Gets the affectations between two dates, whether it is
     * DateAffect or DatePriseService
     */
    public static function GetAffectationsBetweenTwoDates($dateStart, $dateEnd): bool|mysqli_result
    {
        $queryToExec = "SELECT * FROM AFFECTER WHERE DateAffect >= '[1]' AND DateAffect <= '[2]';";
        $result = SQLQuery::ExecPreparedQuery($queryToExec, $dateStart, $dateEnd);

        return $result;
    }

    /**
     * Populates the affectation list table depending on the previous
     * conditions results
     */
    public static function PopulateAffectationList()
    {
        $dataReceived = $_GET;
        $limitingDatesPresent = !is_null($dataReceived) && key_exists("dateStart", $dataReceived) && key_exists("dateEnd", $dataReceived) && $dataReceived["dateStart"] != "" && $dataReceived["dateEnd"] != "";
        $queryToExec = "SELECT * FROM AFFECTER ORDER BY LENGTH(NumAffect) ASC, NumAffect ASC;";
        $result = SQLQuery::ExecQuery($queryToExec);

        while ($row = $result->fetch_assoc()) {
            if ($limitingDatesPresent) {
                $dateStart = $dataReceived["dateStart"];
                $dateEnd = $dataReceived["dateEnd"];
                $betweenDates = AffectationPageLoadConditions::GetAffectationsBetweenTwoDates($dateStart, $dateEnd);
                
                // If it;s not part of the limited results i.e. not in the range, we skip
                if (!AffectationPageLoadConditions::IsNumAffectInResult($row, $betweenDates))
                    continue;
            }
            $counter = $row["NumAffect"];
            $secondQuery = SQLQuery::ExecPreparedQuery("SELECT Nom, Prenom FROM EMPLOYE WHERE NumEmp = '[1]'", $row["NumEmp"]);

            if (!$secondQuery || is_null($secondQuery))
                continue;

            $workerRow = $secondQuery->fetch_assoc();

            echo "<tr class=\"affectationRow\" onclick=\"UpdateDataTracker(".strval($counter).", true)\">";

            // For each column in the associative array i.e. for each field in a row in the table, we
            // add a new <td>

            if (is_null($workerRow) || !key_exists("Nom", $workerRow) || !key_exists("Prenom", $workerRow))
                continue;

            echo "<td>".$row["NumAffect"]."</td>";
            echo "<td>".$workerRow["Nom"]." ".$workerRow["Prenom"]."</td>";
            echo "<td>".$row["AncienLieu"]."</td>";
            echo "<td>".$row["NouveauLieu"]."</td>";
            echo "<td>".$row["DateAffect"]."</td>";
            echo "<td>".$row["DatePriseService"]."</td>";

            foreach ($row as $column) {
                echo "<td>".$column."</td>";
            }

            echo "</tr>";
        }
    }
}
?>