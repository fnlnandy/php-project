<!DOCTYPE html>
<html>
    <!-- PAGE HEADERS -->
    <head>
        <title>Page des lieux</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Page des lieux">
        <link rel="icon" href="" type="image/x-icon">
        <link rel="stylesheet" href="Stylesheets/main.css">
    </head>
    <body>
        <!-- INCLUDES -->
        <script src="../Controller/main_handler.js"></script>
        <script src="../Controller/Location/handler.js"></script>
        
        <!-- NAVIGATION MENU -->
        <nav class="top-navigation-bar">
            <a href="../index.php">Menu principal</a>
            <a href="affectation_page.php">Affectations</a>
            <a href="worker_page.php">Employés</a>
        </nav>

        <!-- TABLE LISTING DATA -->
        <div class="table-list-outer-container space-top-element">
            <table class="table-list-inner-container">
                <tr class="table-header-row">
                    <th>ID du lieu</th>
                    <th>Designation</th>
                    <th>Province</th>
                </tr>

                <?php
                    include_once("../Models/table_helpers.php");
                    TableHelper::PopulateTableElementWithDatabseData("LIEU", "IDLieu", "location-table-row");
                ?>
            </table>
        </div>

        <!-- CRUD OPERATIONS BUTTONS -->
        <div class="force-center-elements space-top-element">
            <span class="crud-actions-container">
                <button onclick="AddLocation()">Ajouter</button>
                <button onclick="EditLocation()">Modifier</button>
                <button onclick="RemoveLocation()">Supprimer</button>
            </span>
        </div>

        <!-- DATA FILLING FORM -->
        <dialog id="form-dialog-container">
        <p onclick="CloseFormDialog()">x</p>
            <div class="force-center-elements">
                <span class="form-inner-container">
                    <form onsubmit="SubmitForm()" method="post" id="location-main-form">
                            <h3 class="form-title">Formulaire pour un lieu</h3>
                            <label>Designation: <input id="form-location-design" name="form-location-design" type="text" maxlength="30" required>        <br></label>
                            <label>Province: <input id="form-location-province" name="form-location-province" type="text" maxlength="30" required>       <br></label>
                            <input type="submit" value="Confirmer">
                            <input type="reset" value="Réinitaliser">
                    </form>
                </span>
            </div>
        </dialog>
    </body>
</html>