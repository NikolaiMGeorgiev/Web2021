<?php
    date_default_timezone_set('Europe/Sofia');
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

            if (!$success && !empty($success)) {
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

            $activeId = -1;
            while ($row = $stmt->fetch()) {
                $studentsId[] = $row["userId"];
                if ($row["active"] == 1) {
                    $activeId = $row["userId"];
                }
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

            return [
                "students" => $students,
                "activeId" => $activeId
            ];
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

            $waitingTime = $room["waitingInterval"];
            $meetInterval = $room["meetInterval"];
            $isInMeeting = $room["state"];
            $currentTime = $room["currentTime"];

            if ($isInMeeting) {
                if ( (strtotime($currentTime) + ($meetInterval*self::secondsInMinute)) < strtotime(date('Y-m-d H:i:s')) ) {
                    return array([
                        "response" => "Времето за среща изтече",
                        "id" => "finished"
                    
                    ]);
                } else {
                    return array([
                        "response" => "Провежда се среща",
                        "id" => "meeting"
                    ]);
                }
            } else {
                if ( (strtotime($currentTime) + ($waitingTime*self::secondsInMinute)) < strtotime(date('Y-m-d H:i:s')) ) {
                    return array([
                        "response" => "Времето за чакане изтече",
                        "id" => "finished"
                    ]);
                } else {
                    return array([
                        "response" => "Изчакване",
                        "id" => "waiting"
                    ]);
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

                if (empty($row)) {
                    throw new BadRequestException("Empty queue");
                }

                $studentId = $row["userId"];
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

            $stmt = $connection->prepare(
                "UPDATE rooms SET state=:isInMeeting, currentTime=now() 
                WHERE id=:roomId"
            );

            $stmt->execute([
                "isInMeeting" => 1,
                "roomId" => $roomId
            ]);
        }

        public static function finishStudent($roomId) {
            if (!$roomId) {
                throw new BadRequestException("Room id should be provided");
            }

            $connection = self::initConnection();

            $stmt = $connection->prepare("DELETE FROM queues WHERE roomId=:roomId and active=:active");

            $stmt->execute([
                "roomId" => $roomId,
                "active" => 1
            ]);

            $stmt = $connection->prepare(
                "UPDATE rooms  SET currentTime=now(), state=:isInMeeting 
                WHERE id=:id"
            );

            $success = $stmt->execute([
                "isInMeeting" => 0,
                "id" => $roomId
            ]);
        }

        private static function initConnection() {
            return (new DB())->getConnection();
        }
    }
?>