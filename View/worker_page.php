<!DOCTYPE html>
<html>
    <!-- PAGE HEADERS -->
    <head>
        <title>Page des employés</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Page des employés">
        <meta http-equiv="Cache-control" content="no-cache, no-store, must-revalidate">
        <meta http-equiv="Expires" content="0">
        <meta http-equiv="Pragma" content="no-cache">
        <link rel="icon" href="" type="image/x-icon">
        <link rel="stylesheet" href="Stylesheets/main.css">
    </head>
    <body>
        <!-- INCLUDES -->
        <?php include_once("ClientDBs/locationrel.php"); ?>
        
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
                Employés
            </div>
        </header>

        <!-- SEARCH BAR AREA -->
        <main class="page-main-content">
            <div class="search-bar-outer-container space-top-element">
                <form class="search-bar-inner-container" method="get">
                    <div class="search-bar-field-wrapper">
                    <label for="search-bar-show-affected">Affectés
                    </label>
                    <input class="flat-checkbox" type="checkbox" id="search-bar-show-affected" name="search-bar-show-affected" onchange="UpdateUnaffectedCheck()"
                    <?php 
                        $checked = "";

                        if (key_exists('search-bar-show-affected', $_GET) && $_GET['search-bar-show-affected'] == 'on')
                            $checked = "checked";
                        
                        echo $checked;
                    ?>>
                    </div>
                    <div class="search-bar-field-wrapper">
                    <label for="search-bar-show-unaffected">Non affectés
                    </label>
                    <input class="flat-checkbox" type="checkbox" id="search-bar-show-unaffected" name="search-bar-show-unaffected" onchange="UpdateAffectedCheck()"
                    <?php 
                        $checked = "";

                        if (key_exists('search-bar-show-unaffected', $_GET) && $_GET['search-bar-show-unaffected'] == 'on')
                            $checked = "checked";

                        echo $checked;
                    ?>>
                    </div>
                    <div class="search-bar-field-wrapper">
                    <?php
                        // Reloading the value searched on refresh
                        $value = "";

                        if (key_exists('worker-search-bar', $_GET))
                            $value = str_replace('+', ' ', $_GET['worker-search-bar']);

                        $searchBar = "<input class=\"search-bar-component\" type=\"search\" id=\"worker-search-bar\" name=\"worker-search-bar\" placeholder=\"Nom et/ou le(s) Prénom(s)...\" pattern=\"[a-zA-Z ]+\" value=\"{$value}\">";
                        echo $searchBar;
                    ?>
                    </div>    
                    <input class="button-highlight-blue" type="submit" value="Rechercher parmis les employés">
                </form>
            </div>

        <!-- TABLE LISTING DATA -->
        <div class="table-list-outer-container space-top-element">
            <div class="table-list-padder">
                <table class="table-list-inner-container">
                    <tr class="table-header-row">
                        <th>Numéro d'employé</th>
                        <th>Civilité</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Adresse e-mail</th>
                        <th>Poste</th>
                        <th>Lieu</th>
                    </tr>

                    <?php
                        include_once("../Controller/Worker/page_load.php");
                        WorkerPageConditions::PopulateWorkersAfterTaskConditions();
                    ?>
                </table>
            </div>
        </div>

        <!-- CRUD OPERATIONS BUTTONS -->
        <div id="crud-actions-movable" class="crud-actions-wrapper">
            <span class="crud-actions-container">
                <button class="button-highlight-green" onclick="AddWorker()">Ajouter</button>
                <button class="button-highlight-blue" onclick="EditWorker()">Modifier</button>
                <button class="button-highlight-red" onclick="RemoveWorker()">Supprimer</button>
                <button onclick="DisplayCurrentEmployeeAffectation()" class="button-highlight-blue">Afficher les affectations</button>
                <input type="number" id="worker-id" name="worker-id" readonly hidden>
            </span>
        </div>
        </main>

        <!-- DATA FILLING FORM -->
        <dialog id="form-dialog-container">
            <p onclick="CloseFormDialog()" class="form-quit-button button-highlight-red"></p>
            <div class="force-center-elements">
                <span class="form-inner-container">
                    <form onsubmit="SubmitForm()" method="post" id="worker-main-form">
                        <h3 id="form-title">Formulaire pour un employé</h3>
                        <div class="form-field-container">
                            <label class="form-field-label">Civilité: <select class="form-field-value" id="form-worker-civility" name="form-worker-civility" required>
                                                <option id="sir-civility">Mr</option>
                                                <option id="miss-civility">Mlle</option>
                                                <option id="lady-civility">Mme</option>
                                            </select>       <br></label>
                        </div>
                        <div class="form-field-container">
                            <label class="form-field-label">Nom: <input class="form-field-value" id="form-worker-name" name="form-worker-name" type="text" maxlength="30" pattern="[a-zA-Z ]+" required>       <br></label>
                        </div>
                        <div class="form-field-container">
                            <label class="form-field-label">Prénom: <input class="form-field-value" id="form-worker-first-name" name="form-worker-first-name" maxlength="40" pattern="[a-zA-Z ]+" type="text" required>       <br></label>
                        </div>
                        <div class="form-field-container">
                            <label class="form-field-label">Adresse e-mail: <input class="form-field-value" id="form-worker-mail" name="form-worker-mail" type="email" maxlength="254" required>       <br></label>
                        </div>
                        <div class="form-field-container">
                            <label class="form-field-label">Poste: <input class="form-field-value" id="form-worker-post" name="form-worker-post" type="text" pattern="[a-zA-Z ]+" maxlength="50" required>       <br></label>
                        </div>
                        <div class="form-field-container">
                            <label class="form-field-label">Lieu: 
                                <select class="form-field-value" id="form-worker-location" name="form-worker-location" onchange="UpdateFormMatchingSelects(true, 'form-worker-location', 'form-info-worker-location')" required><?php include_once("ClientDBs/locationselectoptions.php"); LocationSelectOptions::PopulateSelectOptionIds(); ?></select>
                                <select class="form-field-value" id="form-info-worker-location" name="form-info-worker-location" onchange="UpdateFormMatchingSelects(false, 'form-worker-location', 'form-info-worker-location')" required><?php LocationSelectOptions::PopulateSelectOptionNames(); ?></select>
                            <br></label>
                        </div>
                        <input class="button-highlight-green" type="submit" value="Confirmer">
                        <input class="button-highlight-red" type="reset" value="Réinitaliser">
                    </form>
                </span>
            </div>
        </dialog>

        <!-- CUSTOM: LIST OF AFFECTATIONS FOR A WORKER -->
        <dialog id="affect-list-dialog-container">
            <p onclick="UnscheduleListing()" class="form-quit-button button-highlight-red"></p>
            <h3 id="affect-list-title"></h3>
            <div id="worker-affects-list-outer-container" class="table-list-outer-container">
                <table id="worker-affects-list-inner-container" class="table-list-inner-container">
                    <tr class="table-header-row">
                        <th>Numéro d'affectation</th>
                        <th>Ancien lieu</th>
                        <th>Nouveau lieu</th>
                        <th>Date d'affectation</th>
                        <th>Date de prise de service</th>
                    </tr>
                    <?php 
                    include_once("../Controller/Worker/affectation_list.php");
                    ?>
                </table>
            </div>
        </dialog>

        <!-- JAVASCRIPT SCRIPTS -->
        <script src="../Controller/main_handler.js"></script> 
        <script src="../Controller/Worker/handler.js"></script>
    </body>
</html>