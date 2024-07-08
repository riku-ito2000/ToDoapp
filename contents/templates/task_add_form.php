<!-- templates/task_form.php -->
<div class="edit-form">
    <h1><?= $formTitle ?></h1>
    <form action="<?= $formAction ?>" method="post">
        <?php if (isset($todo['id'])): ?>
            <input type="hidden" name="id" value="<?= htmlspecialchars($todo['id']) ?>">
        <?php endif; ?>
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($todo['title'] ?? '') ?>" required><br><br>
        <label for="content">Content:</label><br>
        <textarea id="content" name="content" required><?= htmlspecialchars($todo['content'] ?? '') ?></textarea><br><br>
        <input type="submit" value="<?= $submitLabel ?>">
    </form>
    <a href="index.php">Back to ToDo List</a>
</div>
