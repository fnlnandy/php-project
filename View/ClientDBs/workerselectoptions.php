<?php
    class WorkerSelectOptions {
        public static function PopulateSelectOptionIds() {
            $query = SQLQuery::ExecQuery("SELECT NumEmp FROM EMPLOYE WHERE Lieu IN (SELECT IDLieu FROM LIEU) ORDER BY LENGTH(NumEmp) ASC, NumEmp ASC;");

            if (!$query || is_null($query))
                return;

            while ($row = $query->fetch_assoc()) {
                if (is_null($row))
                    continue;

                var_dump($row);
                echo "<option>{$row['NumEmp']}</option>";
            }
        }
        public static function PopulateSelectOptionNames() {
            $query = SQLQuery::ExecQuery("SELECT Nom, Prenom FROM EMPLOYE WHERE Lieu IN (SELECT IDLieu FROM LIEU) ORDER BY LENGTH(NumEmp) ASC, NumEmp ASC;");

            if (!$query || is_null($query))
                return;

            while ($row = $query->fetch_assoc()) {
                if (is_null($row))
                    continue;

                var_dump($row);
                echo "<option>{$row['Nom']} {$row['Prenom']}</option>";
            }
        }
    }
?>