<!DOCTYPE html>
<html>
    <head>
        <title>Menu principal</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="Stylesheets/main.css">
    </head>
    <body>
    <script src="../Control/Location/handler.js"></script>
        <a href="../index.php">Menu principal</a>

        <!-- Table that will contain informations about every affectation -->
        <table border="1">
            <tr class="locationHeaderRow">
                <th>ID Lieu</th>
                <th>Design</th>
                <th>Province</th>
            </tr>

            <?php
                include_once("../Models/table_helpers.php");
                TableHelper::PopulateTableElementWithDatabseData("LIEU", "IDLieu", "locationRow");
            ?>
        </table>

        <button onclick="AddLocation()">Ajouter</button>
        <button onclick="EditLocation()">Modifier</button>
        <button onclick="RemoveLocation()">Supprimer</button>

        <!-- Form that will be shown when adding or editing an entry -->
        <form onsubmit="SubmitForm()" method="post" id="locationForm" hidden>
                <label>Design: <input id="formLocationDesign" name="formLocationDesign" type="text" required>        <br></label>
                <label>Province: <input id="formLocationProvince" name="formLocationProvince" type="text" required>       <br></label>
                <input type="submit" value="Confirmer">
                <input type="reset" value="Réinitaliser">
        </form>
    </body>
</html>