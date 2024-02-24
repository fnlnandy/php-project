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

            foreach($row as $column) {
                echo "<td>".$column."</td>";
            }

            echo "</tr>";
        }
    }
}

AffectationList::PrintAffectations();
?>