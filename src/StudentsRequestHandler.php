<?php

    require_once("AppBootStrap.php");
    
    AppBootStrap::init();

    class StudentRequestHandler {
        const studentId = 1;

        public static function getAllStudents() {

            $connection = self::initConnection();

            $stmt = $connection->query(
                "SELECT name, user.id, email, fn, year, degree
                 FROM 
                 users INNER JOIN students_details ON users.id=students_details.userId");
            
            $students = [];

            while ($row = $stmt->fetch()) {
                $students[] = new Student($row["name"], $row["email"], $row["id"], $row["fn"], $row["year"], $row["degree"] );
            }

            return $students;
        }

        private static function initConnection() {
            return (new DB())->getConnection();
        }
    }
?>