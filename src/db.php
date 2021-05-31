<?php

    class DB {

        private $connection;

        function __construct() {
            
            $host = "localhost";
            $dbname = "meetup";
            $username = "root";
            $password = "";

            $dsn = "mysql:host=$host;dbname=$dbname";

            $this->connection = new PDO($dsn, $username, $password);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }


        function getConnection() {
            return $this->connection;
        }

    }

?>