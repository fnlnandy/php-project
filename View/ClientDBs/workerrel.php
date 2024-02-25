<table class="clientDB" id="workerNameFirstNameMatch">
    <?php 
        include_once("../Models/queries.php");

        $query = SQLQuery::ExecQuery("SELECT NumEmp, Nom, Prenom FROM EMPLOYE;");

        if (is_null($query))
            return;

        while ($row = $query->fetch_assoc()) {
            echo "<tr>";

            echo "<td>".$row["NumEmp"]."</td>";
            echo "<td>".$row["Nom"]." ".$row["Prenom"]."</td>";

            echo "</tr>";
        }
    ?>
</table>