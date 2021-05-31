<?php

    require_once("AppBootStrap.php");
    
    AppBootStrap::init();

    class SessionRequestHandler {

        public static function login(array $loginData) : bool {
            
            $connection = self::initConnection();

            $stmt = $connection->prepare("SELECT * FROM users WHERE name=:email");
            $stmt->execute([
                "name" => $loginData["email"]
            ]);
            
            $user = $stmt->fetch();

            if (!$user) {
                return false;
            } else if (!password_verify($loginData["password"], $user["password"])) {
                return false;
            }

            return true;
        }

        public static function requireLoggedUser() {
            if (!$_SESSION["logged"]) {
                throw new AuthorizationException();
            }
        }

        private static function initConnection() {
            return (new DB())->getConnection();
        }
    }
    

?>