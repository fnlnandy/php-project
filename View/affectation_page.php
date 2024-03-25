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
        <link rel="icon" href="Ressources/list-affect-icon.svg" type="image/x-icon">
        <link rel="stylesheet" href="Stylesheets/main.css">
    </head>
    <body>
        <!-- INCLUDES -->
        <?php include_once("ClientDBs/workerrel.php");
              include_once("ClientDBs/locationrel.php"); ?>
        
        <!-- NAVIGATION MENU -->
        <header class="page-header">
            <div class="navigation-bar-wrapper">
                <input class="top-navigation-bar-burger-check" id="top-navigation-bar-burger-check" type="checkbox">
                <label for="top-navigation-bar-burger-check" class="top-navigation-bar-burger"></label>
                <nav class="top-navigation-bar">
                    <ul>
                        <li><a href="../index.php">Menu principal</a></li>
                        <li><a href="affectation_page.php">Affectations</a></li>
                        <li><a href="location_page.php">Lieux</a></li>
                        <li><a href="worker_page.php">Employés</a></li>
                    </ul>
                </nav>
            </div>
            <div class="page-title">
                Affectations
            </div>
        </header>

        <main class="page-main-content">
            <!-- SEARCH BAR AREA -->
            <div class="search-bar-outer-container space-top-element space-bottom-element">
                <form id="affect-based-on-dates-form" class="search-bar-inner-container" method="get">
                    <div class="search-bar-field-wrapper">
                        <label>Date de début: <input class="search-bar-component" type="date" id="search-bar-date-begin" name="search-bar-date-begin" <?php if (key_exists('search-bar-date-begin', $_GET) && $_GET['search-bar-date-begin'] != "") { echo "value=\"{$_GET['search-bar-date-begin']}\""; }  ?>></label>
                    </div>
                    <div class="search-bar-field-wrapper">
                        <label>Date de fin: <input class="search-bar-component" type="date" id="search-bar-date-end" name="search-bar-date-end" <?php if (key_exists('search-bar-date-end', $_GET) && $_GET['search-bar-date-end'] != "") { echo "value=\"{$_GET['search-bar-date-end']}\""; }  ?>></label>
                    </div>
                    <div class="search-bar-field-wrapper">
                        <label for="search-date-affect-based">Par date<br>d'affectation</label><input class="flat-checkbox" type="checkbox" id="search-date-affect-based" name="search-date-affect-based" <?php if (key_exists('search-date-affect-based', $_GET) && $_GET['search-date-affect-based'] == 'on') echo "checked"; ?>>
                    </div>
                    <div class="search-bar-field-wrapper">
                        <label for="search-date-ps-based">Par date<br>de prise de service</label><input class="flat-checkbox" type="checkbox" id="search-date-ps-based" name="search-date-ps-based" <?php if (key_exists('search-date-ps-based', $_GET) && $_GET['search-date-ps-based'] == 'on') echo "checked"; ?>>
                    </div>
                    <input class="button-highlight-blue" type="submit" value="Trier les affectations">
                </form>
            </div>

            <!-- TABLE LISTING DATA -->
            <div class="table-list-outer-container space-top-element">
                <div class = "table-list-padder">
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
            </div>

            <!-- CRUD OPERATIONS BUTTONS -->
            <div id="crud-actions-movable" class="crud-actions-wrapper">
                <div class="crud-actions-container">
                    <button class="button-highlight-green" onclick="AddAffectation()">Ajouter</button>
                    <button class="button-highlight-blue" onclick="EditAffectation()">Modifier</button>
                    <button class="button-highlight-red" onclick="RemoveAffectationEntry()">Supprimer</button>
                    <button class="button-highlight-blue" onclick="TryGeneratePDF()">Generer un PDF</button>
                </div>
            </div>
        </main>

        <!-- DATA FILLING FORM -->
        <dialog id="form-dialog-container">
            <p onclick="CloseFormDialog()" class="form-quit-button button-highlight-red"></p>
            <div class="force-center-elements">
                <span class="form-inner-container">
                    <form onsubmit="event.preventDefault(); SubmitForm();" method="post" id="affectation-main-form">
                            <h3 id="form-title">Formulaire pour une affectation</h3>
                            <div class="form-field-container">
                                <label class="form-field-label">Employé: 
                                    <select class="form-field-value" id="form-affectation-employee-num" name="form-affectation-employee-num" onchange="UpdateFormMatchingSelects(true, 'form-affectation-employee-num', 'form-affectation-info-employee')" required><?php include_once("ClientDBs/workerselectoptions.php"); WorkerSelectOptions::PopulateSelectOptionIds(); ?></select> 
                                    <select class="form-field-value" id="form-affectation-info-employee" name="form-affectation-info-employee" onchange="UpdateFormMatchingSelects(false, 'form-affectation-employee-num', 'form-affectation-info-employee')" required><?php  WorkerSelectOptions::PopulateSelectOptionNames(); ?></select>
                                <br></label>
                            </div>
                            <div class="form-field-container">
                                <label class="form-field-label">Ancien lieu: 
                                    <select class="form-field-value" id="form-affectation-old-location" name="form-affectation-old-location" onchange="UpdateFormMatchingSelects(true, 'form-affectation-old-location', 'form-affectation-info-old-location')" required><?php include_once("ClientDBs/locationselectoptions.php"); LocationSelectOptions::PopulateSelectOptionIds(); ?></select>
                                    <select class="form-field-value" id="form-affectation-info-old-location" name="form-affectation-info-old-location" onchange="UpdateFormMatchingSelects(false, 'form-affectation-old-location', 'form-affectation-info-old-location')" required><?php LocationSelectOptions::PopulateSelectOptionNames(); ?></select>
                                <br></label>
                            </div>
                            <div class="form-field-container">
                                <label class="form-field-label">Nouveau lieu: 
                                    <select class="form-field-value" id="form-affectation-new-location" name="form-affectation-new-location" onchange="UpdateFormMatchingSelects(true, 'form-affectation-new-location', 'form-affectation-info-new-location')" required><?php include_once("ClientDBs/locationselectoptions.php"); LocationSelectOptions::PopulateSelectOptionIds(); ?></select>
                                    <select class="form-field-value" id="form-affectation-info-new-location" name="form-affectation-info-new-location" onchange="UpdateFormMatchingSelects(false, 'form-affectation-new-location', 'form-affectation-info-new-location')" required><?php LocationSelectOptions::PopulateSelectOptionNames(); ?></select>
                                <br></label>
                            </div>
                            <div class="form-field-container">
                                <label class="form-field-label">Date d'affectation: <input class="form-field-value" id="form-affectation-date-affect" name="form-affectation-date-affect" type="date" required>         <br></label>
                            </div>
                            <div class="form-field-container">
                                <label class="form-field-label">Date de prise de service: <input class="form-field-value" id="form-affectation-date-ps" name="form-affectation-date-ps" type="date" required><br></label>
                            </div>
                            <div class="form-field-container">
                                <label class="form-field-label" for="form-notify-employee">Notifier l'employé par e-mail:</label><input class="flat-checkbox" id="form-notify-employee" name="form-notify-employee" type="checkbox" checked>
                            </div>
                            <input class="button-highlight-green" type="submit" value="Confirmer">
                            <input class="button-highlight-red" type="reset" value="Réinitaliser">
                    </form>
                </span>
            </div>
        </dialog>

        <dialog id="pdf-file-dialog">
            <p onclick="CloseFormDialog('pdf-file-dialog')" class="form-quit-button button-highlight-red"></p>
                <form onsubmit="event.preventDefault(); SubmitPDFForm();" method="post" id="pdf-main-form">
                        <h3 id="form-title">Informations du Fichier PDF</h3>
                        <div class="form-field-container">
                            <label class="form-field-label">Les fichiers seront sauvegardés par défaut dans PDFs/</label>
                        </div>
                        <div class="form-field-container">
                            <label class="form-field-label">Nom du fichier:
                                <input id="pdf-name-field" name="pdf-name-field" class="form-field-value" type="text" required>
                            <br></label>
                        </div>
                        <input class="button-highlight-green" type="submit" value="Confirmer">
                        <input class="button-highlight-red" type="reset" value="Réinitaliser">
                </form>
        </dialog>

        <!-- JAVASCRIPT SCRIPTS -->
        <script src="../Controller/main_handler.js"></script>
        <script src="../Controller/Affectation/handler.js"></script>
    </body>
</html>