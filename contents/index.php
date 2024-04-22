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
    private $todos = [];

    public function add($todo) {
        $this->todos[] = $todo;
    }

}

// データベース接続を確立する関数
function connectDB() {
    // config.php ファイルをインクルード
    require_once 'config.php';
    
    // 接続情報を使用してデータベースに接続
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("データベースに接続できません: " . $e->getMessage());
    }
}

// データベース接続を確立
require_once 'config.php';
$pdo = connectDB();

// ここからデータベースを使用した処理を記述
?>

<?php
// ToDo を取得するクエリを実行
$stmt = $pdo->query('SELECT * FROM todos ORDER BY created_at DESC');

// 取得した ToDo を表示する
echo "<ul>";
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    echo "<li>";
        echo "<div>"; // 情報をラップするdiv要素
            echo "<strong>" . htmlspecialchars($row['title']) . "</strong> - 作成日時: " . htmlspecialchars($row['created_at']) . "、更新日時: " . htmlspecialchars($row['updated_at']);
        echo "</div>"; 

    // 編集ボタンと削除ボタンを含むdiv要素。このdiv要素が右側に配置。
        echo "<div>";
            echo "<a href='edit.php?id=" . $row['id'] . "'>編集</a>";
            echo "<a href='delete.php?id=" . $row['id'] . "'>削除</a>";
        echo "</div>";

    echo "</li>";
}
echo "</ul>";


// データベース接続(これ何回もいるのか疑問、)
require_once 'config.php';

// フォームが送信されたかどうかを確認
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // フォームから入力されたデータを取得
    $title = $_POST['title'];
    $content = $_POST['content'];

    // データベースに新しいToDoを追加
    $stmt = $pdo->prepare("INSERT INTO todos (title, content) VALUES (?, ?)");
    $stmt->execute([$title, $content]);

    // 新しいToDoが追加された後、リダイレクトするなどの処理を行う
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Task</title>
</head>
<body>
    <h1>New Task</h1>
    <form action="" method="post">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" required><br><br>
        <label for="content">Content:</label><br>
        <textarea id="content" name="content" required></textarea><br><br>
        <input type="submit" value="Create">
    </form>
</body>
</html>
