<!DOCTYPE html>
<html>
    <!-- PAGE HEADERS -->
    <head>
        <title>Page des affectations</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Page des affectations">
        <meta http-equiv="Cache-control" content="no-cache, no-store, must-revalidate">
        <meta http-equiv="Expires" content="0">
        <meta http-equiv="Pragma" content="no-cache">
        <link rel="icon" href="" type="image/x-icon">
        <link rel="stylesheet" href="Stylesheets/main.css">
    </head>
    <body>
        <!-- INCLUDES -->
        <script src="../Controller/main_handler.js"></script>
        <script src="../Controller/Affectation/handler.js"></script>
        <?php include_once("ClientDBs/workerrel.php");
              include_once("ClientDBs/locationrel.php"); ?>
        
        <!-- NAVIGATION MENU -->
        <nav class="top-navigation-bar">
            <a href="../index.php">Menu principal</a>
            <a href="location_page.php">Lieux</a>
            <a href="worker_page.php">Employés</a>
        </nav>

        <!-- SEARCH BAR AREA -->
        <div class="search-bar-outer-container space-top-element space-bottom-element">
            <form id="affect-based-on-dates-form" method="get">
                <label>Date de début: <input type="date" id="search-bar-date-begin" name="search-bar-date-begin" <?php if (key_exists('search-bar-date-begin', $_GET) && $_GET['search-bar-date-begin'] != "") { echo "value=\"{$_GET['search-bar-date-begin']}\""; }  ?>></label>
                <label>Date de fin: <input type="date" id="search-bar-date-end" name="search-bar-date-end" <?php if (key_exists('search-bar-date-end', $_GET) && $_GET['search-bar-date-end'] != "") { echo "value=\"{$_GET['search-bar-date-end']}\""; }  ?>></label>
                <label>Par date d'affectation<input type="checkbox" id="search-date-affect-based" name="search-date-affect-based" <?php if (key_exists('search-date-affect-based', $_GET) && $_GET['search-date-affect-based'] == 'on') echo "checked"; ?>></label>
                <label>Par date de prise de service<input type="checkbox" id="search-date-ps-based" name="search-date-ps-based" <?php if (key_exists('search-date-ps-based', $_GET) && $_GET['search-date-ps-based'] == 'on') echo "checked"; ?>></label>
                <input type="submit" value="Trier les affectations">
            </form>
        </div>

        <!-- TABLE LISTING DATA -->
        <div class="table-list-outer-container space-top-element">
            <table class="table-list-inner-container">
                <tr class="table-header-row">
                    <th>Numéro d'affectation</th>
                    <th>Employé</th>
                    <th>Ancien lieu</th>
                    <th>Nouveau lieu</th>
                    <th>Date d'affectation</th>
                    <th>Date de prise de service</th>
                </tr>

                <?php
                include_once("../Controller/Affectation/page_load.php");
                AffectationPageLoadConditions::PopulateAffectationList();
                ?>
            </table>
        </div>

        <!-- CRUD OPERATIONS BUTTONS -->
        <div class="force-center-elements space-top-element">
            <span class="crud-actions-container">
                <button onclick="AddAffectation()">Ajouter</button>
                <button onclick="EditAffectation()">Modifier</button>
                <button onclick="RemoveAffectationEntry()">Supprimer</button>
                <button onclick="TryGeneratePDF()">Generer un PDF</button>
            </span>
        </div>

        <!-- DATA FILLING FORM -->
        <dialog id="form-dialog-container">
            <p onclick="CloseFormDialog()">x</p>
            <div class="force-center-elements">
                <span class="form-inner-container">
                    <form onsubmit="SubmitForm()" method="post" id="affectation-main-form">
                            <h3 id="form-title">Formulaire pour une affectation</h3>
                            <label>Employé: 
                                <select id="form-affectation-employee-num" name="form-affectation-employee-num" onchange="UpdateFormMatchingSelects(true, 'form-affectation-employee-num', 'form-affectation-info-employee')" required><?php include_once("ClientDBs/workerselectoptions.php"); WorkerSelectOptions::PopulateSelectOptionIds(); ?></select> 
                                <select id="form-affectation-info-employee" name="form-affectation-info-employee" onchange="UpdateFormMatchingSelects(false, 'form-affectation-employee-num', 'form-affectation-info-employee')" required><?php  WorkerSelectOptions::PopulateSelectOptionNames(); ?></select>
                            <br></label>
                            <label>Ancien lieu: 
                                <select id="form-affectation-old-location" name="form-affectation-old-location" onchange="UpdateFormMatchingSelects(true, 'form-affectation-old-location', 'form-affectation-info-old-location')" required><?php include_once("ClientDBs/locationselectoptions.php"); LocationSelectOptions::PopulateSelectOptionIds(); ?></select>
                                <select id="form-affectation-info-old-location" name="form-affectation-info-old-location" onchange="UpdateFormMatchingSelects(false, 'form-affectation-old-location', 'form-affectation-info-old-location')" required><?php LocationSelectOptions::PopulateSelectOptionNames(); ?></select>
                            <br></label>
                            <label>Nouveau lieu: 
                                <select id="form-affectation-new-location" name="form-affectation-new-location" onchange="UpdateFormMatchingSelects(true, 'form-affectation-new-location', 'form-affectation-info-new-location')" required><?php include_once("ClientDBs/locationselectoptions.php"); LocationSelectOptions::PopulateSelectOptionIds(); ?></select>
                                <select id="form-affectation-info-new-location" name="form-affectation-info-new-location" onchange="UpdateFormMatchingSelects(false, 'form-affectation-new-location', 'form-affectation-info-new-location')" required><?php LocationSelectOptions::PopulateSelectOptionNames(); ?></select>
                            <br></label>
                            <label>Date d'affectation: <input id="form-affectation-date-affect" name="form-affectation-date-affect" type="date" required>         <br></label>
                            <label>Date de prise de service: <input id="form-affectation-date-ps" name="form-affectation-date-ps" type="date" required><br></label>
                            <label>Notifier l'employé par e-mail<input id="form-notify-employee" name="form-notify-employee" type="checkbox" checked></label>
                            <input type="submit" value="Confirmer">
                            <input type="reset" value="Réinitaliser">
                    </form>
                </span>
            </div>
        <dialog>
    </body>
</html>