<?php
include_once("../../Models/queries.php");
include_once("../../Models/table_helpers.php");

/**
 * Removes from EMPLOYE the entry that has id 'id' as value
 * of the column 'NumEmp'
 */
TableHelper::RemoveEntryFromTable("id", "EMPLOYE", "NumEmp");
?>