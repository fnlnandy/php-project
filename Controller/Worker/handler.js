window.onload = () => {
    HighlightCurrentWorker();
}

/**
 * Global data tracker to be used later for
 * the form submitting, current mode depends
 * on its value
 */
var gWorkerDataTracker = {
    id         : 0,
    isEditMode : false
};

/**
 * Updates gWorkerDataTracker to the current
 * selected <td> row
 */
function UpdateDataTracker(id, mode)
{
    var workerTableRows    = document.getElementsByClassName("worker-table-row");
    var affectCurrWorkerId = document.getElementById("worker-id");

    // Update the global data tracker for later use
    gWorkerDataTracker.id         = (id < 0 ? 0 : id);
    gWorkerDataTracker.isEditMode = mode;
    affectCurrWorkerId.value      = gWorkerDataTracker.id;

    // Resetting every rows' style
    for (var i = 0 ; i < workerTableRows.length ; i++) {
        workerTableRows[i].style.backgroundColor = "";
        workerTableRows[i].style.color = "";
    }

    // Highlighting the lastly clicked row
    for (var i = 0 ; i < workerTableRows.length ; i++) {
        var columnsInRow = workerTableRows[i].querySelectorAll("td");

        if (columnsInRow[0].innerText == gWorkerDataTracker.id) {
            workerTableRows[i].style.backgroundColor = "rgb(65, 65, 65)";
            workerTableRows[i].style.color = "white";
            break;
        }
    }
}

/**
 * Shows the worker form and resets
 * all of its values
 */
function AddWorker()
{
    var workerCivilityField     = document.getElementById("form-worker-civility");
    var workerNameField         = document.getElementById("form-worker-name");
    var workerFirstnameField    = document.getElementById("form-worker-first-name");
    var workerMailField         = document.getElementById("form-worker-mail");
    var workerPostField         = document.getElementById("form-worker-post");
    var workerLocationField     = document.getElementById("form-worker-location");
    var infoWorkerLocationField = document.getElementById("form-info-worker-location");

    UpdateAffectedCheck(-1, false);
    DisplayFormDialog();

    // Resetting every field to their original value
    workerCivilityField.selectedIndex     = 0;
    workerNameField.value                 = "";
    workerFirstnameField.value            = "";
    workerMailField.value                 = "";
    workerPostField.value                 = "";
    workerLocationField.selectedIndex     = 0;
    infoWorkerLocationField.selectedIndex = 0;
}

/**
 * Shows the worker form and loads data
 * from the table into the form
 */
function EditWorker()
{
    var workerCivilityField     = document.getElementById("form-worker-civility");
    var workerNameField         = document.getElementById("form-worker-name");
    var workerFirstnameField    = document.getElementById("form-worker-first-name");
    var workerMailField         = document.getElementById("form-worker-mail");
    var workerPostField         = document.getElementById("form-worker-post");
    var workerLocationField     = document.getElementById("form-worker-location");
    var infoWorkerLocationField = document.getElementById("form-info-worker-location");
    var workerTableRows         = document.getElementsByClassName("worker-table-row");

    // No valid data/row was selected
    if (gWorkerDataTracker.id <= 0) {
        alert("Séléctionnez un employé valide.");
        return;
    }

    gWorkerDataTracker.isEditMode = true;
    DisplayFormDialog();

    // Loading the data from the table into the row
    for (var i = 0 ; i < workerTableRows.length ; i++) {
        var columnsInRow = workerTableRows[i].querySelectorAll("td");

        // Loading the data from the <table> element to the form
        if (columnsInRow[0].innerText == gWorkerDataTracker.id) {
            var selectedIndex = 0;
            var correctSelectionIndexForLocation = GetSelectionIndexForSelectedName(columnsInRow[6].innerText, "location-id-name-relation");
            
            // Selecting the correct option for the civility
            if (columnsInRow[1].innerText == "Mr")
                selectedIndex = 0;
            else if (columnsInRow[1].innerText == "Mlle")
                selectedIndex = 1;
            else if (columnsInRow[1].innerText == "Mme")
                selectedIndex = 2;

            workerCivilityField.selectedIndex     = selectedIndex;
            workerNameField.value                 = columnsInRow[2].innerText;
            workerFirstnameField.value            = columnsInRow[3].innerText;
            workerMailField.value                 = columnsInRow[4].innerText;
            workerPostField.value                 = columnsInRow[5].innerText;
            workerLocationField.selectedIndex     = correctSelectionIndexForLocation;
            infoWorkerLocationField.selectedIndex = correctSelectionIndexForLocation;
            break;
        }
    }
}

/**
 * Only acts as connector between the page and the PHP code
 */
function RemoveWorker()
{
    if (gWorkerDataTracker.id <= 0) {
        alert("Séléctionnez un employé valide.");
        return;
    }
    SendXMLHttpRequest(gWorkerDataTracker, "../Controller/Worker/remove.php");
}

/*
* Sends the form to PHP code, in which it will decide
* whether to add or update an entry 
*/
function SubmitForm()
{
    var workerCivilityField  = document.getElementById("form-worker-civility")
    var workerNameField      = document.getElementById("form-worker-name");
    var workerFirstnameField = document.getElementById("form-worker-first-name");
    var workerMailField      = document.getElementById("form-worker-mail");
    var workerPostField      = document.getElementById("form-worker-post");
    var workerLocationField  = document.getElementById("form-worker-location");

    console.log(dataToSend);
    // If some value in the form is empty, then we refuse to submit it
    if (workerNameField.value == "" || workerFirstnameField.value == "" ||
        workerMailField.value == "" || workerPostField.value == "" || workerLocationField?.innerText == "")
        return;

    var dataToSend = {
        NumEmp   : gWorkerDataTracker.id,
        editMode : gWorkerDataTracker.isEditMode,
        Civilite : workerCivilityField.options[workerCivilityField.selectedIndex].innerText,
        Nom      : workerNameField.value,
        Prenom   : workerFirstnameField.value,
        Mail     : workerMailField.value,
        Poste    : workerPostField.value,
        Lieu     : GetCurrentSelectOptionValue(workerLocationField, workerLocationField.selectedIndex),
    };

    SendXMLHttpRequest(dataToSend, "../Controller/Worker/form_submit.php");
}

/**
 * Updates the 'Unaffected' checkbox's state
 */
function UpdateUnaffectedCheck()
{
    var affectedCBox   = document.getElementById("search-bar-show-affected");
    var unaffectedCBox = document.getElementById("search-bar-show-unaffected");

    if (affectedCBox.checked == true) {
        unaffectedCBox.checked = false;
        return;
    }
}

/**
 * Updates the 'Affected' checkbox's state
 */
function UpdateAffectedCheck()
{
    var affectedCBox   = document.getElementById("search-bar-show-affected");
    var unaffectedCBox = document.getElementById("search-bar-show-unaffected");

    if (unaffectedCBox.checked == true) {
        affectedCBox.checked = false;
        return;
    }
}

/**
 * Highlight the current worker when displaying his affectation
 * data
 */
function HighlightCurrentWorker()
{
    var url = new URL(window.location.href);
    var workerIdValue = url.searchParams.get('worker-id');

    if (workerIdValue != null) {
        UpdateDataTracker(workerIdValue, false);
    }
}
