<?php

    require_once("../src/AppBootStrap.php");

    AppBootStrap::init();

    switch($_SERVER["REQUEST_METHOD"]) {
        case "GET": { // get link
            
            SessionRequestHandler::requireLoggedUser();
            
            if (!isset($_GET["roomId"])) {
                throw new BadRequestException("Room id shoud be provided");
            }

            StudentRequestHandler::getLink($_GET["roomId"], $_SESSION["userId"]);

            echo json_encode(["link" => "bbb.fmi.uni-sofia.bg/id=4214"]);

            break;
        }
        case "POST": { // enter the queue 
            SessionRequestHandler::requireLoggedUser();

            $roomId = json_decode(file_get_contents("php://input"), true);

            StudentRequestHandler::addToQueue($_SESSION["id"],$roomId);

            echo json_encode(["success" => true]);

            break;
        }

        case "PUT" : {
            break;
        }

        case "DELETE" : {

        }
    }
?>