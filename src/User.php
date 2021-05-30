<?php

class User implements JsonSerializable
{
    private $username;


    private $email;

    public function __construct($username, $password, $email)
    {
        $this->username = $username;
        $this->password = $password;
        $this->name = $email;
    }
    
    public function getUsername() : string{
        return $this->username;
    }

    public function getEmail() : string {
        return $this->email;

    }

    public function jsonSerialize(): array {
        $fieldsToSerialize = ["username", "email"];

        $jsonArray = [];

        foreach ($fieldsToSerialize as $field) {
            $jsonArray[$field] = $this->$field;
        }

        return $jsonArray;
    }
}