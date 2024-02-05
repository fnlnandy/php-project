<!DOCTYPE html>
<html>
    <head>
        <title>Menu principal</title>
        <meta charset="utf-8">
    </head>
    <body>
        <script src="../Control/Affectation/handler.js"></script>

        <a href="../index.php">Menu principal</a>

        <!-- Table that will contain informations about every affectation -->
        <table border="1">
            <tr>
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

        <!-- Form that will be shown when adding or editing an entry -->
        <form method="post" action="../Control/Affectation/form_submit.php" id="affectationForm">
                <input type="number" value="-1" id="formNumAffect" name="formNumAffect">
                <label>Numéro Employé: <input id="formNumEmp" name="formNumEmp" type="number" required>        <br></label>
                <label>Ancien Lieu: <input id="formAncienLieu" name="formAncienLieu" type="number" required>       <br></label>
                <label>Nouveau Lieu: <input id="formNouveauLieu" name="formNouveauLieu" type="number" required>     <br></label>
                <label>Date Affect: <input id="formDateAffect" name="formDateAffect" type="date" required>         <br></label>
                <label>Date Prise Service: <input id="formPriseService" name="formPriseService" type="date" required><br></label>
                <input type="submit" value="Confirmer">
        </form>

        
    </body>
</html>