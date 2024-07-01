<?php
require_once 'edit.php';
$title = $_POST['title'];
$content = $_POST['content'];
$id = $_POST['id'];

// データベースで対象の ToDo を更新
require_once 'connect_db.php';
$pdo=connectDB();

$stmt = $pdo->prepare("UPDATE todosTable SET title = ?, content = ? WHERE id = ?");
$stmt->execute([$title, $content, $id]);

header("Location: index.php");
exit();
