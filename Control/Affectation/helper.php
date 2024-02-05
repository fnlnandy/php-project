<?php
include_once("../Models/Database/queries.php");

function PopulateAffectationList()
{
    $result = ExecQuery("SELECT * FROM AFFECTER;");

    while($resultRow = $result->fetch_row()) {
        echo "<tr>";  // Creating a new row each time on the HTML page

        foreach ($resultRow as $column) {
            echo "<td>".$column."</td>";
        }

        echo "</tr>";
    }
}
?>