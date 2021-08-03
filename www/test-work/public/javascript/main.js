function createAuthorJSON(ruInputId, enInputId) {
    let name = {
        "ruName": document.getElementById(ruInputId).value,
        "enName": document.getElementById(enInputId).value
    };

    if (name.ruName === "") {
        alert("Не введено имя на русском языке")
        return false;
    } else if (name.enName === "") {
        alert("Не введено имя на английском языке")
        return false;
    }

    return JSON.stringify(name);
}

function createBookJSON(ruInputId, enInputId, author1, author2, author3) {
    let name = {
            "ruName": document.getElementById(ruInputId).value,
            "enName": document.getElementById(enInputId).value,
            "authors": []
        },
        a1 = (parseInt(document.getElementById(author1).value)),
        a2 = (parseInt(document.getElementById(author2).value)),
        a3 = (parseInt(document.getElementById(author3).value));

    if (name.ruName === "") {
        alert("Не введено название на русском языке")
        return false;
    } else if (name.enName === "") {
        alert("Не введено название на английском языке")
        return false;
    } else if (a1 === 0 && a2 === 0 && a3 === 0) {
        alert("Не введено ни одного автора")
        return false;
    }

    name.authors.push(a1);
    name.authors.push(a2);
    name.authors.push(a3);


    return JSON.stringify(name);
}

function sendPostAjaxJSON(path, json, responseDivId) {
    let xmlhttp = new XMLHttpRequest(),
        respDiv = document.getElementById(responseDivId);

    xmlhttp.open("POST", path);
    xmlhttp.setRequestHeader("Content-Type", "application/json");


    xmlhttp.addEventListener("readystatechange", () => {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            let str = JSON.parse(unescape(xmlhttp.responseText));//декодирование
            respDiv.innerText = JSON.stringify(str)
        }
    });

    xmlhttp.send(json);
    respDiv.innerText = 'Запрос отправлен, ожидайте...';
}

function getItemByPath(path, responseDivId) {

    let xmlhttp = new XMLHttpRequest(),
        respDiv = document.getElementById(responseDivId);

    xmlhttp.open("GET", path);
    xmlhttp.setRequestHeader("Content-Type", "application/json");


    xmlhttp.addEventListener("readystatechange", () => {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            let str = JSON.parse(unescape(xmlhttp.responseText));//декодирование
            respDiv.innerText = JSON.stringify(str, null, 2)
        }
    });

    xmlhttp.send();
    respDiv.innerText = 'Запрос отправлен, ожидайте...';
}