<?php
include_once("init.php");

/**
 * Shortcut to avoid referencing $gSqlConnection and choosing
 * out current database everytime we want to execute a single
 * query
 */
function ExecQuery(string $query): mysqli_result|bool
{
    global $gSqlConnection;

    ChooseDatabase();
    $result = $gSqlConnection->query($query);

    if (!$result) {
        die("Error executing query:'".$query."', quitting.");
    }

    return $result;
}

/**
 * Instead of using $var->prepare which requires you to reference
 * $gSqlConnection, and isn't really modular when you want to re-order
 * arguments in it, this function replaces arguments in a string with
 * $queryArgs which are arguments with variable number
 * 
 * Expected format is : INSTR [1] [2]...;
 */
function ExecPreparedQuery(string $queryToBind, ... $queryArgs): mysqli_result|bool
{
    // Expected starting id of the bindable parameters
    $idCounter = 1;

    foreach ($queryArgs as $arg) {
        $queryToBind = str_replace("[".strval($idCounter)."]", 
                    strval($arg), 
                    $queryToBind);
        $idCounter++;
    }

    return ExecQuery($queryToBind);
}


/**
 * Populates the tables for debugging purposes
 */
function PopulateTablesRandomly()
{
}
?>