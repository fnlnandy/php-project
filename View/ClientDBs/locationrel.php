<table class="clientDB" id="locationIdDesignMatch">
    <?php 
        include_once("../Models/queries.php");

        $query = SQLQuery::ExecQuery("SELECT IDLieu, Design, Province FROM LIEU;");

        if (is_null($query))
            return;

        while ($row = $query->fetch_assoc()) {
            echo "<tr>";

            echo "<td>".$row['IDLieu']."</td>";
            echo "<td> {$row['Design']} ({$row['Province']})</td>";

            echo "</tr>";
        }
    ?>
</table>