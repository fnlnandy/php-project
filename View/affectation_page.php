<!DOCTYPE html>
<html>
    <head>
        <title>Page des affectations</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Page des affectations">
        <link rel="icon" href="" type="image/x-icon">
        <link rel="stylesheet" href="Stylesheets/main.css">
    </head>
    <body>
    <script src="../Control/Affectation/handler.js"></script>
        <nav class="topNavigationBar"><a href="../index.php">Menu principal</a></nav>

        <div class="searchBarAreaContainer">
            <form id="twoDatesForm" method="get">
                <label>Début: <input type="date" id="dateStart" name="dateStart"></label>
                <label>Fin: <input type="date" id="dateEnd" name="dateEnd"></label>
                <input type="submit" value="Afficher les affectations">
            </form>
        </div>

        <!-- Table that will contain informations about every affectation -->
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

        <div class="centerElementsFlex">
            <span class="actionButtonsContainer">
                <button onclick="AddAffectation()">Ajouter</button>
                <button onclick="EditAffectation()">Modifier</button>
                <button onclick="RemoveAffectationEntry()">Supprimer</button>
                <button onclick="TryGeneratePDF()">Generer un PDF</button>
            </span>
        </div>

        <!-- Form that will be shown when adding or editing an entry -->
        <dialog id="formDialog">
            <p onclick="CloseFormDialog()">x</p>
            <div class="centerElementsFlex">
                <span class="formContainer">
                    <form onsubmit="SubmitForm()" method="post" id="affectationForm">
                            <h3 class="formTitle">Formulaire pour une affectation</h3>
                            <label>Numéro Employé: <input id="formNumEmp" name="formNumEmp" type="number" required maxlength="10">        <br></label>
                            <label>Ancien Lieu: <input id="formAncienLieu" name="formAncienLieu" type="number" required>       <br></label>
                            <label>Nouveau Lieu: <input id="formNouveauLieu" name="formNouveauLieu" type="number" required>     <br></label>
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