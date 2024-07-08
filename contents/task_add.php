<?php
require 'connect_db.php';
require 'ToDo.php';
require 'ToDoList.php';

// データベース接続を確立
$pdo = connectDB();
$todoList = new ToDoList($pdo);

// フォームから送信されたデータがある場合は、追加処理を行う
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $createdTime = date('Y-m-d H:i:s');
    $updatedTime = $createdTime;

    // バリデーション
    $errors = [];

    if (empty($title)) {
        $errors[] = 'タイトルを入力してください。';
    }

    if (empty($content)) {
        $errors[] = 'コンテンツを入力してください。';
    }

    // エラーチェック
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
    } else {
        // 新しいToDoを追加
        $todo = new ToDo(null, $title, $content, $createdTime, $updatedTime);
        $todoList->add($todo);

        // 成功メッセージまたはリダイレクト
        header('Location: index.php');
        exit();
    }
}
// フォームの設定
$formTitle = 'New Task';
$formAction = 'task_add.php';
$submitLabel = 'Create';

include 'templates/header.php';
include 'templates/task_form.php';
include 'templates/footer.php';
?>