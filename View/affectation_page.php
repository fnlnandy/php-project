<!DOCTYPE html>
<html>
    <head>
        <title>Menu principal</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="Stylesheets/main.css">
    </head>
    <body>
    <script src="../Control/Affectation/handler.js"></script>
        <a href="../index.php">Menu principal</a>

        <label>Id actuel:<input id="currentNumAffectDisplayer" type="number" readonly value="0"></label>
        <!-- Table that will contain informations about every affectation -->
        <table border="1">
            <tr class="affectationHeaderRow">
                <th>Num Affect</th>
                <th>Num Emp</th>
                <th>Ancien Lieu</th>
                <th>Nouveau Lieu</th>
                <th>Date Affect</th>
                <th>Date Prise Service</th>
            </tr>

            <?php
            include_once("../Control/Affectation/helper.php");
            PopulateAffectationList(); // Should always be last
            ?>
        </table>

        <button onclick="AddAffectation()">Ajouter</button>
        <button onclick="EditAffectation()">Modifier</button>
        <button onclick="RemoveAffectationEntry()">Supprimer</button>

        <!-- Form that will be shown when adding or editing an entry -->
        <form onsubmit="SubmitForm()" method="post" id="affectationForm" hidden>
                <label>Numéro Employé: <input id="formNumEmp" name="formNumEmp" type="number" required>        <br></label>
                <label>Ancien Lieu: <input id="formAncienLieu" name="formAncienLieu" type="number" required>       <br></label>
                <label>Nouveau Lieu: <input id="formNouveauLieu" name="formNouveauLieu" type="number" required>     <br></label>
                <label>Date Affect: <input id="formDateAffect" name="formDateAffect" type="date" required>         <br></label>
                <label>Date Prise Service: <input id="formPriseService" name="formPriseService" type="date" required><br></label>
                <input type="submit" value="Confirmer">
                <input type="reset" value="Réinitaliser">
        </form>
    </body>
</html>