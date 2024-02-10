/**
 * Global data tracker to be used later for
 * the form submitting, current mode depends
 * on its value
 */
var gWorkerDataTracker = {
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
 * Updates gWorkerDataTracker to the current
 * selected <td> row
 */
function UpdateDataTracker(id, mode)
{
    var workerTableRows = document.getElementsByClassName("workerRow");
    gWorkerDataTracker.id = (id < 0 ? 0 : id);
    gWorkerDataTracker.isEditMode = mode;

    for (var i = 0 ; i < workerTableRows.length ; i++) {
        workerTableRows[i].style.backgroundColor = "";
    }

    for (var i = 0 ; i < workerTableRows.length ; i++) {
        var columnsInRow = workerTableRows[i].querySelectorAll("td");

        if (columnsInRow[0].innerText == gWorkerDataTracker.id) {
            workerTableRows[i].style.backgroundColor = "beige";
            break;
        }
    }
}

function AddWorker()
{
    var workerForm           = document.getElementById("workerForm");
    var workerCivilityField  = document.getElementById("formWorkerCivility");
    var workerNameField      = document.getElementById("formWorkerName");
    var workerFirstnameField = document.getElementById("formWorkerFirstname");
    var workerMailField      = document.getElementById("formWorkerMail");
    var workerPostField      = document.getElementById("formWorkerPost");
    var workerLocationField  = document.getElementById("formWorkerLocation");

    gWorkerDataTracker.isEditMode = false;
    workerForm.hidden = false;
    workerCivilityField.selectedIndex = 0;
    workerNameField.value = "";
    workerFirstnameField.value = "";
    workerMailField.value = "";
    workerPostField.value = "";
    workerLocationField.value = "";
}

function EditWorker()
{
    var workerForm           = document.getElementById("workerForm");
    var workerCivilityField  = document.getElementById("formWorkerCivility");
    var workerNameField      = document.getElementById("formWorkerName");
    var workerFirstnameField = document.getElementById("formWorkerFirstname");
    var workerMailField      = document.getElementById("formWorkerMail");
    var workerPostField      = document.getElementById("formWorkerPost");
    var workerLocationField  = document.getElementById("formWorkerLocation");
    var workerTableRows = document.getElementsByClassName("workerRow");

    if (gWorkerDataTracker.id <= 0) {
        alert("Séléctionnez un employé valide.");
        return;
    }

    gWorkerDataTracker.isEditMode = true;
    workerForm.hidden = false;

    for (var i = 0 ; i < workerTableRows.length ; i++) {
        var columnsInRow = workerTableRows[i].querySelectorAll("td");

        if (columnsInRow[0].innerText == gWorkerDataTracker.id) {
            var selectedIndex = 0;
            
            if (columnsInRow[1].innerText == "Mr")
                selectedIndex = 0;
            else if (columnsInRow[1].innerText == "Mlle")
                selectedIndex = 1;
            else if (columnsInRow[1].innerText == "Mme")
                selectedIndex = 2;

            workerCivilityField.selectedIndex = selectedIndex;
            workerNameField.value = columnsInRow[2].innerText;
            workerFirstnameField.value = columnsInRow[3].innerText;
            workerMailField.value = columnsInRow[4].innerText;
            workerPostField.value = columnsInRow[5].innerText;
            workerLocationField.value = columnsInRow[6].innerText;
            break;
        }
    }
}

function RemoveWorker()
{
    SendXMLHttpRequest(gWorkerDataTracker, "../Control/Worker/remove.php");
    location.reload();
}

/*
* Send the form to PHP code, in which it will decide
* whether to add or update an entry 
*/
function SubmitForm()
{
    var workerCivilityField  = document.getElementById("formWorkerCivility")
    var workerNameField      = document.getElementById("formWorkerName");
    var workerFirstnameField = document.getElementById("formWorkerFirstname");
    var workerMailField      = document.getElementById("formWorkerMail");
    var workerPostField      = document.getElementById("formWorkerPost");
    var workerLocationField  = document.getElementById("formWorkerLocation");

    console.log(dataToSend);
    // If some value in the form is empty, then we refuse to submit it
    if (workerNameField.value == "" || workerFirstnameField.value == "" ||
        workerMailField.value == "" || workerPostField.value == "" || workerLocationField.value == "")
        return;

    var dataToSend = {
        NumEmp: gWorkerDataTracker.id,
        editMode: gWorkerDataTracker.isEditMode,
        Civilite: workerCivilityField.options[workerCivilityField.selectedIndex].innerText,
        Nom: workerNameField.value,
        Prenom: workerFirstnameField.value,
        Mail: workerMailField.value,
        Poste: workerPostField.value,
        Lieu: workerLocationField.value,
    };

    SendXMLHttpRequest(dataToSend, "../Control/Worker/form_submit.php");
    location.reload();
}

/**
 * 
 */
function UpdateUnaffectedCheck()
{
    var affectedCBox = document.getElementById("showAffectedOnes");
    var unaffectedCBox = document.getElementById("showUnaffectedOne");

    if (affectedCBox.checked == true) {
        unaffectedCBox.checked = false;
        return;
    }
}

/**
 * 
 */
function UpdateAffectedCheck()
{
    var affectedCBox = document.getElementById("showAffectedOnes");
    var unaffectedCBox = document.getElementById("showUnaffectedOne");

    if (unaffectedCBox.checked == true) {
        affectedCBox.checked = false;
        return;
    }
}