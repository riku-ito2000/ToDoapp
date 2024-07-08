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
