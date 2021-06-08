<?php
    require_once("../src/AppBootStrap.php");

    AppBootStrap::init();


    switch($_SERVER["REQUEST_METHOD"]) {
        case "GET": {  // get students in queue
            SessionRequestHandler::requireLoggedTeacher();

            if (!isset($_GET["roomId"])) {
                throw new BadRequestException("Room id shoud be provided");
            }

            $studentsData = QueueRequestHandler::getStudentsInQueue($_GET["roomId"], $_SESSION["id"]);

            echo json_encode($studentsData);
        }
        case "POST": { // start (roomId)
            SessionRequestHandler::requireLoggedTeacher();

            $roomId = json_decode(file_get_contents("php://input"), true);
            
            echo json_encode(["roomId" => $roomId]);
            
            break;
        }
        case "PUT" : {
            

            
            break;
        }
        case "DELETE" : {

        }
    }

?>