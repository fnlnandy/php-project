var gDataTracker = {
    id: 0,
    isEditMode: false
};

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

function UpdateDataTracker(id, mode)
{
    var displayer = document.getElementById("currentNumAffectDisplayer");

    gDataTracker.id = (id < 0 ? 0 : id);
    gDataTracker.mode = mode;
    displayer.value = id;

    console.log(id);
    console.log(mode);
}

function RemoveAffectationEntry()
{
    SendXMLHttpRequest(gDataTracker, "../Control/Affectation/remove.php");
}

function AddAffectation()
{

}

function EditAffectation()
{

}

function SubmitForm()
{
    
}
