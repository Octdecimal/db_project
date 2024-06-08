<?php 
include 'db.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>筆記</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>筆記</h1>
        <nav>
            <ul>
                <li><a href="index.php">首頁</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>筆記</h2>
        <form action="note_process.php" method="POST">
            <label for="title">標題:</label>
            <input type="text" id="title" name="title" required><br>
            <label for="content">內容:</label>
            <textarea id="content" name="content" required></textarea><br>
            <input type="submit" value="Save">
        </form>
    </main>
</body>
</html>