window.onload = async function() {
    console.log("here");
    var userType = await getUserType();
    
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

    if (window.location.href.includes("queue.html")) {
        var utlParam = window.location.href.split("?")[1];
        var roomId = utlParam.substring(utlParam.indexOf('=') + 1);
        var userType = await getUserType();

        renderScheduleTable(roomId);

        if (userType === 1) {
            enterQueue(roomId);
        } else {
            initTeacherButtons();
            document.getElementById("bnt-next").addEventListener("click", function () {
                nextAnimation();
                finishCurrentMeeting(roomId);
                startNextMeeting(roomId);
                getQueueStatus(roomId);
            });
        
            document.getElementById("btn-break").addEventListener("click", function() {
                finishCurrentMeeting(roomId);
                getQueueStatus(roomId);
            });
        }

        getQueueStatus(roomId);
        renderQueueTable(roomId);

        var intervalId = window.setInterval(function(){
            renderQueueTable(roomId);
            getQueueStatus(roomId);
            getLink(userType, roomId);
        }, 5000);
    }
}