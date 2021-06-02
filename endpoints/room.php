<?php
    require_once("../src/AppBootStrap.php");

    AppBootStrap::init();


    switch($_SERVER["REQUEST_METHOD"]) {
        case "GET": { 
            SessionRequestHandler::requreLoggedTeacher();

            
        }
        case "POST": { // create room
            SessionRequestHandler::requreLoggedTeacher();

            $newRoomData = json_decode(file_get_contents("php://input"), true);

            $roomId = RoomRequestHandler::createRoom($newRoomData);
            
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