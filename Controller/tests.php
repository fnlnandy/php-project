<?php 
/**
 * Main container for unit tests
 */

include_once("../Models/debug_util.php");

class Test {
    /**
     * Test for the data reformatting function in Affectation
     */
    public static function Test_Affectation_ReformateDate($data)
    {
        DebugUtil::Assert(($data != ""), "\$date is empty.", __FUNCTION__);
    }

    /**
     * Test for the employee location rewinding function in Affectation
     */
    public static function Test_Affectation_RewindEmployee($data)
    {
        DebugUtil::Assert(($data["oldLoc"] != ""), "\$oldLoc is empty.", __FUNCTION__);
        DebugUtil::Assert(($data["newLoc"] != ""), "\$newLoc is empty.", __FUNCTION__);
        DebugUtil::Assert(($data["numEmp"] != ""), "\$numEmp is empty.", __FUNCTION__);
    }

    /**
     * Test for the latest affectation checking function in Affectation
     */
    public static function Test_Affectation_IsLatest($data)
    {
        DebugUtil::Assert(isset($data['row']['NumAffect']), '\$lastAffectRow["NumAffect"] isn\'t set.', __FUNCTION__);
        DebugUtil::Assert(($data['row']['NumAffect'] != "" && intval($data['row']['NumAffect']) > 0), "\$lastAffectRow['NumAffect'] is empty or is <= 0.", __FUNCTION__);
        DebugUtil::Assert(($data['numAffect'] != "" && intval($data['numAffect']) > 0), "\$numAffect is empty or is <= 0.", __FUNCTION__);
    }

    /**
     * Test for the current employee location fixing function in Affectation
     */
    public static function Test_Affectation_FixCurrEmployee($data)
    {
        DebugUtil::Assert(($data["numAffect"] != ""), "\$numAffect is empty.");
        DebugUtil::Assert(($data['row']['NumEmp'] != "" && intval($data['row']['NumEmp']) > 0), "\$currEmployeeRow['NumEmp'] is empty or is <= 0.", __FUNCTION__);
        DebugUtil::Assert(($data['row']['AncienLieu'] != "" && intval($data['row']['AncienLieu']) > 0), "\$currEmployeeRow['AncienLieu'] is empty or is <= 0.", __FUNCTION__);
        DebugUtil::Assert(($data['row']['NouveauLieu'] != "" && intval($data['row']['NumEmp']) > 0), "\$currEmployeeRow['NouveauLieu'] is empty or is <= 0.", __FUNCTION__);
    }

    /**
     * Test for the new ID generation in case of an addition of entry function
     * in Affectation
     */
    public static function Test_Affectation_GenerateNewId($data)
    {
        DebugUtil::Assert((int)$data > 0, "\$newNumAffect is <= 0.", __FUNCTION__);
    }

    /**
     * Test for the check if the employee ID changed in the affectation
     * function in Affectation
     */
    public static function Test_Affectation_IsNewEmpID($data)
    {
        DebugUtil::Assert((intval($data['row']['NumEmp']) > 0), "\$row['NumEmp'] is <= 0.");
        DebugUtil::Assert((intval($data['id']) > 0), "\$newEmpId is <= 0.");
    }

    /**
     * Test for the entry insertion or replacement in Affectation
     */
    public static function Test_Affectation_InsertOrReplace($data)
    {
        DebugUtil::Assert((isset($data['numAffect'])), "\$numAffect isn't set.", __FUNCTION__);
        DebugUtil::Assert((isset($data['editMode'])), "\$editMode isn't set.", __FUNCTION__);
        DebugUtil::Assert((isset($data['notifyEmployee'])), "\$notifyEmployee isn't set.", __FUNCTION__);
        DebugUtil::Assert((intval($data['ancienLieu']) > 0), "\$receivedData['ancienLieu'] is <= 0.", __FUNCTION__);
        DebugUtil::Assert((intval($data['nouveauLieu']) > 0), "\$receivedData['ancienLieu'] is <= 0.", __FUNCTION__);
        DebugUtil::Assert(($data['dateAffect'] != ""), "\$receivedData['dateAffect'] is empty.", __FUNCTION__);
        DebugUtil::Assert(($data['datePriseService'] != ""), "\$receivedData['datePriseService'] is empty.", __FUNCTION__);
    }
}
?>