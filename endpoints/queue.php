<?php
    require_once("../src/AppBootStrap.php");

    AppBootStrap::init();


    switch($_SERVER["REQUEST_METHOD"]) {
        case "GET": {  // get students in queue

            echo json_encode($roomsData);
        }
        case "POST": { // start (roomId)

            
            echo json_encode(["userId" => $userId]);
            break;
        }
        case "PUT" : {
            
            break;
        }
        case "DELETE" : {

        }
    }

?>