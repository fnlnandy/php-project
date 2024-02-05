function test() {
    var req = new XMLHttpRequest();

    req.open("POST", "../Control/Affectation/add.php");
    
    req.onreadystatechange = function() {
        if (req.readyState == 4 && req.status == 200)
            console.log(req.responseText);
    };

    req.send();
}