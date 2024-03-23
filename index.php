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
        <main class="main-navigation-menu">
            <div class="main-menu-card" onclick="RedirectToPage(this)">
                <a href="View/affectation_page.php"></a>
                <div class="main-menu-card-wrapper">
                    <div class="main-menu-card-back"></div>
                </div>
                <div class="main-menu-card-img"></div>
                <div class="main-menu-card-title">Affectations</div>
                <div class="main-menu-card-description">Ajouter, modifier et supprimer les affectations de chaques employés</div>
            </div>

            <div class="main-menu-card" onclick="RedirectToPage(this)">
                <a href="View/location_page.php"></a>
                <div class="main-menu-card-wrapper">
                    <div class="main-menu-card-back"></div>
                </div>
                <div class="main-menu-card-img"></div>
                <div class="main-menu-card-title">Lieux</div>
                <div class="main-menu-card-description">Ajouter, modifier et supprimer les lieux où les employés sont affectés</div>
            </div>

            <div class="main-menu-card" onclick="RedirectToPage(this)">
                <a href="View/worker_page.php"></a>
                <div class="main-menu-card-wrapper">
                    <div class="main-menu-card-back"></div>
                </div>
                <div class="main-menu-card-img"></div>
                <div class="main-menu-card-title">Employés</div>
                <div class="main-menu-card-description">Ajouter, modifier et supprimer les affectations de chaques employés</div>
            </div>
        <main>
    </body>
</html>