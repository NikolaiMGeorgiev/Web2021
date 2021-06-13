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
            
            $stmt = $connection->prepare("UPDATE rooms SET currentTime=now() WHERE id=:id");

            $stmt->execute([
                "id" => $roomId
            ]);
            
            $success = $stmt->fetch();
            
            if (!$success) {
                throw new BadRequestException("Queue couldn't be started");
            }
        }

        public static function getStudentsInQueue($roomId) {
            if (!$roomId) {
                throw new BadRequestException("Room id should be provided");
            }

            $connection = self::initConnection();

            $stmt = $connection->prepare(
                "SELECT * FROM queues
                 WHERE roomId=:roomId 
                 ORDER BY userIndex ASC"
            );

            $stmt->execute([
                "roomId" => $roomId
            ]);
            
            $studentsId = [];

            while ($row = $stmt->fetch()) {
                $studentsId[] = $row["userId"];
            }

            $students = [];

            foreach ($studentsId as $studentId) {
                $stmt = $connection->prepare("SELECT * FROM users WHERE id=:id");

                $stmt->execute([
                    "id" => $studentId
                ]);

                $row = $stmt->fetch();

                $students[] = ["id" => $row["id"], "name" => $row["name"]];
            }

            return $students;
        }

        public static function refreshQueue($roomId) {
            if (!$roomId) {
                throw new BadRequestException("Room id should be provided");
            }

            $connection = self::initConnection();

            $stmt = $connection->prepare("SELECT * FROM rooms WHERE id=:id");

            $stmt->execute([
                "id" => $roomId
            ]);

            $room = $stmt->fetch();

            if ($room["userId"] != $_SESSION["id"]) {
                throw new AuthorizationException();
            }

            $waitingTime = $room["waitingTime"];
            $meetInterval = $room["meetInterval"];
            $isWaiting = $room["isWaiting"];
            $currentTime = $room["currentTime"];

            if ($isWaiting) {
                if (strtotime($currentTime) + ($meetInterval*self::secondsInMinute) > strtotime(date('Y-m-d H:i:s')) ) {
                    return array(["respone" => "Time is over"]);
                } else {
                    return array(["respone" => "Student is in turn"]);
                }
            } else {
                if (strtotime($currentTime) + ($waitingTime*self::secondsInMinute) > strtotime(date('Y-m-d H:i:s')) ) {
                    return array(["respone" => "Break is over"]);
                } else {
                    return array(["respone" => "Coffe break"]);
                }
            }
        }

        public static function getNext($roomId) {
            if (!$roomId) {
                throw new BadRequestException("Room id should be provided");
            }

            $connection = self::initConnection();

            if (isset($_GET["studentId"])) {
                $studentId = $_GET["studentId"];
            } else {
                $stmt = $connection->prepare(
                    "SELECT userId, MIN(userIndex) AS minIndex FROM queues WHERE roomId=:roomId"
                );

                $stmt->execute([
                    "roomId" => $roomId
                ]);
                
                $row = $stmt->fetch();

                if (!$row) {
                    throw new BadRequestException("Empty queue");
                }
                $studentId = $stmt->fetch()["userId"];
            }

            if (!$studentId) {
                throw new BadRequestException("Student id should be provided");
            }

            $stmt = $connection->prepare(
                "UPDATE queues
                 SET active=1
                 WHERE roomId=:roomId AND userId=:studentId"
            );

            $stmt->execute([
                "roomId" => $roomId,
                "studentId" => $studentId
            ]);

            if ($stmt->rowCount() == 0) {
                throw new BadRequestException("Couldn't find student");
            }

            $stmt = $connection->prepare("UPDATE rooms SET isWaiting=:isWaiting, currentTime=now() WHERE roomId=:roomId");

            $stmt->execute([
                "isWaiting" => 1,
                "roomId" => $roomId
            ]);
        }

        public static function finishStudent($roomId) {
            if (!$roomId) {
                throw new BadRequestException("Room id should be provided");
            }

            $connection = self::initConnection();

            $stmt = $connection->prepare("DELETE FROM queues WHERE roomId=:roomId and active=:active");

            $success = $stmt->execute([
                "roomId" => $roomId,
                "active" => 1
            ]);

            if (!$success) {
                throw new NotFoundException();
            }

            $stmt = $connection->preapre("UPDATE rooms 
                                          SET currentTime=now(), isWaiting=:isWaiting 
                                          WHERE id=:id");

            $success = $stmt->execute([
                "isWaiting" => 0,
                "id" => $roomId
            ]);
            

        }

        private static function initConnection() {
            return (new DB())->getConnection();
        }
    }
?>