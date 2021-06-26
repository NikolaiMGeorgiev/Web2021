function init() {
    var userRow = 
        '<div class="user-row">' +
            '<input type="text" name="email" class="email">' + 
            '<input type="text" name="userTime" class="userTime" disabled>' + 
            '<button type="button" class="add-btn">+</button>' + 
            '<button type="button" class="remove-btn">-</button>'
        '</div>';
    var isEditing = (window.location.href.indexOf("edit") != -1);
    document.getElementById("schedule").innerHTML = userRow;

    buttonsInit(document.querySelector(".add-btn"), document.querySelector(".remove-btn"));
    document.getElementById("time").addEventListener("input", function () {
        if (validateScheduleInput()) {
            changeTime(document.getElementById("time").value);
        }
    });
    document.getElementById("meetInterval").addEventListener("input", function () {
        if (validateScheduleInput()) {
            changeTime(document.getElementById("time").value);
        }
    });
    document.getElementById("waitingInterval").addEventListener("input", function () {
        if (validateScheduleInput()) {
            changeTime(document.getElementById("time").value);
        }
    });

    document.getElementById("schedule_from").addEventListener("submit", function (event) {
    event.preventDefault();
        if (validateFormInput() && validateUsersInput()) {
            postForm(isEditing);
            event.preventDefault();
        } else {
            event.preventDefault();
        }
    });
    document.getElementById("cancel_btn").addEventListener("click", function () {
        window.location.href = "panel.html";
    });
    
    if (isEditing) {
        fillRoomData();
    }
}

async function postForm(isEditing = 0) {
    const response = await fetch("endpoints/room.php", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: getFormData(isEditing),
    }).then(data => data.json());

    if (response.success) {
        window.location.href = "panel.html";
    } else {
        alert("Couldn't create room. Please try again");
    }
}

async function fillRoomData() {
    const roomId = window.location.href.split("?")[1].split("&")[0];
    const userData = await fetch('endpoints/room.php' + "?" + roomId, {
        method: 'GET'
    }).then(data => data.json());

    fillSchedule(userData.schedule);

    var timeDB = userData.room["start"].split(" ")[1].split(":");
    var time = timeDB[0] + ":" + timeDB[1];

    document.getElementById("name").value = userData.room["name"];
    document.getElementById("waitingInterval").value = userData.room["waitingInterval"];
    document.getElementById("meetInterval").value = userData.room["meetInterval"];
    document.getElementById("date").value = userData.room["start"].split(" ")[0];
    document.getElementById("time").value = time;

    document.querySelectorAll(".user-row").forEach(element => {
        buttonsInit(element.childNodes[2], element.childNodes[3]);
    });
    
    changeTime(document.getElementById("time").value);
}

function getFormData (isEditing = 0) {
    var schedule = [];
    document.querySelectorAll(".user-row").forEach(element => {
        schedule.push(element.childNodes[0].value);
    });

    var data = {
        "name": document.getElementById("name").value,
        "waitingInterval": document.getElementById("waitingInterval").value,
        "meetInterval": document.getElementById("meetInterval").value,
        "start": document.getElementById("date").value + " " + document.getElementById("time").value,
        "schedule": schedule
    };
    if (isEditing) {
        const roomId = window.location.href.split("?")[1].split("&")[0].replace("roomId=", "");
        data["id"] = roomId;
        data["edit"] = true;
    }

    return JSON.stringify(data);
}

function fillSchedule(data) {
    var scheduleHTML = '';
    for (var single of data) {
        scheduleHTML += 
        '<div class="user-row">' +
            '<input type="text" name="email" class="email" value="' + single["email"] + '">' + 
            '<input type="text" name="userTime" class="userTime" disabled>' + 
            '<button type="button" class="add-btn">+</button>' + 
            '<button type="button" class="remove-btn">-</button>' +
        '</div>';
        
    }
    document.getElementById("schedule").innerHTML = scheduleHTML;
}

function validateFormInput () {
    let name = document.getElementById("name");
    let date = document.getElementById("date");

    renderError(name);
    renderError(date);
    renderError(document.getElementById("time"));
    renderError(document.getElementById("meetInterval"));
    renderError(document.getElementById("waitingInterval"));

    return validateInput(name) && validateInput(date) && validateScheduleInput();
}

function validateScheduleInput () {
    let time = document.getElementById("time");
    let meetInterval = document.getElementById("meetInterval");
    let waitingInterval = document.getElementById("waitingInterval");

    return validateInput(time) && validateInput(meetInterval) && validateInput(waitingInterval);
}

function validateInput(element) {
    return (element.value && element.value.length);
}

function renderError(element) {
    if (!element.value || element.value.length == 0) {
        element.classList.add("input-error");
    } else {
        element.classList.remove("input-error");
    }
}

function validateUsersInput () {
    var isValid = true;
    document.querySelectorAll(".email").forEach(element => {
        isValid = validateInput(element);
        renderError(element);
    });

    return isValid;
}

function changeTime (timeString) {
    var time = timeString.split(":");
    var startTime = new Date();
    var meetTime = parseInt(document.getElementById("meetInterval").value);
    var waitTime = parseInt(document.getElementById("waitingInterval").value);
    startTime.setHours(time[0]);
    startTime.setMinutes(time[1]);

    document.querySelectorAll(".userTime").forEach(element => {
        let minutes = (startTime.getMinutes() < 10 ? "0" : "") + startTime.getMinutes();
        let hours = (startTime.getHours() < 10 ? "0" : "") + startTime.getHours();
        element.value = hours + ":" + minutes;
        startTime = addMinutes(startTime, (meetTime + waitTime));
    });
}

function addMinutes(date, minutes) {
    return new Date(date.getTime() + minutes*60000);
}

function buttonsInit (addButton, removeButton) {
    var userRowHTML = 
        '<input type="text" name="email" class="email">' + 
        '<input type="text" name="userTime" class="userTime" disabled>' + 
        '<button type="button" class="add-btn">+</button>' + 
        '<button type="button" class="remove-btn">-</button>';

    removeButton.addEventListener("click", function () {
        if (document.getElementsByClassName("remove-btn").length > 1) {
            removeButton.parentNode.remove();
        }

        if (validateScheduleInput()) {
            changeTime(document.getElementById("time").value);
        }
    });

    addButton.addEventListener("click", function () {
        var rowNode = document.createElement("div");
        rowNode.classList.add("user-row");
        rowNode.innerHTML = userRowHTML;
        document.querySelector("#schedule").insertBefore(rowNode, addButton.parentNode.nextSibling);

        buttonsInit(rowNode.childNodes[2], rowNode.childNodes[3]);

        if (validateScheduleInput()) {
            changeTime(document.getElementById("time").value);
        }
    });
}

init();