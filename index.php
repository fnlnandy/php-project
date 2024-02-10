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
        include_once("Models/Database/queries.php");
        SQLQuery::CheckConnection();
        SQLQuery::CreateDatabase();
        ?>

        <a href="View/affectation_page.php">Affectation</a>
        <a href="View/location_page.php">Lieu</a>
        <a href="View/worker_page.php">Employés</a>
    </body>
</html>