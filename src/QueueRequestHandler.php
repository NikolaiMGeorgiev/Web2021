<?php

    require_once("AppBootStrap.php");

    AppBootStrap::init();

    class QueueRequestHandler {
        public static function startQueue($roomId) {
            if (!$roomId) {
                throw new BadRequestException("Room data should be provided");
            }

            $connection = self::initConnection();

            $stmt = $connection->prepare("UPDATE rooms SET active=:active WHERE id=:roomId");
            $success = $stmt->execute([
                "roomId" => $roomId,
                "active" => 1
            ]);
            
            $stmt = $connection->prepare("SELECT * FROM schedule WHERE roomId=:roomId ORDER BY index");
            $success = $stmt->execute([
                "roomId" => $roomId,   
            ]);
            
            $students = $stmt->fetchAll();

        }

        private static function initConnection() {
            return (new DB())->getConnection();
        }

    }

?>