<!DOCTYPE html>
<html>
    <!-- PAGE HEADERS -->
    <head>
        <title>Page des lieux</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Page des lieux">
        <meta http-equiv="Cache-control" content="no-cache, no-store, must-revalidate">
        <meta http-equiv="Expires" content="0">
        <meta http-equiv="Pragma" content="no-cache">
        <link rel="icon" href="" type="image/x-icon">
        <link rel="stylesheet" href="Stylesheets/main.css">
    </head>
    <body>
        <!-- INCLUDES -->
        
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
        </header>

        <!-- TABLE LISTING DATA -->
        <main class="page-main-content">
            <div class="table-list-outer-container space-top-element">
                <div class="table-list-padder">
                    <table class="table-list-inner-container">
                        <tr class="table-header-row">
                            <th>ID du lieu</th>
                            <th>Designation</th>
                            <th>Province</th>
                        </tr>

                        <?php
                            include_once("../Models/table_helpers.php");
                            TableHelper::PopulateTableElementWithDatabseData("LIEU", "IDLieu", "inner-table-row");
                        ?>
                    </table>
                </div>
            </div>

            <!-- CRUD OPERATIONS BUTTONS -->
            <div id="crud-actions-movable" class="crud-actions-wrapper">
                <span class="crud-actions-container">
                    <button class="button-highlight-green" onclick="AddLocation()">Ajouter</button>
                    <button class="button-highlight-blue" onclick="EditLocation()">Modifier</button>
                    <button class="button-highlight-red" onclick="RemoveLocation()">Supprimer</button>
                </span>
            </div>
        </main>

        <!-- DATA FILLING FORM -->
        <dialog id="form-dialog-container">
        <p onclick="CloseFormDialog()" class="form-quit-button button-highlight-red"></p>
            <div class="force-center-elements">
                <span class="form-inner-container">
                    <form onsubmit="SubmitForm()" method="post" id="location-main-form">
                            <h3 id="form-title">Formulaire pour un lieu</h3>
                            <div class="form-field-container">
                                <label class="form-field-label">Designation: <input class="form-field-value" id="form-location-design" name="form-location-design" type="text" maxlength="30" pattern="[a-zA-Z ]+" required>        <br></label>
                            </div>
                            <div class="form-field-container">
                                <label class="form-field-label">Province: <input class="form-field-value" id="form-location-province" name="form-location-province" type="text" maxlength="30" pattern="[a-zA-Z ]+" required>       <br></label>
                            </div>
                            <input class="button-highlight-green" type="submit" value="Confirmer">
                            <input class="button-highlight-red" type="reset" value="Réinitaliser">
                    </form>
                </span>
            </div>
        </dialog>

        <!-- JAVASCRIPT SCRIPTS -->
        <script src="../Controller/main_handler.js"></script>
        <script src="../Controller/Location/handler.js"></script>
    </body>
</html>