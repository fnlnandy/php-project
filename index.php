<!DOCTYPE html>
<html>
    <!-- PAGE HEADERS -->
    <head>
        <title>Menu principal</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Menu principal">
        <meta http-equiv="Cache-control" content="no-cache, no-store, must-revalidate">
        <meta http-equiv="Expires" content="0">
        <meta http-equiv="Pragma" content="no-cache">
        <link rel="icon" href="" type="image/x-icon">
        <link rel="stylesheet" href="View/Stylesheets/main.css">
    </head>
    <body>
        <!-- INCLUDES -->
        <script src="Controller/main_handler.js"></script>
        <!-- Initalize the database. -->
        <?php 
        include_once("Models/queries.php");
        SQLQuery::CheckConnection();
        SQLQuery::CreateDatabase();
        ?>

        <!-- CUSTOM: MAIN NAVIGATION MENU -->
        <section id="index-main-menu">
            <div id="index-menu-outer-container">
                <ol id="index-menu-inner-container">
                    <li class="index-menu-inner-container" onclick="RedirectToPage(this)"><a href="View/affectation_page.php">Affectations</a></li>
                    <li class="index-menu-inner-container" onclick="RedirectToPage(this)"><a href="View/location_page.php">Lieux</a></li>
                    <li class="index-menu-inner-container" onclick="RedirectToPage(this)"><a href="View/worker_page.php">Employés</a></li>
                </ol>
            </div>
        </section>
    </body>
</html>