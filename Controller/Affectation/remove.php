<?php
include_once("../../Models/queries.php");
include_once("../../Models/table_helpers.php");
include_once("../../Models/debug_util.php");
include_once("../tests.php");

class RemoveAffectation
{
    /**
     * Checks if the removed affectation is the latest, the logic is that
     * the latest affectation is the 'active' one, and the others are irrelevant
     */
    public static function IsRemovedAffectationLatest(string $id): bool
    {
        $lastAffectResult = SQLQuery::ExecPreparedQuery("SELECT * FROM AFFECTER WHERE NumEmp = (SELECT NumEmp FROM AFFECTER WHERE NumAffect = '[1]') ORDER BY LENGTH(NumAffect) DESC, NumAffect DESC LIMIT 1;", $id);
        $row = SQLQuery::ProcessResultAsAssocArray($lastAffectResult, 'NumAffect');
        
        if (is_null($row))
            return false;

        Test::Test_Affectation_IsRemovedLatest(array("numAffect" => $row["NumAffect"], "id" => $id));

        return (intval($row['NumAffect']) == intval($id));
    }

    /**
     * Tries to revert the employee's location data to the previous
     * data
     */
    public static function TryToRevertEmployeeLocation(array $data): void
    {
        $affectId = $data['id'];

        if (!RemoveAffectation::IsRemovedAffectationLatest($affectId)) {
            DebugUtil::LogIntoFile(__FILE__, __LINE__, "Removed affectation isn't the latest: {$affectId}.");
            return;
        }

        $query    = "SELECT NumEmp, AncienLieu, NouveauLieu FROM AFFECTER WHERE NumAffect = '[1]';";
        $result = SQLQuery::ExecPreparedQuery($query, $affectId);
        $row = SQLQuery::ProcessResultAsAssocArray($result, 'NumEmp', 'AncienLieu', 'NouveauLieu');

        if (is_null($row))
            return;

        // As in form_submit.php, only update if it was actually a valid affectation i.e.
        // the new location (since we're reverting) is the current location
        $query = "UPDATE EMPLOYE SET Lieu = '[1]' WHERE NumEmp = '[2]' AND Lieu = '[3]';";
        $result = SQLQuery::ExecPreparedQuery($query, $row['AncienLieu'], $row['NumEmp'], $row['NouveauLieu']);
        DebugUtil::LogIntoFile(__FILE__, __LINE__, "{$row['NouveauLieu']} {$row['NumEmp']} {$row['AncienLieu']}");
    }

    /**
     * Tries to remove the selected affectation from the database
     */
    public static function TryToRemoveAffectation(): void
    {
        $affectationData = XMLHttpRequest::DecodeJson();
        $possibleId      = 0;

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
HTML::ForceRefresh();
?>