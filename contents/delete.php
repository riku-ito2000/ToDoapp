<?php
// データベース接続情報を含むファイルを読み込む
require_once 'connect_db.php';

// データベース接続を確立
$pdo = connectDB();

// URL パラメータから削除する ToDo の ID を取得
$id = $_GET['id'];

// データベースで対象の ToDo を削除
$stmt = $pdo->prepare("DELETE FROM todosTable WHERE id = ?");
$stmt->execute([$id]);

// 削除後に index.php にリダイレクト
header("Location: index.php");
exit();
?>
