window.onload = async function () {
    var userType;
    const userData = await fetch('http://localhost/Web2021/endpoints/session.php', {
        method: 'GET'
    }).then(data => data.json());

    if (userData['fn']) {
        userType = 1;
    } else {
        userType = 2;
    }
    
    document.getElementById("nav_panel").addEventListener("click", function () {
        window.location.href = "panel.html";
    });
    document.getElementById("nav_profile").addEventListener("click", function () {
        window.location.href = "profile.html";
    });
    document.getElementById("nav_exit").addEventListener("click", function () {
        logout();
    });

    if (userType === 2) {
        addCreateEventButton();
        document.getElementById("nav_new_room").addEventListener("click", function () {
            window.location.href = "add_room.html";
        });
    }

    if (document.getElementById("events")) {
        loadEvents(userType);
    } else if (document.getElementById("profile")) {
        loadUserInfo(userType);
    }
};

function loadEvents (userType) {
    let events = {
        "1": {
            "name": "АСИ",
            "start": "08.06.20",
            "teacher": "преподавател",
            "place": 4,
            "placeTime": "11:40",
            "studentsCount": 47,
            "meetInterval": 15,
            "waitingInterval": 1
        },
        "2": {
            "name": "АСИ",
            "start": "08.06.20",
            "teacher": "преподавател",
            "place": "4",
            "placeTime": "11:40",
            "studentsCount": 47,
            "meetInterval": 15,
            "waitingInterval": 1
        },
        "3": {
            "name": "АСИ",
            "start": "08.06.20",
            "teacher": "преподавател",
            "place": "4",
            "placeTime": "11:40",
            "studentsCount": 47,
            "meetInterval": 15,
            "waitingInterval": 1
        }
    }

    var container = document.getElementById("events");
    for (var eventId in events) {
        var eventHTML = getEventHTML(userType, eventId, events);
        container.insertAdjacentHTML('beforeend', eventHTML);
    }
}

function getEventHTML (userType, eventId, events) {
    if (userType === 1) {
        var eventHTML =
            '<div class="event">' +
                '<header>' +
                    '<h2 class="name" class="bold">' + events[eventId]["name"] + '</h2>' +
                    '<h3 class="date">' + events[eventId]["start"] + '</h3>' +
                '</header>' +
                '<article class="event-info-container one-row">' +
                    '<h3 class="place">' +
                        '<span class="heading-label">Ред: </span>' + 
                        '<span class="lighter">' + events[eventId]['place']  + '</span>' +
                    '</h3>' +
                    '<h3 class="place_time">' +
                        '<span class="heading-label">Час за реда: </span>' +
                        '<span class="lighter">' + events[eventId]['placeTime'] + '</span>' +
                    '</h3>' +
                    '<h3 class="teacher">' +
                        '<span class="heading-label">Препозавател: </span>' +
                        '<span  class="lighter">' + events[eventId]['teacher'] + '</span>' +
                    '</h3>' +
                '</article>' +
            '</div>';
    } else {
        var eventHTML =
            '<div class="event">' +
                '<header>' +
                    '<h2 class="name" class="bold">' + events[eventId]["name"] + '</h2>' +
                    '<h3 class="date">' + events[eventId]["start"] + '</h3>' +
                '</header>' +
                '<article class="event-info-container one-row-even">' +
                    '<h3 class="studetns-count">' +
                        '<span class="heading-label">Брой участници: </span>' + 
                        '<span class="lighter">' + events[eventId]['studentsCount']  + '</span>' +
                    '</h3>' +
                    '<h3 class="meet-interval">' +
                        '<span class="heading-label">Време за среща: </span>' +
                        '<span class="lighter">' + events[eventId]['meetInterval'] + ' мин.</span>' +
                    '</h3>' +
                    '<h3 class="waiting-interval">' +
                        '<span class="heading-label">Време за чакане: </span>' +
                        '<span  class="lighter">' + events[eventId]['waitingInterval'] + ' мин.</span>' +
                    '</h3>' +
                '</article>' +
            '</div>';
    }

    return eventHTML;
}

function addCreateEventButton () {
    var topBar = document.getElementById("nav_top_bar");
    var buttonContainer = document.createElement("div");
    buttonContainer.classList.add("nav-element");
    buttonContainer.setAttribute("id", "nav_new_room");
    buttonContainer.innerHTML = 
        '<img src="img/icons/add.png" alt="panel.html">' +
        'Нова стая';
    topBar.parentNode.insertBefore(buttonContainer, topBar.nextSibling);
}

async function loadUserInfo (userType) {
    const userData = await fetch('http://localhost/Web2021/endpoints/session.php', {
        method: 'GET'
    }).then(data => data.json());

    var container = document.getElementById("profile");
    var userHTML = getUserHTML(userType, userData);
    container.innerHTML = userHTML;
}

function getUserHTML (userType, userData) {
    if (userType === 1) {
        var userHTML = 
            '<div class="row columns">' +
                '<h2>Име: <span class="lighter">' + userData['username'] + '</span></h2>' +
                '<h2 id="fn">ФН: <span class="lighter">' + userData['fn'] + '</span></h2>' +
            '</div>' +
            '<h2 class="row">Имейл: <span class="lighter">' + userData['email'] + '</span></h2>' +
            '<h2 class="row">Специалност: <span class="lighter">' + userData['degree'] + '</h2>' +
            '<h2 class="row">Година: <span class="lighter">' + userData['year'] + '</span></h2>';
    } else {
        var userHTML = 
            '<h2>Име: <span class="lighter">' + userData['username'] + '</span></h2>' +
            '<h2 class="row">Имейл: <span class="lighter">' + userData['email'] + '</span></h2>';
    }

    return userHTML;
}

async function logout () {
    const response = await fetch('http://localhost/Web2021/endpoints/session.php', {
        method: 'DELETE'
    }).then(data => data.json());

    if(response["success"]) {
        window.location.href = "index.html";
    }
}