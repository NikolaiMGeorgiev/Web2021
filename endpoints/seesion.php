<?php

    require_once("../src/AppBootStrap.php");

    AppBootStrap::init();

    switch($_SERVER["REQUEST_METHOD"]) {
        case "GET": {

            break;
        }
        case "POST": { // login
            $loginData = json_decode(file_get_contents("php://input"), true);

            $logged = SessionRequestHandler::login($loginData);
            
            if ($logged) {
                $_SESSION["logged"] = true;
            } 
            
            echo json_encode(["success" => $logged]);

            break;
        }
        case "PUT" : {
            break;
        }
        case "DELETE" : { // logout
            session_destroy();
            echo json_encode(["success" => true]);
        }
    }
?>