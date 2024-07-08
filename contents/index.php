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



// 必要なファイルをインクルード
require_once('connect_db.php');

// データベース接続を確立
$pdo = connectDB();

// ToDoList インスタンスを作成
$todoList = new ToDoList($pdo);

// 1ページあたりのタスク数
$tasksPerPage = 5;

// 現在のページ番号を取得（デフォルトは1ページ目）
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// ページ番号から取得開始位置を計算
$start = ($page - 1) * $tasksPerPage;

// 全体のタスク数を取得
$totalTasks = $todoList->getTotalTasks();

// 総ページ数を計算
$totalPages = ceil($totalTasks / $tasksPerPage);

// 現在のページに表示するタスクを取得
$allTodos = $todoList->getAllTodos($start, $tasksPerPage);
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
        .pagination {
            text-align: center;
            margin-top: 20px;
        }
        .pagination a, .pagination span {
            display: inline-block;
            padding: 8px 16px;
            text-decoration: none;
            margin: 0 4px;
        }
        .pagination a {
            background-color: #4CAF50;
            color: white;
            border-radius: 4px;
        }
        .pagination a:hover {
            background-color: #45a049;
        }
        .pagination span {
            background-color: #ddd;
            color: #333;
            border-radius: 4px;
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

    <!-- ページネーションリンクを表示 -->
    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <?php if ($i == $page): ?>
                <span><?= $i ?></span>
            <?php else: ?>
                <a href="?page=<?= $i ?>"><?= $i ?></a>
            <?php endif; ?>
        <?php endfor; ?>
    </div>
</body>
</html>



