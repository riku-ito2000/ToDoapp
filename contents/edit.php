<!DOCTYPE html>
<html lang="en">
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Todo</title>
</head>

<body>
    <h1>Edit Todo</h1>
    <form action="a.php" method="post">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" required><br><br>
        <label for="content">Content:</label><br>
        <textarea id="content" name="content" required></textarea><br><br>
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
        <input type="submit" value="Update">
    </form>
</body>