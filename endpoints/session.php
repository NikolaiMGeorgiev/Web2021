<?php

    require_once("../src/AppBootStrap.php");

    AppBootStrap::init();

    switch($_SERVER["REQUEST_METHOD"]) {
        case "GET": { // get logged user
            SessionRequestHandler::requireLoggedUser();

            $userData = UserRequestHandler::getUserById($_SESSION["id"]);
            echo json_encode($userData);

            break;
        }
        case "POST": { // login
            $loginData = json_decode(file_get_contents("php://input"), true);
            $user = SessionRequestHandler::login($loginData);
                
            $_SESSION["logged"] = true;
            $_SESSION["id"] = $user['id'];
            $_SESSION["typeId"] = $user['userTypeId'];
            
            //header('Location: http://localhost/Web2021/panel.html');
            echo json_encode(["success" => true]);
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