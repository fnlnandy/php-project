<?php 
include_once("../Models/queries.php");

class AffectationList {
    public static function PrintAffectations()
    {
        if (!key_exists('workerId', $_GET) || intval($_GET['workerId']) <= 0)
            return;

        $queryToExec = "SELECT NumAffect, AncienLieu, NouveauLieu, DateAffect, DatePriseService FROM EMPLOYE WHERE NumEmp = '[1]';";
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