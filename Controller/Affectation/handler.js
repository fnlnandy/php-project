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

    document.getElementById("form-affectation-date-affect").value = formattedDate;
    document.getElementById("form-affectation-date-ps").value = formattedDate;
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
    var affectationTableRows   = document.getElementsByClassName("inner-table-row");
    gAffectationDataTracker.id = (id < 0 ? 0 : id);
    gAffectationDataTracker.isEditMode = mode;

    // Resetting the styles of the rows
    for (var i = 0 ; i < affectationTableRows.length ; i++) {
        affectationTableRows[i].style.backgroundColor = "";
        affectationTableRows[i].style.color = "";
    }

    // Highlighting the lastly clicked row
    for (var i = 0 ; i < affectationTableRows.length ; i++) {
        var columnsInRow = affectationTableRows[i].querySelectorAll("td");

        if (columnsInRow.length > 0 && columnsInRow[0].innerText == gAffectationDataTracker.id) {
            affectationTableRows[i].style.backgroundColor = "rgb(65, 65, 65)";
            affectationTableRows[i].style.color = "white";
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
    ReloadPageWithTimeStamp();
}

/**
 * Switches current mode to addMode, handles
 * showing the form to add a new entry
 */
function AddAffectation()
{
    var numEmpField     = document.getElementById("form-affectation-employee-num");
    var infoEmpField    = document.getElementById("form-affectation-info-employee");
    var oldLocField     = document.getElementById("form-affectation-old-location");
    var infoOldLocField = document.getElementById("form-affectation-info-old-location");
    var newLocField     = document.getElementById("form-affectation-new-location");
    var infoNewLocField = document.getElementById("form-affectation-info-new-location");

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
    var numEmpField           = document.getElementById("form-affectation-employee-num");
    var infoEmpField          = document.getElementById("form-affectation-info-employee");
    var oldLocField           = document.getElementById("form-affectation-old-location");
    var infoOldLocField       = document.getElementById("form-affectation-info-old-location");
    var newLocField           = document.getElementById("form-affectation-new-location");
    var infoNewLocField       = document.getElementById("form-affectation-info-new-location");
    var dateAffectField       = document.getElementById("form-affectation-date-affect");
    var datePriseServiceField = document.getElementById("form-affectation-date-ps");
    var tableRows             = document.getElementsByClassName("inner-table-row");

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
            var employeeIndex = GetSelectionIndexForSelectedName(columnsInRow[1].innerText, "worker-id-name-relation");
            var oldLocIndex   = GetSelectionIndexForSelectedName(columnsInRow[2].innerText, "location-id-name-relation");
            var newLocIndex   = GetSelectionIndexForSelectedName(columnsInRow[3].innerText, "location-id-name-relation");

            numEmpField.selectedIndex     = employeeIndex;
            infoEmpField.selectedIndex    = employeeIndex;
            oldLocField.selectedIndex     = oldLocIndex;
            infoOldLocField.selectedIndex = oldLocIndex;
            newLocField.selectedIndex     = newLocIndex;
            infoNewLocField.selectedIndex = newLocIndex;
            dateAffectField.value         = FormatDMYDate(columnsInRow[4].innerText);
            datePriseServiceField.value   = FormatDMYDate(columnsInRow[5].innerText);

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
    var numEmpField           = document.getElementById("form-affectation-employee-num");
    var oldLocField           = document.getElementById("form-affectation-old-location");
    var newLocField           = document.getElementById("form-affectation-new-location");
    var dateAffectField       = document.getElementById("form-affectation-date-affect");
    var datePriseServiceField = document.getElementById("form-affectation-date-ps");
    var notifyEmployeeField   = document.getElementById("form-notify-employee");
    var affectDateObj         = new Date(dateAffectField.value);
    var serviceDateObj        = new Date(datePriseServiceField.value);

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
            datePriseService : datePriseServiceField.value,
            notifyEmployee   : (notifyEmployeeField.checked ? 1 : 0)
        };

        // If the old location and the new location are the same, then it's not an affectation
        // at all
        if (formData.ancienLieu == formData.nouveauLieu) {
            alert("L'ancien lieu et le nouveau lieu ne peuvent pas être identiques.");
            return;
        }

        if (affectDateObj > serviceDateObj) {
            alert("L'employé ne peut pas prendre service avant son affectation.");
            return;
        }
        
        console.log(formData);
        SendXMLHttpRequest(formData, "../Controller/Affectation/form_submit.php");
        ReloadPageWithTimeStamp();
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

    DisplayFormDialog("pdf-file-dialog");
}

function SubmitPDFForm()
{
    var pdfName = document.getElementById("pdf-name-field");

    if (pdfName.value.trim() == "") {
        return;
    }

    var dataToSend = {
        id: gAffectationDataTracker.id,
        realPath: pdfName.value
    };

    console.log(dataToSend);
    SendXMLHttpRequest(dataToSend, "../Controller/Affectation/pdf_generator.php", "Le dossier 'PDFs' n'existe pas ou est protégé en écriture.");
}