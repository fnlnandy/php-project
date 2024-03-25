/**
 * We trick the browser into thinking it needs to reload,
 * since location.reload() only makes a GET request and sometimes
 * the page's data isn't actually updated.
 * 
 * This does absolutely nothing as it's literally directed to index.php
 */
function FakePostRequest(url)
{
    var fakeReq = new XMLHttpRequest();
    var fakeData = { __trash_data_345243353348: null };
    
    fakeReq.open("POST", "../index.php");
    fakeReq.setRequestHeader("Content-type", "application/json") // We set the header for the data we'll send
    fakeReq.onreadystatechange = () => {
        window.location.replace(url);
        window.location.reload();
    }
    fakeReq.send(JSON.stringify(fakeData));// We send the data in JSON Format
}

/**
 * Reloads the current page with a timestamp, might 
 * be deprecated if it shows that FakePostRequest() can
 * handle the refreshing by itself
 */
function ReloadPageWithTimeStamp()
{
    var tsName = "tstamp";
    var tsVal = (performance.now() % 1000).toFixed(8).toString();
    var currentUrl = window.location.href;
    var urlParams = new URLSearchParams(window.location.search);
    var updatedUrl = "";
    
    urlParams.set(tsName, tsVal);
    updatedUrl = currentUrl.split("?")[0] + "?" + urlParams.toString();

    FakePostRequest(updatedUrl);
    // window.location.replace(updatedUrl);
}

/**
 * Sends a Request to a specific file,
 * response is just logged, but we rarely
 * are able to use that response
 */
function SendXMLHttpRequest(dataToSend, dest, err = "Erreur inconnue.") {
    ReloadPageWithTimeStamp();
    var req = new XMLHttpRequest();
    var result = {
        err: false,
        text: "",
        errText: ""
    }

    req.open("POST", dest);              // We prepare the destination file
    req.setRequestHeader("Content-type", "application/json") // We set the header for the data we'll send
    req.onload = function () {
        console.log(req.responseText);
        ReloadPageWithTimeStamp();
    };
    req.onerror = () => {
        console.error("Request failed.");
    };
    req.onreadystatechange = () => { 
        if (req.readyState == req.DONE) {
            result.err = (req.status != 200);
            result.text = req.responseText;
            result.errText = req.statusText;

            if (result.err == true) {
                alert(err);
            }

            return result;
        }
        ReloadPageWithTimeStamp(); 
    };
    req.send(JSON.stringify(dataToSend));// We send the data in JSON Format
    console.log("Before updating the URL.");
    ReloadPageWithTimeStamp();
}

/**
 * Redirects to a page specified in an <a> element,
 * is used in the main menu page so that clicking on a
 * menu redirects you to the expected page
 */
function RedirectToPage(element)
{
    var linked = element.querySelector("a");
    window.location.href = linked.href;
}

/**
 * Displays a <dialog> element
 */
function DisplayFormDialog(formId = "form-dialog-container")
{
    var formDialog = document.getElementById(formId);
    formDialog.showModal();
    formDialog.style.display = "block";
    formDialog.style.opacity = 1;
}

/**
 * Closes a <dialog> element
 */
function CloseFormDialog(formId = "form-dialog-container")
{
    var formDialog = document.getElementById(formId);
    formDialog.style.opacity = 0;
    formDialog.addEventListener("transitionend", () => {
        formDialog.style.display = "none";
        formDialog.close();
    }, {once: true});
}

/**
 * Get the current selected element's innerText
 * based on an id
 */
function GetCurrentSelectOptionValue(selectElementId, currentId)
{
    var elem    = selectElementId;
    var options = elem.querySelectorAll("option");
    const max   = options.length;

    if (currentId >= max)
        return "";

    return options[currentId]?.innerText;
}

/**
 * Get the current selection's index in the <select> element
 */
function GetSelectionIndexForSelectedName(nameToCompare, clientDBId)
{
    var workerRel  = document.getElementById(clientDBId);
    var workerRows = workerRel.querySelectorAll("tr");
    const max      = workerRows.length;

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
 * Synchronizes two select fields that have the relation
 * <select class="id..."></select> and <select class="name..."></select>
 */
function UpdateFormMatchingSelects(idIsBase, idName, infoName)
{
    var idField   = document.getElementById(idName);
    var infoField = document.getElementById(infoName);

    console.log([idIsBase, idName, infoName]);
    if (idIsBase) {
        infoField.selectedIndex = idField.selectedIndex;
    }
    else {
        idField.selectedIndex = infoField.selectedIndex;
    }
}

function FormatDMYDate(date)
{
    var parts = date.split('/');

    var day = parts[0];
    var month = parts[1];
    var year = parts[2];
    var valid = year + '-' + month + '-' + day;

    return valid;
}