function showStatut(idu, stt, tpe, gea) {
    if (stt == "") {
        location.reload();
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() { // la fonction sera executée à chaque fois que l'état onready change
            if (this.readyState == 4 && this.status == 200) { // readyState->4 = request finished and response is ready ; status->200 = OK
                document.getElementById("txtHint").innerHTML = this.responseText;
            }
        };
        if (stt != "" && tpe == "" && gea == "") {
            xmlhttp.open("GET", "getuser.php?st=" + stt + "&id=" + idu, true);
        }
        if (stt == "" && tpe != "" && gea == "") {
            xmlhttp.open("GET", "getuser.php?ty=" + tpe + "&id=" + idu, true);
        }
        if (stt == "" && tpe == "" && gea != "") {
            xmlhttp.open("GET", "getuser.php?ag=" + gea + "&id=" + idu, true);
        }

        if (stt != "" && tpe != "" && gea == "") {
            xmlhttp.open("GET", "getuser.php?st=" + stt + "&ty=" + tpe + "&id=" + idu, true);
        }
        if (stt != "" && gea != "" && tpe == "") {
            xmlhttp.open("GET", "getuser.php?st=" + stt + "&ag=" + gea + "&id=" + idu, true);
        }
        if (tpe != "" && gea != "" && stt == "") {
            xmlhttp.open("GET", "getuser.php?ty=" + tpe + "&ag=" + gea + "&id=" + idu, true);
        }

        if (stt != "" && tpe != "" && gea != "") {
            xmlhttp.open("GET", "getuser.php?st=" + stt + "&ty=" + tpe + "&ag=" + gea + "&id=" + idu, true);
        }
        xmlhttp.send();
    }
}

function showType(idu, tpe, stt, gea) {
    if (tpe == "") {
        location.reload();
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() { // la fonction sera executée à chaque fois que l'état onready change
            if (this.readyState == 4 && this.status == 200) { // readyState->4 = request finished and response is ready ; status->200 = OK
                document.getElementById("txtHint").innerHTML = this.responseText;
            }
        };
        if (stt != "" && tpe == "" && gea == "") {
            xmlhttp.open("GET", "getuser.php?st=" + stt + "&id=" + idu, true);
        }
        if (stt == "" && tpe != "" && gea == "") {
            xmlhttp.open("GET", "getuser.php?ty=" + tpe + "&id=" + idu, true);
        }
        if (stt == "" && tpe == "" && gea != "") {
            xmlhttp.open("GET", "getuser.php?ag=" + gea + "&id=" + idu, true);
        }

        if (stt != "" && tpe != "" && gea == "") {
            xmlhttp.open("GET", "getuser.php?st=" + stt + "&ty=" + tpe + "&id=" + idu, true);
        }
        if (stt != "" && gea != "" && tpe == "") {
            xmlhttp.open("GET", "getuser.php?st=" + stt + "&ag=" + gea + "&id=" + idu, true);
        }
        if (tpe != "" && gea != "" && stt == "") {
            xmlhttp.open("GET", "getuser.php?ty=" + tpe + "&ag=" + gea + "&id=" + idu, true);
        }

        if (stt != "" && tpe != "" && gea != "") {
            xmlhttp.open("GET", "getuser.php?st=" + stt + "&ty=" + tpe + "&ag=" + gea + "&id=" + idu, true);
        }
        xmlhttp.send();
    }
}

function showAge(idu, gea, stt, tpe) {
    if (gea == "") {
        location.reload();
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() { // la fonction sera executée à chaque fois que l'état onready change
            if (this.readyState == 4 && this.status == 200) { // readyState->4 = request finished and response is ready ; status->200 = OK
                document.getElementById("txtHint").innerHTML = this.responseText;
            }
        };
        if (stt != "" && tpe == "" && gea == "") {
            xmlhttp.open("GET", "getuser.php?st=" + stt + "&id=" + idu, true);
        }
        if (stt == "" && tpe != "" && gea == "") {
            xmlhttp.open("GET", "getuser.php?ty=" + tpe + "&id=" + idu, true);
        }
        if (stt == "" && tpe == "" && gea != "") {
            xmlhttp.open("GET", "getuser.php?ag=" + gea + "&id=" + idu, true);
        }

        if (stt != "" && tpe != "" && gea == "") {
            xmlhttp.open("GET", "getuser.php?st=" + stt + "&ty=" + tpe + "&id=" + idu, true);
        }
        if (stt != "" && gea != "" && tpe == "") {
            xmlhttp.open("GET", "getuser.php?st=" + stt + "&ag=" + gea + "&id=" + idu, true);
        }
        if (tpe != "" && gea != "" && stt == "") {
            xmlhttp.open("GET", "getuser.php?ty=" + tpe + "&ag=" + gea + "&id=" + idu, true);
        }

        if (stt != "" && tpe != "" && gea != "") {
            xmlhttp.open("GET", "getuser.php?st=" + stt + "&ty=" + tpe + "&ag=" + gea + "&id=" + idu, true);
        }
        xmlhttp.send();
    }
}