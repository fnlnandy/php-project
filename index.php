<!DOCTYPE html>
<html>
    <head>
        <title>Menu principal</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Menu principal">
        <link rel="icon" href="" type="image/x-icon">
        <link rel="stylesheet" href="View/Stylesheets/main.css">
    </head>
    <body>
        <!-- Initalize the database. -->
        <?php 
        include_once("Models/queries.php");
        SQLQuery::CheckConnection();
        SQLQuery::CreateDatabase();
        ?>

        <nav id="navigationMenu">
        <a href="View/affectation_page.php">Affectation</a> <br>
        <a href="View/location_page.php">Lieu</a>           <br>
        <a href="View/worker_page.php">Employés</a>         
        </nav>
    </body>
</html>