/**
 * Global data tracker to be used later for
 * the form submitting, current mode depends
 * on its value
 */
var gDataTracker = {
    id: 0,
    isEditMode: false
};

/**
 * Sends a Request to a specific file,
 * response is just logged, but we rarely
 * are able to use that response
 */
function SendXMLHttpRequest(dataToSend, dest) {
    var req = new XMLHttpRequest();

    req.open("POST", dest);              // We prepare the destination file
    req.setRequestHeader("Content-type", "application/json") // We set the header for the data we'll send
    req.onreadystatechange = function () {
        if (req.readyState == 4 && req.status === 200) {
            console.log(req.responseText);
        }    
    };
    req.send(JSON.stringify(dataToSend));// We send the data in JSON Format
}

/**
 * Updates gDataTracker to the current
 * selected <td> row
 */
function UpdateDataTracker(id, mode)
{
    var displayer = document.getElementById("currentNumAffectDisplayer");

    gDataTracker.id = (id < 0 ? 0 : id);
    gDataTracker.mode = mode;
    displayer.value = gDataTracker.id;

    console.log(id);
    console.log(mode);
}

/**
 * Removes an entry using gDataTracker's data,
 * remove.php handles all edge cases
 */
function RemoveAffectationEntry()
{
    SendXMLHttpRequest(gDataTracker, "../Control/Affectation/remove.php");
}

/**
 * Switches current mode to addMode, handles
 * showing the form to add a new entry
 */
function AddAffectation()
{
    var form = document.getElementById("affectationForm");
    var numEmpField = document.getElementById("formNumEmp");
    var ancienLieuField = document.getElementById("formAncienLieu");
    var nouveauLieuField = document.getElementById("formNouveauLieu");
    var dateAffectField = document.getElementById("formDateAffect");
    var datePriseServiceField = document.getElementById("formPriseService");

    UpdateDataTracker(-1, false);
    form.hidden = false;
    numEmpField.value = "";
    ancienLieuField.value = "";
    nouveauLieuField.value = "";
    dateAffectField.value = "";
    datePriseServiceField.value = "";
}

/**
 * Switches the current mode to edit mode, fills the form
 * with the current selected row's data
 */
function EditAffectation()
{
    var form = document.getElementById("affectationForm");
    var numEmpField = document.getElementById("formNumEmp");
    var ancienLieuField = document.getElementById("formAncienLieu");
    var nouveauLieuField = document.getElementById("formNouveauLieu");
    var dateAffectField = document.getElementById("formDateAffect");
    var datePriseServiceField = document.getElementById("formPriseService");
    var tableRows = document.getElementsByClassName("affectationRow");

    if (gDataTracker.id <= 0 || gDataTracker.id > tableRows.length) {
        alert("Séléctionnez une affectation valide.");
        return;
    }

    gDataTracker.isEditMode = true;
    form.hidden = false;

    var columnsOnRow = tableRows[gDataTracker.id - 1].querySelectorAll("td");
    console.log(columnsOnRow);
    numEmpField.value = columnsOnRow[1].innerText;
    ancienLieuField.value = columnsOnRow[2].innerText;
    nouveauLieuField.value = columnsOnRow[3].innerText;
    dateAffectField.value = columnsOnRow[4].innerText;
    datePriseServiceField.value = columnsOnRow[5].innerText;
}

/*
* Send the form to PHP code, in which it will decide
* whether to add or update an entry 
*/
function SubmitForm()
{
    var numEmpField = document.getElementById("formNumEmp");
    var ancienLieuField = document.getElementById("formAncienLieu");
    var nouveauLieuField = document.getElementById("formNouveauLieu");
    var dateAffectField = document.getElementById("formDateAffect");
    var datePriseServiceField = document.getElementById("formPriseService");

    // If some value in the form is empty, then we refuse to submit it
    if (numEmpField.value == "" || ancienLieuField.value == "" || nouveauLieuField.value == ""
        || dateAffectField.value == "" || datePriseServiceField.value == "") {
            return;
    }
    else {
    var formData = {
        numAffect: gDataTracker.id,
        editMode: gDataTracker.isEditMode,
        numEmp: numEmpField.value,
        ancienLieu: ancienLieuField.value,
        nouveauLieu: nouveauLieuField.value,
        dateAffect: dateAffectField.value,
        datePriseService: datePriseServiceField.value
    };
    console.log(formData);
    }
    
    SendXMLHttpRequest(formData, "../Control/Affectation/form_submit.php");
}
