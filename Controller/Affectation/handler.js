window.onload = () => {
    // Getting the current date formatted as yyyy-mm-dd
    var currentDate   = new Date();
    var year          = currentDate.getFullYear();
    var month         = (currentDate.getMonth() + 1).toString().padStart(2, '0');
    var day           = currentDate.getDate().toString().padStart(2, '0');
    var formattedDate = year + '-' + month + '-' + day;
    var dateStart     = document.getElementById("search-bar-date-begin");
    var dateEnd       = document.getElementById("search-bar-date-end");

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
    id         : 0,
    isEditMode : false
};

/**
 * Updates gAffectationDataTracker to the current
 * selected <td> row
 */
function UpdateDataTracker(id, mode)
{
    // Updating the global data tracker
    var affectationTableRows   = document.getElementsByClassName("affectation-table-row");
    gAffectationDataTracker.id = (id < 0 ? 0 : id);
    gAffectationDataTracker.isEditMode = mode;

    // Resetting the styles of the rows
    for (var i = 0 ; i < affectationTableRows.length ; i++) {
        affectationTableRows[i].style.backgroundColor = "";
    }

    // Highlighting the lastly clicked row
    for (var i = 0 ; i < affectationTableRows.length ; i++) {
        var columnsInRow = affectationTableRows[i].querySelectorAll("td");

        if (columnsInRow.length > 0 && columnsInRow[0].innerText == gAffectationDataTracker.id) {
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
    if (gAffectationDataTracker.id <= 0) {
        alert("Séléctionnez une affectation valide.");
        return;
    }

    SendXMLHttpRequest(gAffectationDataTracker, "../Controller/Affectation/remove.php");
    location.reload();
}

/**
 * Switches current mode to addMode, handles
 * showing the form to add a new entry
 */
function AddAffectation()
{
    var numEmpField     = document.getElementById("formNumEmp");
    var infoEmpField    = document.getElementById("formInfoEmp");
    var oldLocField     = document.getElementById("formAncienLieu");
    var infoOldLocField = document.getElementById("formInfoAncienLieu");
    var newLocField     = document.getElementById("formNouveauLieu");
    var infoNewLocField = document.getElementById("formInfoNouveauLieu");

    UpdateDataTracker(-1, false);
    DisplayFormDialog();

    // Resetting everything to their original values
    numEmpField.selectedIndex     = 0;
    infoEmpField.selectedIndex    = 0;
    oldLocField.selectedIndex     = 0;
    infoOldLocField.selectedIndex = 0;
    newLocField.selectedIndex     = 0;
    infoNewLocField.selectedIndex = 0;

    window.onload(null); // Reloading the current dates
}

/**
 * Switches the current mode to edit mode, fills the form
 * with the current selected row's data
 */
function EditAffectation()
{
    var numEmpField           = document.getElementById("formNumEmp");
    var infoEmpField          = document.getElementById("formInfoEmp");
    var oldLocField           = document.getElementById("formAncienLieu");
    var infoOldLocField       = document.getElementById("formInfoAncienLieu");
    var newLocField           = document.getElementById("formNouveauLieu");
    var infoNewLocField       = document.getElementById("formInfoNouveauLieu");
    var dateAffectField       = document.getElementById("formDateAffect");
    var datePriseServiceField = document.getElementById("formPriseService");
    var tableRows             = document.getElementsByClassName("affectation-table-row");

    // An invalid data was selected, thus we cannot load anything into the form
    if (gAffectationDataTracker.id <= 0) {
        alert("Séléctionnez une affectation valide.");
        return;
    }

    gAffectationDataTracker.isEditMode = true;
    DisplayFormDialog();

    // Loading every value from the table to the form
    for (var i = 0 ; i < tableRows.length ; i++) {
        var columnsInRow = tableRows[i].querySelectorAll("td");

        // Loading every value from the <table> into the form
        if (columnsInRow.length > 0 && columnsInRow[0].innerText == gAffectationDataTracker.id) {
            var employeeIndex = GetSelectionIndexForSelectedName(columnsInRow[1].innerText, "workerNameFirstNameMatch");
            var oldLocIndex   = GetSelectionIndexForSelectedName(columnsInRow[2].innerText, "locationIdDesignMatch");
            var newLocIndex   = GetSelectionIndexForSelectedName(columnsInRow[3].innerText, "locationIdDesignMatch");

            numEmpField.selectedIndex     = employeeIndex;
            infoEmpField.selectedIndex    = employeeIndex;
            oldLocField.selectedIndex     = oldLocIndex;
            infoOldLocField.selectedIndex = oldLocIndex;
            newLocField.selectedIndex     = newLocIndex;
            infoNewLocField.selectedIndex = newLocIndex;
            dateAffectField.value         = columnsInRow[4].innerText;
            datePriseServiceField.value   = columnsInRow[5].innerText;

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
    var numEmpField           = document.getElementById("formNumEmp");
    var oldLocField           = document.getElementById("formAncienLieu");
    var newLocField           = document.getElementById("formNouveauLieu");
    var dateAffectField       = document.getElementById("formDateAffect");
    var datePriseServiceField = document.getElementById("formPriseService");

    // If some value in the form is empty, then we refuse to submit it
    if (numEmpField?.innerText == "" || oldLocField?.innerText == "" ||
        newLocField?.innerText == "" || dateAffectField.value == ""  || datePriseServiceField.value == "") {
            return;
    }
    else {
        // Loading every form field into a container that will be parsed on the server side
        var formData = {
            numAffect        : gAffectationDataTracker.id,
            editMode         : gAffectationDataTracker.isEditMode,
            numEmp           : GetCurrentSelectOptionValue(numEmpField, numEmpField.selectedIndex),
            ancienLieu       : GetCurrentSelectOptionValue(oldLocField, oldLocField.selectedIndex),
            nouveauLieu      : GetCurrentSelectOptionValue(newLocField, newLocField.selectedIndex),
            dateAffect       : dateAffectField.value,
            datePriseService : datePriseServiceField.value
        };

        // If the old location and the new location are the same, then it's not an affectation
        // at all
        if (formData.ancienLieu == formData.nouveauLieu) {
            alert("L'ancien lieu et le nouveau lieu ne peuvent pas être identiques.");
            return;
        }
        
        console.log(formData);
        SendXMLHttpRequest(formData, "../Controller/Affectation/form_submit.php");
        location.reload();
    }
}

/**
 * Tries to generate a PDF file out
 * of the selected entry, only is a connection
 * between the page and the PHP code to be honest
 */
function TryGeneratePDF()
{
    if (gAffectationDataTracker.id <= 0) {
        alert("Selectionnez une affectation valide.");
        return;
    }

    SendXMLHttpRequest(gAffectationDataTracker, "../Controller/Affectation/pdf_generator.php");
}