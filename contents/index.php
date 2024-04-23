<?php

// ToDo クラスの定義
class ToDo {
    private $id;
    private $title;
    private $createdTime;
    private $updatedTime;

    // コンストラクタ
    public function __construct($id, $title, $createdTime, $updatedTime) {
        $this->id = $id;
        $this->title = $title;
        $this->createdTime = $createdTime;
        $this->updatedTime = $updatedTime;
    }

    // メソッド：ToDoのIDを取得
    public function getId() {
        return $this->id;
    }

    // メソッド：ToDoのタイトルを取得
    public function getTitle() {
        return $this->title;
    }

    // メソッド：ToDoの作成日時を取得
    public function getCreatedTime() {
        return $this->createdTime;
    }

    // メソッド：ToDoの更新日時を取得
    public function getUpdatedTime() {
        return $this->updatedTime;
    }
}

// ToDoList クラスの定義
class ToDoList {
    private $todos = [];

    // メソッド：ToDoを追加
    public function add($todo) {
        $this->todos[] = $todo;
    }

    // メソッド：ToDoリストを取得
    public function getTodos() {
        return $this->todos;
    }
}

// config.php ファイルをインクルード
require_once __DIR__.'/config/config.php';

// データベース接続を確立する関数
function connectDB() {
    global $host, $dbname, $username, $password;
    
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
$pdo = connectDB();

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

<?php




echo "<ul>";
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<li>";
        echo "<div>"; // 情報をラップするdiv要素
            echo "<strong>" . htmlspecialchars($row['title']) . "</strong> - 作成日時: " . htmlspecialchars($row['created_at']) . "、更新日時: " . htmlspecialchars($row['updated_at']);
        echo "</div>"; 

        // 編集ボタンへのリンクを追加
        echo "<div>";
            echo "<a href='edit.php?id=" . $row['id'] . "'>Edit</a>";
            echo "<a href='delete.php?id=" . $row['id'] . "'>Delete</a>";
        echo "</div>";

    echo "</li>";
}
echo "</ul>";




require_once 'config.php'; // データベース接続情報を含むファイルを読み込む

// URL パラメータから削除する ToDo の ID を取得
$id = $_GET['id'];

// データベースで対象の ToDo を削除
$stmt = $pdo->prepare("DELETE FROM todos WHERE id = ?");
$stmt->execute([$id]);

// 削除後に index.php にリダイレクト
header("Location: index.php");
exit();

?>
