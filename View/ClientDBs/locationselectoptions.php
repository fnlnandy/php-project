<?php
    class LocationSelectOptions {
        public static function PopulateSelectOptionIds() {
            $query = SQLQuery::ExecQuery("SELECT IDLieu FROM LIEU ORDER BY LENGTH(IDLieu) ASC, IDLieu ASC;");

            if (!$query || is_null($query))
                return;

            while ($row = $query->fetch_assoc()) {
                if (is_null($row))
                    continue;

                var_dump($row);
                echo "<option>{$row['IDLieu']}</option>";
            }
        }
        public static function PopulateSelectOptionNames() {
            $query = SQLQuery::ExecQuery("SELECT Design, Province FROM LIEU ORDER BY LENGTH(IDLieu) ASC, IDLieu ASC;");

            if (!$query || is_null($query))
                return;

            while ($row = $query->fetch_assoc()) {
                if (is_null($row))
                    continue;

                var_dump($row);
                echo "<option>{$row['Design']} ({$row['Province']})</option>";
            }
        }
    }
?>