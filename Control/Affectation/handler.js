var gDataTracker = {
    id: 0,
    isEditMode: false
};

function test() {
    var req = new XMLHttpRequest();

    req.open("POST", "../Control/Affectation/add.php");

    req.onreadystatechange = function() {
        if (req.readyState == 4 && req.status == 200)
            console.log(req.responseText);
    };

    req.send();
}

function UpdateDataTracker(id, mode)
{
    gDataTracker.id = (id < 0 ? 0 : id);
    gDataTracker.mode = mode;
    console.log(id);
    console.log(mode);
}