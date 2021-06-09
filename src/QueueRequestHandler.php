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
            
            $stmt = $connection->prepare("SELECT * FROM rooms WHERE id=:roomId");

            $stmt->execute([
                "roomId" => $roomId
            ]);
            
            $room = $stmt->fetch();

            $waitingInterval = $room["waitingInterval"];
            $meetInterval = $room["meetInterval"];
            
            while (true) {
                //sleep($waitingInterval*self::secondsInMinute);
                print_r("before wait sleep");
                sleep($waitingInterval);
                print_r("after wait sleep");

                $stmt = $connection->prepare(
                    "SELECT userId, MIN(userIndex) AS minIndex FROM queues WHERE roomId=:roomId"
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

                
                print_r("before meet sleep");
                //sleep($meetInterval*self::secondsInMinute);
                sleep($meetInterval);
                print_r("after meet sleep");

                $stmt = $connection->prepare(
                    "DELETE FROM queues
                     WHERE userId=:userId AND roomId=:roomId"
                );

                $stmt->execute([
                    "userId" => $userId,
                    "roomId" => $roomId
                ]);
            }

        }

        public static function getStudentsInQueue($roomId) {
            if (!$roomId) {
                throw new BadRequestException("Room id should be provided");
            }

            $connection = self::initConnection();
<<<<<<< Updated upstream
=======
            $stmt = $connection->prepare("SELECT * FROM rooms WHERE id=:roomId");
            $stmt->execute([
                "roomId" => $roomId
            ]);
            $room = $stmt->fetch();
>>>>>>> Stashed changes

            $stmt = $connection->prepare(
                "SELECT * FROM queues
                 WHERE roomId=:roomId 
                 ORDER BY userIndex ASC"
            );
<<<<<<< Updated upstream

            $stmt->execute([
                "roomId" => $roomId
            ]);
            
=======
            $stmt->execute([
                "roomId" => $roomId
            ]);
>>>>>>> Stashed changes
            $students = [];

            while ($row = $stmt->fetch()) {
                $students[] = ["id" => $row["userId"]];
            }

            return $students;
        }

        private static function initConnection() {
            return (new DB())->getConnection();
        }
    }
?>