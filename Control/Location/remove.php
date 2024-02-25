<?php
include_once("../../Models/queries.php");
include_once("../../Models/table_helpers.php");

/**
 * This file's main and only callback
 */
TableHelper::RemoveEntryFromTable("id", "LIEU", "IDLieu");
header("Refresh:0");
?>