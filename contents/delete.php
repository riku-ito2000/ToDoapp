<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete ToDo</title>
    <style>
        .task-container {
            border: 2px solid #ccc;
            padding: 10px;
            background-color: #f9f9f9;
            margin-bottom: 20px;
        }
    </style>
    <script>
        function confirmDelete() {
            return confirm("本当に削除しますか？");
        }
    </script>
</head>
<body>
    <?php
    // データベース接続情報を含むファイルを読み込む
    require_once 'connect_db.php';

    // データベース接続を確立
    $pdo = connectDB();

    // URL パラメータから削除する ToDo の ID を取得
    $id = $_GET['id'];

    // データベースから ToDo の情報を取得
    $stmt = $pdo->prepare("SELECT * FROM todosTable WHERE id = ?");
    $stmt->execute([$id]);
    $todo = $stmt->fetch(PDO::FETCH_ASSOC);

    // ToDo が見つからない場合はエラーとして処理
    if (!$todo) {
        die("ToDoが見つかりませんでした。");
    }
    ?>
    
    <h1>本当にタスクを削除しますか？</h1>
    
    <!-- タスクを枠で囲んで表示 -->
    <div class="task-container">
        <p><strong>Title:</strong> <?= htmlspecialchars($todo['title']) ?></p>
        <p><strong>Content:</strong> <?= nl2br(htmlspecialchars($todo['content'])) ?></p>
        <p><strong>作成日時:</strong> <?= htmlspecialchars($todo['created_at']) ?></p>
        <p><strong>更新日時:</strong> <?= htmlspecialchars($todo['updated_at']) ?></p>
    </div>
    
    <form action="" method="post">
        <input type="submit" name="confirm_delete" value="削除する" onclick="return confirmDelete();">
        <button type="button" onclick="location.href='index.php'">キャンセル</button>
    </form>
    
    <?php
    // POST メソッドで送信された場合の削除処理
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['confirm_delete'])) {
            $stmt = $pdo->prepare("DELETE FROM todosTable WHERE id = ?");
            $stmt->execute([$id]);

            // 削除後に index.php にリダイレクト
            header("Location: index.php");
            exit();
        }
    }
    ?>
</body>
</html>


