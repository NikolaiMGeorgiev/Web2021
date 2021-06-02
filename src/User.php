<?php

class User implements JsonSerializable
{
    private $username;
    private $email;

    public function __construct(string $username, string $email)
    {
        $this->username = $username;
        $this->email = $email;
    }
    
    public function getUsername() : string {
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