<!DOCTYPE html>
<html>
    <head>
        <title>Page des employés</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Page des employés">
        <link rel="icon" href="" type="image/x-icon">
        <link rel="stylesheet" href="Stylesheets/main.css">
    </head>
    <body>
    <script src="../Control/Worker/handler.js"></script>
    <script src="../Control/menu_handler.js"></script>
        <nav class="topNavigationBar"><a href="../index.php">Menu principal</a></nav>

        <div class="searchBarAreaContainer">
            <form class="searchBarContainer" method="get">
                <?php
                    // Reloading the value searched on refresh
                    $value = "";

                    if (key_exists('searchBar', $_GET))
                        $value = str_replace('+', ' ', $_GET['searchBar']);

                    $searchBar = "<input type=\"search\" id=\"searchBar\" name=\"searchBar\" placeholder=\"Nom et/ou le Prénom...\" pattern=\"[a-zA-Z ]+\" value=\"{$value}\">";
                    echo $searchBar;
                ?>
                <label>Affectés
                <?php 
                    $checked = "";

                    if (key_exists('showAffectedOnes', $_GET) && $_GET['showAffectedOnes'] == 'on')
                        $checked = "checked";

                    $affectedCBox = "<input type=\"checkbox\" id=\"showAffectedOnes\" name=\"showAffectedOnes\" onchange=\"UpdateUnaffectedCheck()\" {$checked}>";
                    echo $affectedCBox;
                ?>    
                </label>
                <label>Non affectés
                <?php 
                    $checked = "";

                    if (key_exists('showUnaffectedOnes', $_GET) && $_GET['showUnaffectedOnes'] == 'on')
                        $checked = "checked";

                    $unaffectedCBox = "<input type=\"checkbox\" id=\"showUnaffectedOnes\" name=\"showUnaffectedOnes\" onchange=\"UpdateAffectedCheck()\" {$checked}>";
                    echo $unaffectedCBox;
                ?>       
                </label>
                <input type="submit" value="Afficher">
            </form>
        </div>

        <!-- Table that will contain informations about every affectation -->
        <div class="tableListingAreaContainer">
            <table class="tableListingArea">
                <tr class="workerHeaderRow" class="tableListingArea">
                    <th>Num Emp</th>
                    <th>Civilite</th>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>Mail</th>
                    <th>Poste</th>
                    <th>Lieu</th>
                </tr>

                <?php
                    include_once("../Control/Worker/page_load.php");
                    WorkerPageConditions::PopulateWorkersAfterTaskConditions();
                ?>
            </table>
        </div>

        <div class="centerElementsFlex">
            <span class="actionButtonsContainer">
                <button onclick="AddWorker()">Ajouter</button>
                <button onclick="EditWorker()">Modifier</button>
                <button onclick="RemoveWorker()">Supprimer</button>
                <form id="affectationListForm" method="get">
                    <input type="submit" value="Afficher les affectations">
                    <input type="number" id="workerId" name="workerId" readonly hidden>
                </form>
            </span>
        </div>

        <!-- Form that will be shown when adding or editing an entry -->
        <dialog id="formDialog">
            <p onclick="CloseFormDialog()">x</p>
            <div class="centerElementsFlex">
                <span class="formContainer">
                    <form onsubmit="SubmitForm()" method="post" id="workerForm">
                        <h3 class="formTitle">Formulaire pour un employé</h3>
                        <label>Civilité: <select id="formWorkerCivility" name="formWorkerCivility" required>
                                            <option id="sirCivility">Mr</option>
                                            <option id="missCivility">Mlle</option>
                                            <option id="ladyCivility">Mme</option>
                                        </select>       <br></label>
                        <label>Nom: <input id="formWorkerName" name="formWorkerName" type="text" required>       <br></label>
                        <label>Prénom: <input id="formWorkerFirstname" name="formWorkerFirstname" type="text" required>       <br></label>
                        <label>Mail: <input id="formWorkerMail" name="formWorkerMail" type="email" required>       <br></label>
                        <label>Poste: <input id="formWorkerPost" name="formWorkerPost" type="text" required>       <br></label>
                        <label>Lieu: <input id="formWorkerLocation" name="formWorkerLocation" type="text" required>       <br></label>
                        <input type="submit" value="Confirmer">
                        <input type="reset" value="Réinitaliser">
                    </form>
                </span>
            </div>
        </dialog>

        <div id="affectationListContainer" class="tableListingAreaContainer">
            <table id="affectationList" 
            <?php 
                if (!key_exists("workerId", $_GET) || $_GET["workerId"] == "" || intval($_GET["workerId"]) <= 0)
                    echo "hidden"; 
            ?>>
                <tr>
                    <th>Num Affect</th>
                    <th>Ancien Lieu</th>
                    <th>Nouveau Lieu</th>
                    <th>Date Affect</th>
                    <th>Date Prise Service</th>
                </tr>
                <?php 
                include_once("../Control/Worker/affectation_list.php");
                ?>
            </table>
        </div>
    </body>
</html>