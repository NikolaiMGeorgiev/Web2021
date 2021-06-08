<?php

    require_once("AppBootStrap.php");

    AppBootStrap::init();

    class QueueRequestHandler {
        const secondsInMinute = 60;

        public static function startQueue($roomId) {
            if (!$roomId) {
                throw new BadRequestException("Room id should be provided");
            }

            $connection = self::initConnection();
            
            $stmt = $connection->prepare("SELECT * FROM rooms WHERE roomId=:roomId");

            $stmt->execute([
                "roomId" => $roomId
            ]);

            $room = $stmt->fetch();

            $waitingInterval = $room["waitingInterval"];
            $meetInterval = $room["meetInterval"];
            
            while (true) {
                sleep($waitingInterval*self::secondsInMinute);

                $stmt = $connection->prepare(
                    "SELECT *
                    FROM (SELECT * FROM queues WHERE roomId=:roomId) 
                    WHERE roomId=(SELECT MIN(roomId) FROM queues)"
                );

                $stmt->execute([
                    "roomId" => $roomId
                ]);

                $userId = $stmt->fetch()["userId"];

                $stmt = $connection->prepare(
                    "UPDATE queues
                     SET active=1
                     WHERE roomId=:roomId AND userId=:userId"
                );


                $stmt->execute([
                    "roomId" => $roomId,
                    "userId" => $userId
                ]);

                if ($stmt->rowCount() == 0) {
                    break;
                }

                sleep($meetInterval*self::secondsInMinute);

                $stmt = $connection->prepare(
                    "DELETE FROM queues
                     WHERE userId=:userId"
                );

                $stmt->execute([
                    "userId" => $userId
                ]);
            }

        }

        public static function getStudentsInQueue($roomId, $teacherId) {
            if (!$roomId) {
                throw new BadRequestException("Room id should be provided");
            }

            $connection = self::initConnection();

            $stmt = $connection->prepare("SELECT * FROM rooms WHERE roomId=:roomId");

            $stmt->execute([
                "roomId" => $roomId
            ]);

            $room = $stmt->fetch();
            
            if ($room["userId"] != $teacherId) {
                throw new AuthorizationException("This room doesn't belong to this teacher");
            }

            $stmt = $connection->prepare(
                "SELECT *
                 FROM queues
                 WHERE roomId=:roomId 
                 ORDER BY userIndex ASC"
            );
            
            $students = [];

            while ($row = $stmt->fetch()) {
                $students[] = ["id" => $row["userId"]];
            }

        }

        private static function initConnection() {
            return (new DB())->getConnection();
        }

    }

?>