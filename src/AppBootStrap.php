<?php

    class AppBootStrap {

        public static function init() {
            //session_start();

            spl_autoload_register(function ($className) {
                require_once("../src/" . $className . ".php");
            });

            set_exception_handler(function($exception) {
                $response_body = [
                    "message" => "Error"
                ];
                
                echo json_encode($response_body, JSON_UNESCAPED_UNICODE);
            });
        }
    }

?>