window.onload = function () {
    if (document.getElementById("login-btn")) {
        document.getElementById("login-btn").addEventListener("click", postForm);
    }

    if (document.getElementById("register-techer-btn")) {
        document.getElementById("register-techer-btn").addEventListener("click", function () {
            redirect("register_teacher");
        });
    }

    if (document.getElementById("register-student-btn")) {
        document.getElementById("register-student-btn").addEventListener("click", function () {
            redirect("register_student");
        });
    }

    if (document.getElementById("student-btn")) {
        document.getElementById("student-btn").addEventListener("click", postForm);
    }

    if (document.getElementById("teacher-btn")) {
        document.getElementById("teacher-btn").addEventListener("click", postForm);
    }
};

function postForm () {
    if (validateInput()) {
        const data = getFormDataJSON();
        const url =  "http://localhost/Web2021/endpoints/user.php";
        const response = fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: data,
        });
        console.log(response);
        if (!response && !document.getElementById("error_validation")) {
            let inputs = document.getElementsByTagName("input");
            let lastInput = inputs[inputs.length - 1];
            let error = document.createElement("small");
            error.classList.add("error_field");
            error.setAttribute("id", "error_validation");
            error.textContent = "Невалидни входни данни";
            lastInput.parentNode.insertBefore(error, lastInput.nextSibling);
        } else if (window.location.href.substring("register")) {
            //window.location.href = 'register_success.html';
        }
    }
}

function validateInput () {
    let inputs = document.getElementsByTagName("input");
    let isValid = true;
    for (const input of inputs) {
        if (input.value == "" && !document.getElementById("error_" + input.id)) {
            let error = document.createElement("small");
            error.classList.add("error_field");
            error.setAttribute("id", "error_" + input.id);
            error.textContent = "Полето е задължително";
            input.parentNode.insertBefore(error, input.nextSibling);
            isValid = false;
        } else if (input.value != "" && document.getElementById("error_" + input.id)) {
            document.getElementById("error_" + input.id).remove();
        }
    }
    return isValid;
}

function getFormDataJSON () {
    let inputs = document.getElementsByTagName("input");
    let name = "";
    data = {};

    for (const input of inputs) {
        if (input.name == "first_name") {
            name += input.value;
            continue;
        } else if (input.name == "last_name") {
            name += input.value;
            data["name"] = name;
            continue;
        }
        data[input.name] = input.value;
    }

    data["userTypeId"] = "1";

    return JSON.stringify(data);
}

function redirect (page) {
    window.location.href = window.location.href.replace("register", page);
}