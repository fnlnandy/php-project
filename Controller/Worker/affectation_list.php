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
        if (!key_exists('worker-id', $_GET) || intval($_GET['worker-id']) <= 0)
            return;

        $queryToExec = "SELECT NumAffect, AncienLieu, NouveauLieu, DateAffect, DatePriseService FROM AFFECTER WHERE NumEmp = '[1]';";
        $result = SQLQuery::ExecPreparedQuery($queryToExec, $_GET['worker-id']);

        while ($row = $result->fetch_assoc()) {
            echo "<tr class=\"inner-table-row\">";

            $secondQuery = "SELECT Design, Province FROM LIEU WHERE IDLieu = '[1]';";

            $oldLocRes  = SQLQuery::ExecPreparedQuery($secondQuery, $row['AncienLieu']);
            $newLocRes  = SQLQuery::ExecPreparedQuery($secondQuery, $row['NouveauLieu']);
            $oldLocData = SQLQuery::ProcessResultAsAssocArray($oldLocRes, 'Design', 'Province');
            $newLocData = SQLQuery::ProcessResultAsAssocArray($newLocRes, 'Design', 'Province');

            if (is_null($oldLocData) || is_null($newLocData))
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