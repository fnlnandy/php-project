<?php 
include_once("../Models/table_helpers.php");

class AffectationPageLoadConditions
{
    /**
     * Verifies if NumAffect is in $subject, intrinsically verifies if an entry
     * is a match of a condition
     */
    public static function IsNumAffectInResult(array $toCheck, mysqli_result $subject): bool
    {
        if (!SQLQuery::DoKeysExistInArray($toCheck, 'NumAffect'))
            return false;

        $subject->data_seek(0); // Due to fetching that might have happened to $subject, we go back to the beginning
        
        while ($row = $subject->fetch_assoc()) {
            if (isset($row['NumAffect']) && $row['NumAffect'] == $toCheck['NumAffect'])
                return true;
        }

        return false;
    }
    
    /**
     * Gets the affectations between two dates, whether it is
     * DateAffect or DatePriseService
     */
    public static function GetAffectationsBetweenTwoDates(string $dateStart, string $dateEnd, bool $isDateABased, bool $isDatePSBased): bool|mysqli_result
    {
        $conditionDA = "DateAffect >= '[1]' AND DateAffect <= '[2]'";             // Based on the DateAffect
        $conditionPS = "DatePriseService >= '[1]' AND DatePriseService <= '[2]'"; // Based on the DatePriseService
        $joiner = "OR";

        if (!$isDateABased && !$isDatePSBased) { // Both conditions can't be false at the same time
            $isDateABased = true;
            $joiner = "";                        // One of the condition is null, so no joiner
        }
        if (!$isDateABased) { 
            $conditionDA = "";
            $joiner = "";
        }
        if (!$isDatePSBased) {
            $conditionPS = "";
            $joiner = "";
        }

        DebugUtil::Assert(($dateStart != ""), "\$dateStart is empty.", __FUNCTION__);
        DebugUtil::Assert(($dateEnd != ""), "\$dateEnd is empty.", __FUNCTION__);
            
        $queryToExec = "SELECT * FROM AFFECTER WHERE {$conditionDA} {$joiner} {$conditionPS};";
        $result = SQLQuery::ExecPreparedQuery($queryToExec, $dateStart, $dateEnd);

        return $result;
    }

    /**
     * Populates the affectation list table depending on the previous
     * conditions results
     */
    public static function PopulateAffectationList(): void
    {
        $limitingDatesPresent = SQLQuery::DoKeysExistInArray($_GET, "search-bar-date-begin", "search-bar-date-end") && $_GET["search-bar-date-begin"] != "" && $_GET["search-bar-date-end"] != "";
        $isDateAffectBased    = SQLQuery::DoKeysExistInArray($_GET, "search-date-affect-based") && $_GET["search-date-affect-based"] != "";
        $isDatePSBased        = SQLQuery::DoKeysExistInArray($_GET, "search-date-ps-based") && $_GET["search-date-ps-based"] != "";
        $queryToExec          = "SELECT * FROM AFFECTER ORDER BY LENGTH(NumAffect) ASC, NumAffect ASC;";
        $result               = SQLQuery::ExecQuery($queryToExec);

        while ($row = $result->fetch_assoc()) {
            if ($limitingDatesPresent) {
                $dateStart    = $_GET["search-bar-date-begin"];
                $dateEnd      = $_GET["search-bar-date-end"];
                $betweenDates = AffectationPageLoadConditions::GetAffectationsBetweenTwoDates($dateStart, $dateEnd, $isDateAffectBased, $isDatePSBased);
                
                // If it's not part of the limited results i.e. not in the range, we skip
                if (!AffectationPageLoadConditions::IsNumAffectInResult($row, $betweenDates))
                    continue;
            }

            $affectCounter = $row["NumAffect"];
            $workerResult  = SQLQuery::ExecPreparedQuery("SELECT Nom, Prenom FROM EMPLOYE WHERE NumEmp = '[1]'", $row["NumEmp"]);
            $oldLocResult  = SQLQuery::ExecPreparedQuery("SELECT Design, Province FROM LIEU WHERE IDLieu = '[1]';", $row["AncienLieu"]);
            $newLocResult  = SQLQuery::ExecPreparedQuery("SELECT Design, Province FROM LIEU WHERE IDLieu = '[1]';", $row["NouveauLieu"]);

            $workerRow = SQLQuery::ProcessResultAsAssocArray($workerResult, "Nom", "Prenom");
            $oldLocRow = SQLQuery::ProcessResultAsAssocArray($oldLocResult, "Design", "Province");
            $newLocRow = SQLQuery::ProcessResultAsAssocArray($newLocResult, "Design", "Province");

            if (is_null($workerRow) || is_null($oldLocRow) || is_null($newLocRow))
                continue;

            // The current row, contains a call to the JavaScript function supposed to update
            // the data tracker for the Edit and Delete functions on the table
            echo "<tr class=\"affectation-table-row\" onclick=\"UpdateDataTracker(".strval($affectCounter).", true)\">";

            // The table's elements
            echo "<td>".$row["NumAffect"]."</td>";
            echo "<td>".$workerRow["Nom"]." ".$workerRow["Prenom"]."</td>";
            echo "<td> {$oldLocRow['Design']} ({$oldLocRow['Province']})</td>";
            echo "<td> {$newLocRow['Design']} ({$newLocRow['Province']})</td>";
            echo "<td>".$row["DateAffect"]."</td>";
            echo "<td>".$row["DatePriseService"]."</td>";

            // Closing the current row
            echo "</tr>";
        }
    }
}
?>