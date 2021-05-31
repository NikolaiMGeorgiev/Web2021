<?php
    require_once("User.php");

    class Students extends User {
        private $fn;

        private $year;

        private $degree;

        public function __construct(string $username, string $email, int $fn, int $year, 
                string $degree ) {
            parent::__construct($username, $email);

            $this->fn = $fn;
            $this->year = $year;
            $this->degree = $degree;
        }

        public function getFn() : int {
            return $this->fn;
        }

        public function getYear() : int {
            return $this->year;
        }

        public function getDegree() : string {
            return $this->degree;
        }

         // Override
         public function jsonSerialize(): array {
            $fieldsToSerialize = ["username", "email", "fn", "year", "degree"];
    
            $jsonArray = [];
    
            foreach ($fieldsToSerialize as $field) {
                $jsonArray[$field] = $this->$field;
            }
    
            return $jsonArray;
        }
    }
?>