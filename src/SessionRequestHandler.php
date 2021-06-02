<?php

    require_once("AppBootStrap.php");
    
    AppBootStrap::init();

    class SessionRequestHandler {
        const studentTypeId = 1;
        const teacherTypeId = 2;

        public static function login(array $loginData) {
            
            $connection = self::initConnection();

            $stmt = $connection->prepare("SELECT * FROM users WHERE name=:email");
            $stmt->execute([
                "email" => $loginData["email"]
            ]);
            
            $user = $stmt->fetch();

            if (!$user || !password_verify($loginData["password"], $user["password"])) {
                throw new NoutFoundException("Incorrect data");
            }

            return $user;
        }

        public static function requireLoggedUser() {
            if (!$_SESSION["logged"] || !$_SESSION["id"]) {
                throw new AuthorizationException();
            }
        }

        public static function requreLoggedTeacher() {
            if (!$_SESSION["logged"] || !$_SESSION["id"] || $_SESSION["typeId"]!=self::teacherTypeId) {
                throw new AuthorizationException();
            }
        }

        private static function initConnection() {
            return (new DB())->getConnection();
        }
    }
    

?>