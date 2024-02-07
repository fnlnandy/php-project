<?php
include_once("init.php");

/*
* Instantly executes a query without the hassle
* of referencing $gSqlConnection and specifiying
* the use of our database
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

/*
* Instead of using the default prepared query format using ?
* we bind parameters using their indexes, the expected format is:
* Ex: SELECT [1] FROM [2] WHERE [3] = [4];
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

    var_dump($queryToBind);

    return ExecQuery($queryToBind);
}

/*
* For debugging purposes 
*/
function PopulateTablesRandomly()
{
    ExecQuery("DELETE FROM LIEU");
    ExecQuery("DELETE FROM EMPLOYE");
    ExecQuery("DELETE FROM AFFECTER");
    ExecQuery("INSERT INTO LIEU VALUES
    ('1', 'Lieu1', 'Province1'),
    ('2', 'Lieu2', 'Province2'),
    ('3', 'Lieu3', 'Province3'),
    ('4', 'Lieu4', 'Province4');");
    ExecQuery("INSERT INTO EMPLOYE VALUES
    ('1', 'Mr', 'RAKOTO', 'Be', 'rakotobe@host.com', 'Directeur General', '1'),
    ('2', 'Mlle', 'RANDRIA', 'Kely', 'randriakely@host.com', 'Ingenieur', '2'),
    ('3', 'Mme', 'RAVO', 'Soa', 'ravosoa@host.com', 'Ingenieur', '3');");
    ExecQuery("INSERT INTO AFFECTER VALUES
    ('1', '1', '1', '2', '2024-01-02', '2024-02-15'),
    ('2', '3', '2', '1', '2024-01-19', '2024-01-02');");
}
?>