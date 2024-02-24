/**
 * Global data tracker to be used later for
 * the form submitting, current mode depends
 * on its value
 */
var gLocationDataTracker = {
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
    location.reload(); // Force refresh to load data instantly
}

/**
 * Updates gLocationDataTracker to the current
 * selected <td> row
 */
function UpdateDataTracker(id, mode)
{
    // Updating the global data tracker for later use
    var locationTableRows = document.getElementsByClassName("locationRow");
    gLocationDataTracker.id = (id < 0 ? 0 : id);
    gLocationDataTracker.isEditMode = mode;

    // Resetting every rows' style
    for (var i = 0 ; i < locationTableRows.length ; i++) {
        locationTableRows[i].style.backgroundColor = "";
    }

    // Highlighing the lastly clicked row
    for (var i = 0 ; i < locationTableRows.length ; i++) {
        var columnsInRow = locationTableRows[i].querySelectorAll("td");

        if (columnsInRow[0].innerText == gLocationDataTracker.id) {
            locationTableRows[i].style.backgroundColor = "rgba(0, 0, 0, 0.2)";
            break;
        }
    }
}

/**
 * Shows the location form and reset its values
 */
function AddLocation()
{
    var locationForm = document.getElementById("locationForm");
    var locationDesignField = document.getElementById("formLocationDesign");
    var locationProvinceField = document.getElementById("formLocationProvince");
    
    UpdateDataTracker(-1, false);
    locationForm.hidden = false;
    locationDesignField.value = "";
    locationProvinceField.value = "";
}

/**
 * Shows the location form and load data from the tables
 * to edit later
 */
function EditLocation()
{
    var locationForm = document.getElementById("locationForm");
    var locationDesignField = document.getElementById("formLocationDesign");
    var locationProvinceField = document.getElementById("formLocationProvince");
    var locationTableRows = document.getElementsByClassName("locationRow");

    // No valid data was selected
    if (gLocationDataTracker.id <= 0) {
        alert("Séléctionnez un lieu valide.");
        return;
    }

    gLocationDataTracker.isEditMode = true;
    locationForm.hidden = false;

    // Loading every data from the correct row into the form
    for (var i = 0 ; i < locationTableRows.length ; i++) {
        var columnsInRow = locationTableRows[i].querySelectorAll("td");

        if (columnsInRow[0].innerText == gLocationDataTracker.id) {
            locationDesignField.value = columnsInRow[1].innerText;
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
    SendXMLHttpRequest(gLocationDataTracker, "../Control/Location/remove.php");
    location.reload();
}

/*
* Send the form to PHP code, in which it will decide
* whether to add or update an entry 
*/
function SubmitForm()
{
    var locationDesignField = document.getElementById("formLocationDesign");
    var locationProvinceField = document.getElementById("formLocationProvince");

    // If some value in the form is empty, then we refuse to submit it
    if (locationDesignField.value == "" || locationProvinceField.value == "")
        return;

    var dataToSend = {
        IDLieu: gLocationDataTracker.id,
        editMode: gLocationDataTracker.isEditMode,
        Design: locationDesignField.value,
        Province: locationProvinceField.value
    };

    SendXMLHttpRequest(dataToSend, "../Control/Location/form_submit.php");
    location.reload();
}