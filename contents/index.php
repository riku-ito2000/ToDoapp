<?php

class ToDo {
    private $id;
    private $title;
    private $createdTime;
    private $updatedTime;

    public function __construct($id, $title, $createdTime, $updatedTime) {
        $this->id = $id;
        $this->title = $title;
        $this->createdTime = $createdTime;
        $this->updatedTime = $updatedTime;
    }

    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getCreatedTime() {
        return $this->createdTime;
    }

    public function getUpdatedTime() {
        return $this->updatedTime;
    }
}

class ToDoList {
    private $todos = [];

    public function add($todo) {
        $this->todos[] = $todo;
    }

?>