<?php
require_once 'connect_db.php';

// データベース接続を確立
$pdo = connectDB();

// フォームから送信されたデータがある場合は、更新処理を行う
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
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
    // GETリクエストの場合は、編集フォームを表示
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
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }
        .edit-form {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .edit-form label {
            font-weight: bold;
        }
        .edit-form input[type="text"],
        .edit-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }
        .edit-form textarea {
            height: 150px; /* テキストエリアの高さを調整 */
        }
        .edit-form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .edit-form input[type="submit"]:hover {
            background-color: #45a049;
        }
        .edit-form a {
            display: inline-block;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
            font-size: 16px;
        }
        .edit-form a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="edit-form">
        <h1>Edit ToDo</h1>

        <form action="edit.php" method="post">
            <input type="hidden" name="id" value="<?= htmlspecialchars($todo['id']) ?>">
            <label for="title">Title:</label><br>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($todo['title']) ?>" required><br><br>
            <label for="content">Content:</label><br>
            <textarea id="content" name="content" required><?= htmlspecialchars($todo['content']) ?></textarea><br><br>
            <input type="submit" value="Update">
        </form>
        
        <a href="index.php">Back to ToDo List</a>
    </div>
</body>
</html>
