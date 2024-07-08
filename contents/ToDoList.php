<?php
require_once 'connect_db.php';
require 'ToDo.php';

class ToDoList {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function add($todo) {
        $stmt = $this->pdo->prepare("INSERT INTO todosTable (title, content, created_at, updated_at) VALUES (?, ?, ?, ?)");
        $stmt->execute([$todo->getTitle(), $todo->getContent(), $todo->getCreatedAt(), $todo->getUpdatedAt()]);
    }


    public function update($todo) {
        $stmt = $this->pdo->prepare("UPDATE todosTable SET title = ?, content = ?, updated_at = ? WHERE id = ?");
        $stmt->execute([$todo->getTitle(), $todo->getContent(), $todo->getUpdatedAt(), $todo->getId()]);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare('SELECT * FROM todosTable WHERE id = ?');
        $stmt->execute([$id]);
        $todo = $stmt->fetch(PDO::FETCH_ASSOC);
        return new ToDo($todo['id'], $todo['title'], $todo['content'], $todo['created_at'], $todo['updated_at']);
    }

    public function getAllTodos($start, $tasksPerPage) {
        $stmt = $this->pdo->prepare('SELECT * FROM todosTable ORDER BY created_at DESC LIMIT :start, :tasksPerPage');
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':tasksPerPage', $tasksPerPage, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalTasks() {
        $stmt = $this->pdo->query('SELECT COUNT(*) FROM todosTable');
        return $stmt->fetchColumn();
    }
}
?>
