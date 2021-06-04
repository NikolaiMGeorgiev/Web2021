<?php

    require_once("AppBootStrap.php");

    AppBootStrap::init();

    class RoomRequestHandler {

        public static function createRoom($roomData) {
            if (!$roomData) {
                throw new BadRequestException("Room data should be provided");
            }

            $connection = self::initConnection();

            $stmt = $connection->prepare("INSERT INTO rooms (name,waitingInterval, meetInterval,
                userId, start) VALUES (:name, :waitingInterval, :meetInterval, :userId, :start)");
            $success = $stmt->execute([
                "name" => $roomData["name"],
                "waitingInterval" => $roomData["waitingInterval"],
                "meetInterval" => $roomData["meetInterval"],
                "userId" => $_SESSION["id"],
                "start" => $roomData["start"]
            ]);

            $roomId = $connection->lastInsertId();

            return $roomId;
        }

        public static function getUserRooms($id) {
            if (!$id) {
                throw new BadRequestException("User id should be provided");
            }

            $connection = self::initConnection();

            $stmt = $connection->prepare("SELECT * FROM rooms WHERE userId=:userId");
            $stmt->execute([
                "userId" => $id
            ]);
            
            $rooms = [];

            while ($row = $stmt->fetch()) {
                $rooms[] = new Room($row["name"], $row["waitingInterval"], $row["meetInterval"], $row["start"]);
            }

            return $rooms;

        }

        private static function initConnection() {
            return (new DB())->getConnection();
        }

    }

?>