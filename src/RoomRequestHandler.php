<?php

    require_once("AppBootStrap.php");

    AppBootStrap::init();

    class RoomRequestHandler {

        public static function createRoom($roomData) {
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

        private static function initConnection() {
            return (new DB())->getConnection();
        }
    }

?>