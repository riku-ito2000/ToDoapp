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
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function add($todo) {
        $stmt = $this->pdo->prepare("INSERT INTO todosTble (title, created_at, updated_at) VALUES (?, ?, ?)");
        $stmt->execute([$todo->getTitle(), $todo->getCreatedTime(), $todo->getUpdatedTime()]);
    }

    public function getAllTodos() {
        $stmt = $this->pdo->query('SELECT * FROM todosTable ORDER BY created_at DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

require_once 'config.php';

function connectDB() {
    echo TEST;
    // 接続情報を使用してデータベースに接続
    try {
        $pdo = new PDO("mysql:host=$databaseHost;dbname=$databaseName", $databaseUsername, $databasePassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("データベースに接続できません: " . $e->getMessage());
    }
}



// データベース接続を確立
$pdo = connectDB();

// ToDo を取得するクエリを実行
$stmt = $pdo->query('SELECT * FROM todosTable ORDER BY created_at DESC');

// データベース接続を確立
$pdo = connectDB();

// ToDoList インスタンスを作成
$todoList = new ToDoList($pdo);

// 全ての ToDo を取得
$allTodos = $todoList->getAllTodos();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDo App</title>
</head>
<body>
    <h1>ToDo List</h1>
    <ul>
        <?php foreach ($allTodos as $todo): ?>
            <li>
                <div>
                    <strong><?= htmlspecialchars($todo['title']) ?></strong> - 作成日時: <?= htmlspecialchars($todo['created_at']) ?>、更新日時: <?= htmlspecialchars($todo['updated_at']) ?>
                </div>
                <div>
                    <a href='edit.php?id=<?= $todo['id'] ?>'>Edit</a>
                    <a href='delete.php?id=<?= $todo['id'] ?>'>Delete</a>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>New Task</h2>
    <form action="" method="post">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" required><br><br>
        <label for="content">Content:</label><br>
        <textarea id="content" name="content" required></textarea><br><br>
        <input type="submit" value="Create">
    </form>
</body>
</html>
