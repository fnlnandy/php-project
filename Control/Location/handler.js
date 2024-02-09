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
    var displayer = document.getElementById("currentNumLocationDisplayer");

    gLocationDataTracker.id = (id < 0 ? 0 : id);
    gLocationDataTracker.isEditMode = mode;
    displayer.value = gLocationDataTracker.id;

    console.log(id);
    console.log(mode);
}

function AddLocation()
{
    var locationForm = document.getElementById("locationForm");
    var locationDesignField = document.getElementById("formLocationDesign");
    var locationProvinceField = document.getElementById("formLocationProvince");
    
    locationForm.hidden = false;
    locationDesignField.value = "";
    locationProvinceField.value = "";
}

function EditLocation()
{
    var locationForm = document.getElementById("locationForm");
    var locationDesignField = document.getElementById("formLocationDesign");
    var locationProvinceField = document.getElementById("formLocationProvince");
    var locationTableRows = document.getElementsByClassName("locationRow");

    if (gLocationDataTracker.id <= 0) {
        alert("Séléctionnez un lieu valide.");
        return;
    }

    gLocationDataTracker.isEditMode = true;
    locationForm.hidden = false;

    for (var i = 0 ; i < locationTableRows.length ; i++) {
        var columnsInRow = locationTableRows[i].querySelectorAll("td");

        if (columnsInRow[0].innerText == gLocationDataTracker.id) {
            locationDesignField.value = columnsInRow[1].innerText;
            locationProvinceField.value = columnsInRow[2].innerText;
            break;
        }
    }
}

function RemoveLocation()
{
    SendXMLHttpRequest(gLocationDataTracker, "../Control/Location/remove.php");
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