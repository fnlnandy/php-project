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
        <script src="../Control/Affectation/handler.js"></script>
        <script src="../Control/menu_handler.js"></script>
        <?php include_once("ClientDBs/workerrel.php");
              include_once("ClientDBs/locationrel.php"); ?>
        
        <!-- NAVIGATION MENU -->
        <nav class="topNavigationBar"><a href="../index.php">Menu principal</a></nav>

        <!-- SEARCH BAR AREA -->
        <div class="searchBarAreaContainer">
            <form id="twoDatesForm" method="get">
                <label>Début: <input type="date" id="dateStart" name="dateStart" <?php if (key_exists('dateStart', $_GET) && $_GET['dateStart'] != "") { echo "value=\"{$_GET['dateStart']}\""; }  ?>></label>
                <label>Fin: <input type="date" id="dateEnd" name="dateEnd" <?php if (key_exists('dateEnd', $_GET) && $_GET['dateEnd'] != "") { echo "value=\"{$_GET['dateEnd']}\""; }  ?>></label>
                <input type="submit" value="Afficher les affectations">
            </form>
        </div>

        <!-- TABLE LISTING DATA -->
        <div class="tableListingAreaContainer">
            <table class="tableListingArea">
                <tr class="affectationHeaderRow">
                    <th>Num Affect</th>
                    <th>Num Emp</th>
                    <th>Ancien Lieu</th>
                    <th>Nouveau Lieu</th>
                    <th>Date Affect</th>
                    <th>Date Prise Service</th>
                </tr>

                <?php
                include_once("../Control/Affectation/page_load.php");
                AffectationPageLoadConditions::PopulateAffectationList();
                ?>
            </table>
        </div>

        <!-- CRUD OPERATIONS BUTTONS -->
        <div class="centerElementsFlex">
            <span class="actionButtonsContainer">
                <button onclick="AddAffectation()">Ajouter</button>
                <button onclick="EditAffectation()">Modifier</button>
                <button onclick="RemoveAffectationEntry()">Supprimer</button>
                <button onclick="TryGeneratePDF()">Generer un PDF</button>
            </span>
        </div>

        <!-- DATA FILLING FORM -->
        <dialog id="formDialog">
            <p onclick="CloseFormDialog()">x</p>
            <div class="centerElementsFlex">
                <span class="formContainer">
                    <form onsubmit="SubmitForm()" method="post" id="affectationForm">
                            <h3 class="formTitle">Formulaire pour une affectation</h3>
                            <label>Numéro Employé: 
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