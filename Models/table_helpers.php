<?php 
include_once("queries.php");

/**
 * Basic container to avoid *eventual* naming
 * conflicts
 */
class TableHelper {
    /**
     * Helper function to remove a specific entry from a table, avoids repetition
     * between remove.php files across the source code
     */
    public static function RemoveEntryFromTable($idInPost, $tableName, $idInDatabase)
    {
        $receivedData = XMLHttpRequest::DecodeJson();
        $possibleId = intval($receivedData[$idInPost]);

        if ($possibleId <= 0)
            return;

        SQLQuery::ExecPreparedQuery("DELETE FROM ".$tableName." WHERE ".$idInDatabase." = [1];", $possibleId);
    }

        /**
     * 
     */
    public static function PopulateTableElementWithQueryResult($result, $idToGet, $className)
    {
        $counter = 0;

        while($resultRow = $result->fetch_assoc()) {
            // $counter keeps track of the current id to pass as parameter to UpdateDataTracker,
            // we cannot just increment $counter each time in case of affectation deletion
            $counter = $resultRow[$idToGet];

            // When we enter edit mode, we should know exactly which Row we want to edit,
            // hence why we always update gDataTracker
            echo "<tr class=\"".$className."\" onclick=\"UpdateDataTracker(".strval($counter).", true)\">";

            // For each column in the associative array i.e. for each field in a row in the table, we
            // add a new <td>
            foreach ($resultRow as $column) {
                echo "<td>".$column."</td>";
            }

            echo "</tr>";
        }
    }

    /**
     * Helper function to populate an HTML Table element,
     * makes other helper.php files irrelevant
     */
    public static function PopulateTableElementWithDatabseData($sourceTable, $idToGet, $className)
    {
        $result = SQLQuery::ExecQuery("SELECT * FROM ".$sourceTable." ORDER BY ".$idToGet." ASC;");
        TableHelper::PopulateTableElementWithQueryResult($result, $idToGet, $className);
    }
}
?>