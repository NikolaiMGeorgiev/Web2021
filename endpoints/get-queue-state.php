<?php
    require_once("../src/AppBootStrap.php");

    AppBootStrap::init();

    switch($_SERVER["REQUEST_METHOD"]) {
        case "GET": {  // get queue state
            SessionRequestHandler::requireLoggedTeacher();

            if (!isset($_GET["roomId"])) {
                throw new BadRequestException("Room id shoud be provided");
            }

            $response = QueueRequestHandler::refreshQueue($_GET["roomId"]);

            echo json_encode($response);

            break;
        }

        case "POST": { 
            break;
        }

        case "PUT" : {
            break;
        }

        case "DELETE" : {

        }
    }

?>