window.onload = () => {
    // Getting the current date formatted as yyyy-mm-dd
    var currentDate = new Date();
    var year = currentDate.getFullYear();
    var month = (currentDate.getMonth() + 1).toString().padStart(2, '0');
    var day = currentDate.getDate().toString().padStart(2, '0');
    var formattedDate = year + '-' + month + '-' + day;
    var dateStart = document.getElementById("dateStart");
    var dateEnd = document.getElementById("dateEnd");
    // Updating every 'date' elements to the current date
    if (dateStart.value === "") {
        dateStart.value = formattedDate;
    }
    if (dateEnd.value === "") {
        dateEnd.value = formattedDate;
    }
    document.getElementById("formDateAffect").value = formattedDate;
    document.getElementById("formPriseService").value = formattedDate;
}   

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
        console.log(req.responseText);
    };
    req.send(JSON.stringify(dataToSend));// We send the data in JSON Format
    location.reload(); // Force refresh to load data instantly
}

/**
 * Updates gAffectationDataTracker to the current
 * selected <td> row
 */
function UpdateDataTracker(id, mode)
{
    // Updating the global data tracker
    var affectationTableRows = document.getElementsByClassName("affectationRow");
    gAffectationDataTracker.id = (id < 0 ? 0 : id);
    gAffectationDataTracker.isEditMode = mode;

    // Resetting the styles of the rows
    for (var i = 0 ; i < affectationTableRows.length ; i++) {
        affectationTableRows[i].style.backgroundColor = "";
    }

    // Highlighting the lastly clicked row
    for (var i = 0 ; i < affectationTableRows.length ; i++) {
        var columnsInRow = affectationTableRows[i].querySelectorAll("td");

        if (columnsInRow[0].innerText == gAffectationDataTracker.id) {
            affectationTableRows[i].style.backgroundColor = "rgba(0, 0, 0, 0.2)";
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
 * 
 */
function CloseFormDialog()
{
    var formDialog = document.getElementById("formDialog");
    formDialog.style.opacity = 0;
    formDialog.addEventListener("transitionend", () => {
        formDialog.close();
    }, {once: true});
}

/**
 * 
 */
function DisplayFormDialog()
{
    var formDialog = document.getElementById("formDialog");
    formDialog.showModal(); 
    formDialog.style.opacity = 1;
    formDialog.style.top = 0;
    formDialog.style.left = 0;
    formDialog.addEventListener("transitionend", () => {}, { once: true });
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

    UpdateDataTracker(-1, false);
    DisplayFormDialog();
    numEmpField.value = "";
    ancienLieuField.value = "";
    nouveauLieuField.value = "";
    window.onload(null); // Reloading the current dates
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

    // An invalid data was selected, thus we cannot load anything into the form
    if (gAffectationDataTracker.id <= 0) {
        alert("Séléctionnez une affectation valide.");
        return;
    }

    gAffectationDataTracker.isEditMode = true;
    form.hidden = false;

    // Loading every value from the table to the form
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
    // Loading every form field into a container that will be parsed on the server side
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

/**
 * Tries to generate a PDF file out
 * of the selected entry, only is a connection
 * between the page and the PHP code to be honest
 */
function TryGeneratePDF()
{
    SendXMLHttpRequest(gAffectationDataTracker, "../Control/Affectation/pdf_generator.php");
}