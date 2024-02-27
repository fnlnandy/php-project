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
 * 
 */
function RedirectToPage(element)
{
    var linked = element.querySelector("a");
    window.location.href = linked.href;
}

/**
 * 
 */
function CloseFormDialog()
{
    var formDialog = document.getElementById("formDialog");
    formDialog.style.opacity = 0;
    formDialog.addEventListener("transitionend", () => {
        formDialog.style.display = "none";
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
    formDialog.style.display = "block";
    formDialog.style.opacity = 1;
}

/**
 * 
 */
function GetCurrentSelectOptionValue(selectElementId, currentId)
{
    var elem = selectElementId;
    var options = elem.querySelectorAll("option");
    const max = options.length;

    if (currentId >= max)
        return "";

    return options[currentId]?.innerText;
}

/**
 * 
 */
function GetSelectionIndexForSelectedName(nameToCompare, clientDBId)
{
    var workerRel = document.getElementById(clientDBId);
    var workerRows = workerRel.querySelectorAll("tr");
    const max = workerRows.length;

    console.log([nameToCompare, clientDBId]);
    
    for (var i = 0 ; i < max ; i++) {
        var currentColumn = workerRows[i].querySelectorAll("td");

        if (currentColumn.length > 1 && currentColumn[1].innerText == nameToCompare) {
            console.log(currentColumn[0].innerText);
            return i;
        }
    }

    console.log("No index found.");
    return 0;
}

/**
 * 
 */
function UpdateFormMatchingSelects(idIsBase, idName, infoName)
{
    var idField = document.getElementById(idName);
    var infoField = document.getElementById(infoName);

    console.log([idIsBase, idName, infoName]);
    if (idIsBase) {
        infoField.selectedIndex = idField.selectedIndex;
    }
    else {
        idField.selectedIndex = infoField.selectedIndex;
    }
}