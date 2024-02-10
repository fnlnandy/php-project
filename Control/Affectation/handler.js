/**
 * Global data tracker to be used later for
 * the form submitting, current mode depends
 * on its value
 */
var gAffectationDataTracker = {
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
    location.reload();
}

/**
 * Updates gAffectationDataTracker to the current
 * selected <td> row
 */
function UpdateDataTracker(id, mode)
{
    var affectationTableRows = document.getElementsByClassName("affectationRow");
    gAffectationDataTracker.id = (id < 0 ? 0 : id);
    gAffectationDataTracker.isEditMode = mode;

    for (var i = 0 ; i < affectationTableRows.length ; i++) {
        affectationTableRows[i].style.backgroundColor = "";
    }

    for (var i = 0 ; i < affectationTableRows.length ; i++) {
        var columnsInRow = affectationTableRows[i].querySelectorAll("td");

        if (columnsInRow[0].innerText == gAffectationDataTracker.id) {
            affectationTableRows[i].style.backgroundColor = "beige";
            break;
        }
    }
}

/**
 * Removes an entry using gAffectationDataTracker's data,
 * remove.php handles all edge cases
 */
function RemoveAffectationEntry()
{
    SendXMLHttpRequest(gAffectationDataTracker, "../Control/Affectation/remove.php");
    location.reload();
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

    if (gAffectationDataTracker.id <= 0) {
        alert("Séléctionnez une affectation valide.");
        return;
    }

    gAffectationDataTracker.isEditMode = true;
    form.hidden = false;

    for (var i = 0 ; i < tableRows.length ; i++) {
        var columnsInRow = tableRows[i].querySelectorAll("td");

        if (columnsInRow[0].innerText == gAffectationDataTracker.id) {
            numEmpField.value           = columnsInRow[1].innerText;
            ancienLieuField.value       = columnsInRow[2].innerText;
            nouveauLieuField.value      = columnsInRow[3].innerText;
            dateAffectField.value       = columnsInRow[4].innerText;
            datePriseServiceField.value = columnsInRow[5].innerText;
            break;
        }
    }
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
        numAffect: gAffectationDataTracker.id,
        editMode: gAffectationDataTracker.isEditMode,
        numEmp: numEmpField.value,
        ancienLieu: ancienLieuField.value,
        nouveauLieu: nouveauLieuField.value,
        dateAffect: dateAffectField.value,
        datePriseService: datePriseServiceField.value
    };
    console.log(formData);
    }
    
    SendXMLHttpRequest(formData, "../Control/Affectation/form_submit.php");
    location.reload();
}
