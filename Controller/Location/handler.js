/**
 * Global data tracker to be used later for
 * the form submitting, current mode depends
 * on its value
 */
var gLocationDataTracker = {
    id         : 0,
    isEditMode : false
};

/**
 * Updates gLocationDataTracker to the current
 * selected <td> row
 */
function UpdateDataTracker(id, mode)
{
    // Updating the global data tracker for later use
    var locationTableRows   = document.getElementsByClassName("location-table-row");
    gLocationDataTracker.id = (id < 0 ? 0 : id);
    gLocationDataTracker.isEditMode = mode;

    // Resetting every rows' style
    for (var i = 0 ; i < locationTableRows.length ; i++) {
        locationTableRows[i].style.backgroundColor = "";
        locationTableRows[i].style.color = "";
    }

    // Highlighing the lastly clicked row
    for (var i = 0 ; i < locationTableRows.length ; i++) {
        var columnsInRow = locationTableRows[i].querySelectorAll("td");

        if (columnsInRow[0].innerText == gLocationDataTracker.id) {
            locationTableRows[i].style.backgroundColor = "rgb(65, 65, 65)";
            locationTableRows[i].style.color = "white";
            break;
        }
    }
}

/**
 * Shows the location form and reset its values
 */
function AddLocation()
{
    var locationForm          = document.getElementById("location-main-form");
    var locationDesignField   = document.getElementById("form-location-design");
    var locationProvinceField = document.getElementById("form-location-province");
    
    UpdateDataTracker(-1, false);
    DisplayFormDialog();

    locationForm.hidden         = false;
    locationDesignField.value   = "";
    locationProvinceField.value = "";
}

/**
 * Shows the location form and load data from the tables
 * to edit later
 */
function EditLocation()
{
    var locationDesignField   = document.getElementById("form-location-design");
    var locationProvinceField = document.getElementById("form-location-province");
    var locationTableRows     = document.getElementsByClassName("location-table-row");

    // No valid data was selected
    if (gLocationDataTracker.id <= 0) {
        alert("Séléctionnez un lieu valide.");
        return;
    }

    gLocationDataTracker.isEditMode = true;
    DisplayFormDialog();

    // Loading every data from the correct row into the form
    for (var i = 0 ; i < locationTableRows.length ; i++) {
        var columnsInRow = locationTableRows[i].querySelectorAll("td");

        if (columnsInRow[0].innerText == gLocationDataTracker.id) {
            locationDesignField.value   = columnsInRow[1].innerText;
            locationProvinceField.value = columnsInRow[2].innerText;
            break;
        }
    }
}

/**
 * Is just a connection between the page and the relevant PHP code
 */
function RemoveLocation()
{
    if (gLocationDataTracker.id <= 0) {
        alert("Séléctionnez un lieu valide.");
        return;
    }

    SendXMLHttpRequest(gLocationDataTracker, "../Controller/Location/remove.php");
    location.reload();
}

/*
* Send the form to PHP code, in which it will decide
* whether to add or update an entry 
*/
function SubmitForm()
{
    var locationDesignField   = document.getElementById("form-location-design");
    var locationProvinceField = document.getElementById("form-location-province");

    // If some value in the form is empty, then we refuse to submit it
    if (locationDesignField.value == "" || locationProvinceField.value == "")
        return;

    var dataToSend = {
        IDLieu   : gLocationDataTracker.id,
        editMode : gLocationDataTracker.isEditMode,
        Design   : locationDesignField.value,
        Province : locationProvinceField.value
    };

    SendXMLHttpRequest(dataToSend, "../Controller/Location/form_submit.php");
    location.reload();
}