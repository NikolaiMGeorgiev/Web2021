<?php

    require_once("AppBootStrap.php");

    AppBootStrap::init();

    class ScheduleRequestHandler {

        public static function getSchedule($roomId) {
            if (!$roomId) {
                throw new BadRequestException("Room id should be provided");
            }

            $connection = self::initConnection();

            $stmt = $connection->prepare("SELECT * FROM schedule WHERE roomId=:roomId ORDER BY place ASC");

            $stmt->execute([
                "roomId" => $roomId
            ]);

            $studentsId = [];

            while ($row = $stmt->fetch()) {
                $studentsId[] = $row["userId"];
            }

            $schedule = [];

            foreach ($studentsId as $studentId) {
                $stmt = $connection->prepare("SELECT * FROM users WHERE id=:id");

                $stmt->execute([
                    "id" => $studentId
                ]);

                $row = $stmt->fetch();

                $schedule[] = ["id" => $row["id"], "name" => $row["name"]];
            }

            return $schedule;
        }

        private static function initConnection() {
            return (new DB())->getConnection();
        }

    }

?>