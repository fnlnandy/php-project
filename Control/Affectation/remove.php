<?php
include_once("../../Models/queries.php");
include_once("../../Models/table_helpers.php");
include_once("../../Models/debug_util.php");

class RemoveAffectation
{
    /**
     * 
     */
    public static function IsRemovedAffectationLatest($id): bool
    {
        $lastAffectResult = SQLQuery::ExecPreparedQuery("SELECT * FROM AFFECTER WHERE NumEmp = (SELECT NumEmp FROM AFFECTER WHERE NumAffect = '[1]') ORDER BY LENGTH(NumAffect) DESC, NumAffect DESC LIMIT 1;", $id);

        if (!SQLQuery::IsResultValid($lastAffectResult)) {
            DebugUtil::LogIntoFile(__FILE__, __LINE__, "Result is invalid.");
            return false;
        }

        $row = $lastAffectResult->fetch_array();

        if (!SQLQuery::DoKeysExistInArray($row, 'NumAffect')) {
            DebugUtil::LogIntoFile(__FILE__, __LINE__, "Keys don't exist in array.");
            return false;
        }
        

        // Return the comparison between both ids, i.e. between the given parameter and the
        // last entry
        DebugUtil::LogIntoFile(__FILE__, __LINE__, "\$row['NumAffect'] = {$row['NumAffect']}");
        DebugUtil::LogIntoFile(__FILE__, __LINE__, "\$id = {$id}");
        return (intval($row['NumAffect']) == intval($id));
    }

    /**
     * 
     */
    public static function TryToRevertEmployeeLocation($data)
    {
        $affectId = $data['id'];
        $query = "SELECT NumEmp, AncienLieu, NouveauLieu FROM AFFECTER WHERE NumAffect = '[1]';";

        if (!RemoveAffectation::IsRemovedAffectationLatest($affectId)) {
            DebugUtil::LogIntoFile(__FILE__, __LINE__, "Removed affectation isn't the latest: {$affectId}.");
            return;
        }

        $result = SQLQuery::ExecPreparedQuery($query, $affectId);

        if (!SQLQuery::IsResultValid($result)) {
            DebugUtil::LogIntoFile(__FILE__, __LINE__, "Query result is invalid.");
            return;
        }

        $row = $result->fetch_assoc();
        if (!SQLQuery::DoKeysExistInArray($row, 'NumEmp', 'AncienLieu', 'NouveauLieu')) {
            DebugUtil::LogIntoFile(__FILE__, __LINE__, "Keys don't exist in array.");
            return;
        }

        // As in form_submit.php, only update if it was actually a valid affectation i.e.
        // the new location (since we're reverting) is the current location
        $query = "UPDATE EMPLOYE SET Lieu = '[1]' WHERE NumEmp = '[2]' AND Lieu = '[3]';";
        $result = SQLQuery::ExecPreparedQuery($query, $row['AncienLieu'], $row['NumEmp'], $row['NouveauLieu']);
        DebugUtil::LogIntoFile(__FILE__, __LINE__, "{$row['NouveauLieu']} {$row['NumEmp']} {$row['AncienLieu']}");
    }

    /**
     * 
     */
    public static function TryToRemoveAffectation()
    {
        $affectationData = XMLHttpRequest::DecodeJson();
        $possibleId = 0;

        if (!SQLQuery::DoKeysExistInArray($affectationData, 'id')) {
            DebugUtil::LogIntoFile(__FILE__, __LINE__, "Keys don't exist in array.");
            return;
        }

        $possibleId = intval($affectationData['id']);

        if ($possibleId <= 0) {
            DebugUtil::LogIntoFile(__FILE__, __LINE__, "possibleId is <= 0.");
            return;
        }

        RemoveAffectation::TryToRevertEmployeeLocation($affectationData);
        SQLQuery::ExecPreparedQuery("DELETE FROM AFFECTER WHERE NumAffect = '[1]';", $possibleId);
    }
}

/**
 * This file's main and only callback
 */
RemoveAffectation::TryToRemoveAffectation();
header("Refresh:0");
?>