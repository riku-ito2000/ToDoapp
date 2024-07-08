<?php
require 'connect_db.php';
require 'index.php';

// データベース接続を確立
$pdo = connectDB();
$todoList = new ToDoList($pdo);

// フォームから送信されたデータがある場合は、更新処理を行う
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_GET['id'] ?? '';
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $updatedTime = date('Y-m-d H:i:s');

    // バリデーション
    $errors = [];

    if (empty($title)) {
        $errors[] = 'タイトルを入力してください。';
    }

    if (empty($content)) {
        $errors[] = 'コンテンツを入力してください。』';
    }

    // エラーチェック
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
    } else {
        // データベースを更新
        $stmt = $pdo->prepare("UPDATE todosTable SET title = ?, content = ?, updated_at = ? WHERE id = ?");
        $stmt->execute([$title, $content, $updatedTime, $id]);

        // 成功メッセージまたはリダイレクト
        header('Location: index.php'); // インデックスページにリダイレクト
        exit();
    }
} else {
    $id = $_GET['id'] ?? '';

    // ToDoの情報を取得
    $stmt = $pdo->prepare("SELECT * FROM todosTable WHERE id = ?");
    $stmt->execute([$id]);
    $todo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$todo) {
        echo "指定されたToDoが見つかりません。";
        exit();
    }
}
// フォームの設定

include 'templates/header.php';
include 'templates/footer.php';
?>
