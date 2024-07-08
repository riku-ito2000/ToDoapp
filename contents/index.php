<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete ToDo</title>
    <link rel="stylesheet" href="style.css"> <!-- CSSファイルをリンク -->
    <script>
        function confirmDelete() {
            return confirm("Do you wish to remove this?");
        }
    </script>
</head>
<body>

<?php
// 必要なファイルをインクルード
require_once 'connect_db.php';
require 'ToDoList.php';

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

// HTMLの出力
require_once 'templates/header.php'; // ヘッダー部分をインクルード
?>

<!-- ToDoリストの表示 -->
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
<!-- ページネーションリンクの表示 -->
<?php include 'templates/pagination.php'; ?>
<?php require_once 'templates/footer.php'; // フッター部分をインクルード ?>

</body>
</html>