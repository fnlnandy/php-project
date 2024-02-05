<?php
include_once("../Models/Database/queries.php");

function PopulateAffectationList()
{
    $result = ExecQuery("SELECT * FROM AFFECTER;");
    $counter = 1;

    while($resultRow = $result->fetch_row()) {
        // When we enter edit mode, we should know exactly which Row we want to edit
        echo "<tr class=\"affectationRow\" onclick=\"UpdateDataTracker(".strval($counter).", true)\">";

        foreach ($resultRow as $column) {
            echo "<td>".$column."</td>";
        }

        echo "</tr>";
        $counter++;
    }
}
?>