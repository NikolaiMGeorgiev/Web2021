window.onload = function () {
    if (document.getElementById("login-btn")) {
        document.getElementById("login-btn").addEventListener("click",function () {
            postForm('login', 2);
        });
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
        document.getElementById("student-btn").addEventListener("click", function () {
            postForm('register', 1);
        });
    }

    if (document.getElementById("teacher-btn")) {
        document.getElementById("teacher-btn").addEventListener("click", function () {
            postForm('register', 2);
        });
    }
};

async function postForm (formType, userTypeId = 0) {
    if (validateInput()) {
        const data = userTypeId ? getFormDataJSON(userTypeId) : '';
        const url = formType === "register" ? "http://localhost/Web2021/endpoints/user.php" :
            "http://localhost/Web2021/endpoints/session.php";
        var response;
        const responseJSON = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: data,
        }).then(data => data.json());
        response = formType === "register" ? responseJSON['userId'] : responseJSON['success'];
        console.log(responseJSON['success']);

        if (!response && !document.getElementById("error_validation")) {
            let inputs = document.getElementsByTagName("input");
            let lastInput = inputs[inputs.length - 1];
            let error = document.createElement("small");
            error.classList.add("error_field");
            error.setAttribute("id", "error_validation");
            error.textContent = "Невалидни входни данни";
            
            lastInput.parentNode.insertBefore(error, lastInput.nextSibling);
        } else if (formType === 'register') {
            window.location.href = 'register_success.html';
        } else if (formType === 'login') {
            window.location.href = 'panel.html';
        }
    }
}

function validateInput () {
    let inputs = document.getElementsByTagName("input");
    let isValid = true;

    for (const input of inputs) {
        if (input.value == "") {
            if (!document.getElementById("error_" + input.id)) {
                let error = document.createElement("small");
                error.classList.add("error_field");
                error.setAttribute("id", "error_" + input.id);
                error.textContent = "Полето е задължително";
                input.parentNode.insertBefore(error, input.nextSibling);
            }

            isValid = false;
        } else if (input.value != "" && document.getElementById("error_" + input.id)) {
            if (input.name == "email" && !isValidEmail(input.value)) {
                document.getElementById("error_" + input.id).textContent = "Грешен имейл";
                isValid = false;
                continue;
            }

            document.getElementById("error_" + input.id).remove();
        } else if (input.name == "email" && !isValidEmail(input.value)) {
            let error = document.createElement("small");
            error.classList.add("error_field");
            error.setAttribute("id", "error_email");
            error.textContent = "Грешен имейл";
            input.parentNode.insertBefore(error, input.nextSibling);
            isValid = false;
        }
    }

    return isValid;
}

function isValidEmail(email) {
    const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

function getFormDataJSON (userTypeId) {
    let inputs = document.getElementsByTagName("input");
    let name = "";
    data = {};
    data["userTypeId"] = userTypeId;

    for (const input of inputs) {
        if (input.name == "first_name") {
            name += input.value;
            continue;
        } else if (input.name == "last_name") {
            name += " " + input.value;
            data["name"] = name;
            continue;
        }

        data[input.name] = input.value;
    }

    return JSON.stringify(data);
}

function redirect (page) {
    window.location.href = window.location.href.replace("register", page);
}