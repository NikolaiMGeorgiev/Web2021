<?php

    require_once("AppBootStrap.php");

    class UserRequestHandler {
        public static function createUser($userInfo) {
            $connection = self::initConnection();

            $hashed_password = password_hash($userInfo["password"], PASSWORD_DEFAULT);

            $stmt = $connection->prepare("INSERT INTO users (name,password,email,userTypeId) VALUES (:name, :password, :email, :userTypeId");
            $success = $stmt->execute([
                "name" => $userInfo["name"],
                "password" => $hashed_password,
                "email" => $userInfo["email"],
                "userTypeId" => $userInfo["userTypeId"]
            ]);

            if (!$success) {
                throw new Exception("Error with database operation");
            }

            $userId = $connection->lastInsertId();
    
            $stmt = $connection->prepare("SELECT * FROM usertypes WHERE id=:userTypeId");
            $success = $stmt->execute([
                "userTypeId" => $userInfo["userTypeId"]
            ]);

            if (!$success) {
                throw new Exception("Error with database operation");
            }

            if ($stmt->fetch(PDO::FETCH_ASSOC)["code"] == "STUDENT") {
                $stmt = $connection->prepare("INSERT INTO studentDetails (fn,year,degree,userId) VALUES (:fn,:year,:degree,:userId)");
                $success = $stmt->execute([
                    "fn" => $userInfo["fn"],
                    "year" => $userInfo["fn"],
                    "degree" => $userInfo["degree"],
                    "userId" => $userId
                ]);

                if (!$success) {
                    throw new Exception("Error with database operation");
                }
            }

            return $userId;
        }

        private static function initConnection() {
            return (new DB())->getConnection();
        }
    }
    

?>