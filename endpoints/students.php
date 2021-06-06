<?php

    require_once("../src/AppBootStrap.php");

    AppBootStrap::init();

    switch($_SERVER["REQUEST_METHOD"]) {
        case "GET": { // get all students
            SessionRequestHandler::requireLoggedTeacher();
            
            $studentsData = StudentRequestHandler::getAllStudents();

            echo json_encode($studentsData);

            break;
        }
        case "POST": { 

        }
        case "PUT" : {
            break;
        }
        case "DELETE" : {

        }
    }
?>