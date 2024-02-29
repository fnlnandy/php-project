<!DOCTYPE html>
<html>
    <!-- PAGE HEADERS -->
    <head>
        <title>Page des employés</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Page des employés">
        <link rel="icon" href="" type="image/x-icon">
        <link rel="stylesheet" href="Stylesheets/main.css">
    </head>
    <body>
        <!-- INCLUDES -->
        <script src="../Controller/main_handler.js"></script> 
        <script src="../Controller/Worker/handler.js"></script>
        <?php include_once("ClientDBs/locationrel.php"); ?>
        
        <!-- NAVIGATION MENU -->
        <nav class="top-navigation-bar">
            <a href="../index.php">Menu principal</a>
            <a href="affectation_page.php">Affectations</a>
            <a href="location_page.php">Lieux</a>
        </nav>

        <!-- SEARCH BAR AREA -->
        <div class="search-bar-outer-container space-top-element">
            <form class="search-bar-inner-container" method="get">
                <label>Affectés
                <?php 
                    $checked = "";

                    if (key_exists('search-bar-show-affected', $_GET) && $_GET['search-bar-show-affected'] == 'on')
                        $checked = "checked";

                    $affectedCBox = "<input type=\"checkbox\" id=\"search-bar-show-affected\" name=\"search-bar-show-affected\" onchange=\"UpdateUnaffectedCheck()\" {$checked}>";
                    echo $affectedCBox;
                ?>    
                </label>
                <label>Non affectés
                <?php 
                    $checked = "";

                    if (key_exists('search-bar-show-unaffected', $_GET) && $_GET['search-bar-show-unaffected'] == 'on')
                        $checked = "checked";

                    $unaffectedCBox = "<input type=\"checkbox\" id=\"search-bar-show-unaffected\" name=\"search-bar-show-unaffected\" onchange=\"UpdateAffectedCheck()\" {$checked}>";
                    echo $unaffectedCBox;
                ?>
                </label>
                <?php
                    // Reloading the value searched on refresh
                    $value = "";

                    if (key_exists('worker-search-bar', $_GET))
                        $value = str_replace('+', ' ', $_GET['worker-search-bar']);

                    $searchBar = "<input type=\"search\" id=\"worker-search-bar\" name=\"worker-search-bar\" placeholder=\"Nom et/ou le Prénom...\" pattern=\"[a-zA-Z ]+\" value=\"{$value}\">";
                    echo $searchBar;
                ?>     
                <input type="submit" value="Rechercher parmis les employés">
            </form>
        </div>

        <!-- TABLE LISTING DATA -->
        <div class="table-list-outer-container space-top-element">
            <table class="table-list-inner-container">
                <tr class="table-header-row" class="table-list-inner-container">
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

        <!-- CRUD OPERATIONS BUTTONS -->
        <div class="force-center-elements space-top-element">
            <span class="crud-actions-container">
                <button onclick="AddWorker()">Ajouter</button>
                <button onclick="EditWorker()">Modifier</button>
                <button onclick="RemoveWorker()">Supprimer</button>
                <form id="worker-affects-list-form" method="get">
                    <input type="submit" value="Afficher les affectations">
                    <input type="number" id="worker-id" name="worker-id" readonly hidden>
                </form>
            </span>
        </div>

        <!-- DATA FILLING FORM -->
        <dialog id="form-dialog-container">
            <p onclick="CloseFormDialog()">x</p>
            <div class="force-center-elements">
                <span class="form-inner-container">
                    <form onsubmit="SubmitForm()" method="post" id="worker-main-form">
                        <h3 id="form-title">Formulaire pour un employé</h3>
                        <label>Civilité: <select id="form-worker-civility" name="form-worker-civility" required>
                                            <option id="sir-civility">Mr</option>
                                            <option id="miss-civility">Mlle</option>
                                            <option id="lady-civility">Mme</option>
                                        </select>       <br></label>
                        <label>Nom: <input id="form-worker-name" name="form-worker-name" type="text" maxlength="30" required>       <br></label>
                        <label>Prénom: <input id="form-worker-first-name" name="form-worker-first-name" maxlength="40" type="text" required>       <br></label>
                        <label>Adresse e-mail: <input id="form-worker-mail" name="form-worker-mail" type="email" maxlength="254" required>       <br></label>
                        <label>Poste: <input id="form-worker-post" name="form-worker-post" type="text" maxlength="50" required>       <br></label>
                        <label>Lieu: 
                            <select id="form-worker-location" name="form-worker-location" onchange="UpdateFormMatchingSelects(true, 'form-worker-location', 'form-info-worker-location')" required><?php include_once("ClientDBs/locationselectoptions.php"); LocationSelectOptions::PopulateSelectOptionIds(); ?></select>
                            <select id="form-info-worker-location" name="form-info-worker-location" onchange="UpdateFormMatchingSelects(false, 'form-worker-location', 'form-info-worker-location')" required><?php LocationSelectOptions::PopulateSelectOptionNames(); ?></select>
                        <br></label>
                        <input type="submit" value="Confirmer">
                        <input type="reset" value="Réinitaliser">
                    </form>
                </span>
            </div>
        </dialog>

        <!-- CUSTOM: LIST OF AFFECTATIONS FOR A WORKER -->
        <div id="worker-affects-list-outer-container" class="table-list-outer-container">
            <table id="worker-affects-list-inner-container" class="table-list-inner-container"
            <?php 
                if (!key_exists("worker-id", $_GET) || $_GET["worker-id"] == "" || intval($_GET["worker-id"]) <= 0)
                    echo "hidden"; 
            ?>>
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
    </body>
</html>