<?php

class Room implements JsonSerializable
{
    private $name;
    private $waitingInterval;
    private $meetInterval;
    private $start;

    public function __construct($name, $waitingInterval, $meetInterval, $start)
    {
        $this->name = $name;
        $this->waitingInterval = $waitingInterval;
        $this->meetInterval = $meetInterval;
        $this->start = $start;
    }
    
    public function getName() {
        return $this->name;
    }

    public function getWaitingInterval() {
        return $this->waitingInterval;
    }

    public function getMeetInterval() {
        return $this->meetInterval;
    }

    public function getStart() {
        return $this->start;
    }

    //Override
    public function jsonSerialize() {
        $fieldsToSerialize = ["name", "waitingInterval", "meetInterval", "start"];

        $jsonArray = [];

        foreach ($fieldsToSerialize as $field) {
            $jsonArray[$field] = $this->$field;
        }

        return $jsonArray;
    }
}