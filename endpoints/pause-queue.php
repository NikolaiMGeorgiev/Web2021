<?php
    require_once("../src/AppBootStrap.php");

    AppBootStrap::init();

    switch($_SERVER["REQUEST_METHOD"]) {
        case "GET": {  // get next student in queue
            SessionRequestHandler::requireLoggedTeacher();

            if (!isset($_GET["roomId"])) {
                throw new BadRequestException("Room id shoud be provided");
            }

            $response = QueueRequestHandler::finishStudent($_GET["roomId"]);

            echo json_encode(["success" => true]);

            break;
        }
    }

?>