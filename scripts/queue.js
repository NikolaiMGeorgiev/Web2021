function init(){
    document.getElementById("next-bnt").addEventListener("click", function () {
        nextAnimation();
    });
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