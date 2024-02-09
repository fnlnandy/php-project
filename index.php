<!DOCTYPE html>
<html>
    <head>
        <title>Menu principal</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="View/Stylesheets/main.css">
    </head>
    <body>
        <!-- Initalize the database. -->
        <?php 
        include_once("Models/Database/init.php");

        CheckConnection();
        CreateDatabase();
        // TO-DO: Comment this
        include_once("Models/Database/queries.php");
        PopulateTablesRandomly();
        ?>

        <a href="View/affectation_page.php">Affectation</a>
        <a href="View/location_page.php">Lieu</a>
    </body>
</html>