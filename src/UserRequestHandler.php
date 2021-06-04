<?php

    require_once("AppBootStrap.php");

    AppBootStrap::init();

    class UserRequestHandler {

        public static function createUser(array $userInfo) {
            if (!$userInfo) {
                throw new BadRequestException("User data should be provided");
            }
            
            $connection = self::initConnection();

            $hashed_password = password_hash($userInfo["password"], PASSWORD_DEFAULT);

            $stmt = $connection->prepare("INSERT INTO users (name,password,email,userTypeId) VALUES (:name, :password, :email, :userTypeId)");
            $success = $stmt->execute([
                "name" => $userInfo["name"],
                "password" => $hashed_password,
                "email" => $userInfo["email"],
                "userTypeId" => $userInfo["userTypeId"]
            ]);

            $userId = $connection->lastInsertId();
    
            $stmt = $connection->prepare("SELECT * FROM usertypes WHERE id=:userTypeId");
            $success = $stmt->execute([
                "userTypeId" => $userInfo["userTypeId"]
            ]);

            if ($stmt->fetch(PDO::FETCH_ASSOC)["code"] == "STUDENT") {
                $stmt = $connection->prepare("INSERT INTO students_details (fn,year,degree,userId) VALUES (:fn,:year,:degree,:userId)");
                $success = $stmt->execute([
                    "fn" => $userInfo["fn"],
                    "year" => $userInfo["fn"],
                    "degree" => $userInfo["degree"],
                    "userId" => $userId
                ]);
            }

            return $userId;
        }

        public static function getUserById(int $id) {
            if (!$id) {
                throw new BadRequestException("User id should be provided");
            }

            $connection = self::initConnection();

            $stmt = $connection->prepare("SELECT * FROM users WHERE id=:id");

            $stmt->execute([
                "id" => $id
            ]);

            $user = $stmt->fetch();

            if (!$user) {
                throw new NotFoundException();
            }

            $stmt = $connection->prepare("SELECT * FROM usertypes WHERE id=:userTypeId");
            $success = $stmt->execute([
                "userTypeId" => $user["userTypeId"]
            ]);

            if ($stmt->fetch(PDO::FETCH_ASSOC)["code"] == "STUDENT") {
                $stmt = $connection->prepare("SELECT * FROM students_details WHERE userId=:userId");
                $stmt->execute([
                    "userId" => $user["id"]
                ]);

                $student = $stmt->fetch();
                
                if (!$student) {
                    throw new NotFoundException();
                }
                $returnUser = new Student($user["name"], $user["email"], $student["fn"], $student["year"], $student["degree"]);
            } else {
                $returnUser = new User($user["name"], $user["email"]);
            }

            return $returnUser;
        }

        private static function initConnection() {
            return (new DB())->getConnection();
        }
        
    }
    

?>