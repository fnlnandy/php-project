<!DOCTYPE html>
<html>
    <head>
        <title>Menu principal</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="Stylesheets/main.css">
    </head>
    <body>
    <script src="../Control/Worker/handler.js"></script>
        <a href="../index.php">Menu principal</a>

        <div class="searchBarContainer">
        <input type="search" id="searchBarByNameOrFirstname">
        <button>o</button>
        </div>
        <label>Id actuel:<input id="currentNumWorkerDisplayer" type="number" readonly value="0"></label>
        <!-- Table that will contain informations about every affectation -->
        <table border="1">
            <tr class="workerHeaderRow">
                <th>Num Emp</th>
                <th>Civilite</th>
                <th>Nom</th>
                <th>Prenom</th>
                <th>Mail</th>
                <th>Poste</th>
                <th>Lieu</th>
            </tr>

            <?php
                include_once("../Models/table_helpers.php");
                TableHelper::PopulateTableElementWithDatabseData("EMPLOYE", "NumEmp", "workerRow");
            ?>
        </table>

        <button onclick="AddWorker()">Ajouter</button>
        <button onclick="EditWorker()">Modifier</button>
        <button onclick="RemoveWorker()">Supprimer</button>

        <!-- Form that will be shown when adding or editing an entry -->
        <form onsubmit="SubmitForm()" method="post" id="workerForm" hidden>
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

        <div id="searchResults">

        </div>
    </body>
</html>