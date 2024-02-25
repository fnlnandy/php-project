<?php 
include_once("../Models/queries.php");

/**
 * Container with functions regarding
 * the loading of the affectation list of an
 * employee
 */
class AffectationList {
    /**
     * Prints the list where the form is supposed to be at, I guess
     */
    public static function PrintAffectations()
    {
        if (!key_exists('workerId', $_GET) || intval($_GET['workerId']) <= 0)
            return;

        $queryToExec = "SELECT NumAffect, AncienLieu, NouveauLieu, DateAffect, DatePriseService FROM AFFECTER WHERE NumEmp = '[1]';";
        $result = SQLQuery::ExecPreparedQuery($queryToExec, $_GET['workerId']);

        while ($row = $result->fetch_assoc()) {
            echo "<tr class=\"workerAffectListRow\">";

            $secondQuery = "SELECT Design, Province FROM LIEU WHERE IDLieu = '[1]';";

            $oldLocRes = SQLQuery::ExecPreparedQuery($secondQuery, $row['AncienLieu']);
            $newLocRes = SQLQuery::ExecPreparedQuery($secondQuery, $row['NouveauLieu']);

            if (!$oldLocRes || !$newLocRes || is_null($oldLocRes) || is_null($newLocRes))
                continue;

            $oldLocData = $oldLocRes->fetch_assoc();
            $newLocData = $newLocRes->fetch_assoc();

            if (is_null($oldLocData) || is_null($newLocData) || !key_exists('Design', $oldLocData)
            || !key_exists('Design', $newLocData) || !key_exists('Province', $oldLocData) ||
            !key_exists('Province', $newLocData))
                continue;

            echo "<td>{$row['NumAffect']}</td>";
            echo "<td>{$oldLocData['Design']} ({$oldLocData['Province']})</td>";
            echo "<td>{$newLocData['Design']} ({$newLocData['Province']})</td>";
            echo "<td>{$row['DateAffect']}</td>";
            echo "<td>{$row['DatePriseService']}</td>";

            echo "</tr>";
        }
    }
}

AffectationList::PrintAffectations();
?>