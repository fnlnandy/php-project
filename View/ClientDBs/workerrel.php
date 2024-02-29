<!-- DATA STORING TABLE TO ACCESS IN JS -->
<table class="clent-side-database" id="worker-id-name-relation">
    <?php 
        include_once("../Models/queries.php");

        $query = SQLQuery::ExecQuery("SELECT NumEmp, Nom, Prenom FROM EMPLOYE WHERE Lieu IN (SELECT IDLieu FROM LIEU) ORDER BY LENGTH(NumEmp) ASC, NumEmp ASC;");

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