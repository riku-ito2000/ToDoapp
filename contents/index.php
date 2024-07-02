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
        $stmt = $this->pdo->prepare("INSERT INTO todosTable (title, created_at, updated_at) VALUES (?, ?, ?)");
        $stmt->execute([$todo->getTitle(), $todo->getCreatedTime(), $todo->getUpdatedTime()]);
    }

    public function getAllTodos() {
        $stmt = $this->pdo->query('SELECT * FROM todosTable ORDER BY created_at DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// 必要なファイルをインクルード
require_once('connect_db.php');

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
    <title>ToDo List</title>
    <style>
        .task {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }
        .task strong {
            font-size: 18px;
        }
        .task .details {
            margin-top: 5px;
            font-size: 14px;
            color: #666;
        }
        .task p {
            margin-top: 10px;
        }
        .green-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 16px;
            transition: background-color 0.3s ease;
            margin-bottom: 20px; /* ボタンとタスクの間隔 */
        }
        .green-button:hover {
            background-color: #45a049;
        }
        .header {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 36px;
        }
        .btn-edit, .btn-delete {
            display: inline-block;
            padding: 8px 15px;
            background-color: #008CBA;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            margin-right: 10px;
            transition: background-color 0.3s ease;
        }
        .btn-edit:hover, .btn-delete:hover {
            background-color: #005f6b;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>ToDo List</h1>
    </div>
    <a href="task_add.php" class="green-button">New Task</a>
    <?php foreach ($allTodos as $todo): ?>
        <div class="task">
            <strong><?= htmlspecialchars($todo['title']) ?></strong>
            <div class="details">
                Created: <?= htmlspecialchars($todo['created_at']) ?>, Updated: <?= htmlspecialchars($todo['updated_at']) ?>
            </div>
            <p><?= nl2br(htmlspecialchars($todo['content'])) ?></p>
            <div>
                <button class="btn-edit" onclick="location.href='edit.php?id=<?= $todo['id'] ?>'">Edit</button>
                <button class="btn-delete" onclick="location.href='delete.php?id=<?= $todo['id'] ?>'">Delete</button>
            </div>
        </div>
    <?php endforeach; ?>
</body>
</html>



