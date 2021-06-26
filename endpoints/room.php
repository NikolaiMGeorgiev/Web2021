<?php
    require_once("../src/AppBootStrap.php");

    AppBootStrap::init();


    switch($_SERVER["REQUEST_METHOD"]) {
        case "GET": {  // get user's rooms 
            SessionRequestHandler::requireLoggedUser();

            if (isset($_GET["roomId"])) {
                $response = RoomRequestHandler::getRoom($_GET["roomId"]);

            } else {
                $response = RoomRequestHandler::getUserRooms($_SESSION["id"]);
            }

            echo json_encode($response);
            break;
        }
        case "POST": { // create room
            SessionRequestHandler::requireLoggedTeacher();

            $newRoomData = json_decode(file_get_contents("php://input"), true);

            if($newRoomData["edit"]) {
                RoomRequestHandler::editRoom($newRoomData);
            } else {
                $roomId = RoomRequestHandler::createRoom($newRoomData);
            }
            
            echo json_encode(["success" => true]);
            break;
        }
    }

?>