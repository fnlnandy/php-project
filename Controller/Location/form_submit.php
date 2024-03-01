<?php
include_once("../../Models/queries.php");

/**
 * Container with functions called on submit of
 * a valid location form
 */
class Location {
    /**
     * If needed, i.e. when adding a new entry, the user
     * isn't expected to specify the next location ID,
     * and adding AUTO INCREMENT can't be done since IDLieu
     * is a VACHAR, hence we have to generate a new ID each time
     */
    public static function GenerateNewID()
    {
        $lastLocResult = SQLQuery::ExecQuery("SELECT * FROM LIEU ORDER BY LENGTH(IDLieu) DESC, IDLieu DESC LIMIT 1;");
        $newIDLoc      = 1;
        $lastLocRow    = SQLQuery::ProcessResultAsAssocArray($lastLocResult, 'IDLieu');

        if (!is_null($lastLocRow))
            $newIDLoc = intval($lastLocRow['IDLieu']) + 1;

        return $newIDLoc;
    }

    /**
     * Depends on the data sent from handler.js, this
     * function inserts or updates an entry
     */
    public static function InsertOrReplaceEntry()
    {
        $queryToExec  = "";
        $receivedData = XMLHttpRequest::DecodeJson();

        if (SQLQuery::AreElementsEmpty($receivedData['Design'], $receivedData['Province']))
            return;
        if (!SQLQuery::DoKeysExistInArray($receivedData, 'IDLieu', 'editMode'))
            return;

        $possibleId   = intval($receivedData['IDLieu']);
        $isEditMode   = (intval($receivedData['editMode']) != 0);

        if ($isEditMode == false || $possibleId <= 0) {
            $possibleId  = Location::GenerateNewID();
            $queryToExec = "INSERT INTO LIEU VALUES('[1]', '[2]', '[3]');";
        }
        else {
            $queryToExec = "UPDATE LIEU SET Design='[2]', Province='[3]' WHERE IDLieu='[1]';";
        }
        
        SQLQuery::ExecPreparedQuery($queryToExec, $possibleId, $receivedData['Design'], $receivedData['Province']);
    }
}

/**
 * This file's main function
 */
Location::InsertOrReplaceEntry();
HTML::ForceRefresh();
?>