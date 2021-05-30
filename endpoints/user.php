<?php
    require_once("../src/AppBootStrap.php");
    
    switch($_SERVER["REQUEST_METHOD"]) {
        case "GET": {

            break;
        }
        case "POST": {
            $newPersonData = json_decode(file_get_contents("php://input"), true);

            $userId = UserRequestHandler::createUser($newPersonData);
            
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