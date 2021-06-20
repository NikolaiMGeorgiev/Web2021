<?php

    require_once("AppBootStrap.php");

    AppBootStrap::init();

    class ScheduleRequestHandler {

        public static function getSchedule($roomId) {
            if (!$roomId) {
                throw new BadRequestException("Room id should be provided");
            }

            $connection = self::initConnection();

            $stmt = $connection->prepare("SELECT * 
                                          FROM schedule JOIN users ON schedule.userId=users.id
                                          WHERE roomId=:roomId ORDER BY place ASC");

            $stmt->execute([
                "roomId" => $roomId
            ]);

            $schedule = [];

            while ($row = $stmt->fetch()) {
                $schedule[] = ["id" => $row["users.id"], "name" => $row["users.name"]];
            }

            return $schedule;
        }

        private static function initConnection() {
            return (new DB())->getConnection();
        }

    }

?>