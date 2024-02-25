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
}
