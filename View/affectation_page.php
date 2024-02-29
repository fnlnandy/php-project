<!DOCTYPE html>
<html>
    <!-- PAGE HEADERS -->
    <head>
        <title>Page des affectations</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Page des affectations">
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
        <div class="search-bar-outer-container top-page-element">
            <form id="affect-based-on-dates-form" method="get">
                <label>Par date d'affectation<input type="checkbox" id="fromDateAffect" name="fromDateAffect" <?php if (key_exists('fromDateAffect', $_GET) && $_GET['fromDateAffect'] == 'on') echo "checked"; ?>></label>
                <label>Par date de prise de service<input type="checkbox" id="fromDatePS" name="fromDatePS" <?php if (key_exists('fromDatePS', $_GET) && $_GET['fromDatePS'] == 'on') echo "checked"; ?>></label>
                <label>Début: <input type="date" id="search-bar-date-begin" name="search-bar-date-begin" <?php if (key_exists('search-bar-date-begin', $_GET) && $_GET['search-bar-date-begin'] != "") { echo "value=\"{$_GET['search-bar-date-begin']}\""; }  ?>></label>
                <label>Fin: <input type="date" id="search-bar-date-end" name="search-bar-date-end" <?php if (key_exists('search-bar-date-end', $_GET) && $_GET['search-bar-date-end'] != "") { echo "value=\"{$_GET['search-bar-date-end']}\""; }  ?>></label>
                <input type="submit" value="Afficher les affectations">
            </form>
        </div>

        <!-- TABLE LISTING DATA -->
        <div class="table-list-outer-container">
            <table class="table-list-inner-container">
                <tr class="affectationHeaderRow">
                    <th>Num Affect</th>
                    <th>Num Emp</th>
                    <th>Ancien Lieu</th>
                    <th>Nouveau Lieu</th>
                    <th>Date Affect</th>
                    <th>Date Prise Service</th>
                </tr>

                <?php
                include_once("../Controller/Affectation/page_load.php");
                AffectationPageLoadConditions::PopulateAffectationList();
                ?>
            </table>
        </div>

        <!-- CRUD OPERATIONS BUTTONS -->
        <div class="force-center-elements">
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
                    <form onsubmit="SubmitForm()" method="post" id="affectationForm">
                            <h3 class="form-title">Formulaire pour une affectation</h3>
                            <label>Employé: 
                                <select id="formNumEmp" name="formNumEmp" onchange="UpdateFormMatchingSelects(true, 'formNumEmp', 'formInfoEmp')" required><?php include_once("ClientDBs/workerselectoptions.php"); WorkerSelectOptions::PopulateSelectOptionIds(); ?></select> 
                                <select id="formInfoEmp" name="formInfoEmp" onchange="UpdateFormMatchingSelects(false, 'formNumEmp', 'formInfoEmp')" required><?php  WorkerSelectOptions::PopulateSelectOptionNames(); ?></select>
                            <br></label>
                            <label>Ancien Lieu: 
                                <select id="formAncienLieu" name="formAncienLieu" onchange="UpdateFormMatchingSelects(true, 'formAncienLieu', 'formInfoAncienLieu')" required><?php include_once("ClientDBs/locationselectoptions.php"); LocationSelectOptions::PopulateSelectOptionIds(); ?></select>
                                <select id="formInfoAncienLieu" name="formInfoAncienLieu" onchange="UpdateFormMatchingSelects(false, 'formAncienLieu', 'formInfoAncienLieu')" required><?php LocationSelectOptions::PopulateSelectOptionNames(); ?></select>
                            <br></label>
                            <label>Nouveau Lieu: 
                                <select id="formNouveauLieu" name="formNouveauLieu" onchange="UpdateFormMatchingSelects(true, 'formNouveauLieu', 'formInfoNouveauLieu')" required><?php include_once("ClientDBs/locationselectoptions.php"); LocationSelectOptions::PopulateSelectOptionIds(); ?></select>
                                <select id="formInfoNouveauLieu" name="formInfoNouveauLieu" onchange="UpdateFormMatchingSelects(false, 'formNouveauLieu', 'formInfoNouveauLieu')" required><?php LocationSelectOptions::PopulateSelectOptionNames(); ?></select>
                            <br></label>
                            <label>Date Affect: <input id="formDateAffect" name="formDateAffect" type="date" required>         <br></label>
                            <label>Date Prise Service: <input id="formPriseService" name="formPriseService" type="date" required><br></label>
                            <input type="submit" value="Confirmer">
                            <input type="reset" value="Réinitaliser">
                    </form>
                </span>
            </div>
        <dialog>
    </body>
</html>