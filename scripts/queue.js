function init(){
    var utlParam = window.location.href.split("?")[1];
    var roomId = utlParam.substring(utlParam.indexOf('=') + 1);
    document.getElementById("next-bnt").addEventListener("click", function () {
        nextAnimation();
    });
    document.getElementById("btn-start").addEventListener("click", function() {
        startEvent(roomId);
    });

    document.getElementById("btn-queue").addEventListener("click", function() {
        enterQueue(roomId);
    });

    renderQueueTable(roomId);
    
    var intervalId = window.setInterval(function(){
        renderQueueTable(roomId);
      }, 10000);
}

async function renderQueueTable(roomId) {
    const response = await fetch("http://localhost/Web2021/endpoints/queue.php?roomId=" + roomId, {
        method: 'GET'
    }).then(data => data.json());
    
    var queue = document.querySelector("#queue tbody");
    var tableHTML = "";
    if (response.length > 0) {
        for(var i = 0; i < response.length; i++) {
            tableHTML += 
                '<tr>' +
                    '<td>' + (i + 1) + '</td>' +
                    '<td>' + response[i]['id'] + '</td>' +
                '</td>';
        }
    } else {
        tableHTML = 
        '<tr>' +
            '<td colspan="2">Няма ученици/студенти в опашката.</td>' + 
        '</tr>';
    }
    
    queue.innerHTML = tableHTML;
}

async function startEvent(roomId) {
    const response = fetch("http://localhost/Web2021/endpoints/queue.php", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: roomId,
    }).then(data => data.json());
    
}

async function enterQueue(roomId){
    const response = fetch("http://localhost/Web2021/endpoints/students.php", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: roomId,
    }).then(data => data.json());
}

function nextAnimation () {
    var images = document.querySelectorAll(".animation-img");
    var currentStudent = parseInt(images[3].getAttribute("id").replace("img_", ""));
    var nextStudent = (currentStudent + 1) % 5 != 0 ? (currentStudent + 1) % 5 : 5;

    var fadeInImg = document.createElement("img");
    fadeInImg.setAttribute("src", "img/pages/student_" + nextStudent + ".png");
    fadeInImg.setAttribute("id", "img_" + nextStudent);

    images[3].remove();
    images[0].classList.remove("fadein-img");
    images[1].classList.remove("move-img");
    images[2].classList.remove("move-img");
    
    images[0].parentNode.insertBefore(fadeInImg, images[0]);
    fadeInImg.classList.add("fadein-img", "animation-img");
    images[0].classList.add("move-img");
    images[1].offsetHeight;
    images[1].classList.add("move-img");
    images[2].classList.add("fadeaway-img");
}

init();