function RedirectToPage(element)
{
    var linked = element.querySelector("a");
    window.location.href = linked.href;
}