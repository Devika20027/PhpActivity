<?php
// Database connection settings
$host = 'localhost';
$dbname = 'dynamic_app';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle form submission (add new post)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $pdo->prepare("INSERT INTO posts (title, content) VALUES (?, ?)");
    $stmt->execute([$title, $content]);

    // Redirect to avoid form resubmission issue on refresh
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Fetch posts from the database
$query = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
$posts = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic PHP Application</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .post {
            background-color: #fff;
            padding: 15px;
            margin: 15px auto;
            border-radius: 8px;
            max-width: 600px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .post h2 {
            margin-top: 0;
        }
        .post small {
            color: #777;
        }
        .form-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<h1>Dynamic Content in PHP</h1>

<!-- Form to Add New Post -->
<div class="form-container">
    <h2>Add a New Post</h2>
    <form method="POST" action="">
        <input type="text" name="title" placeholder="Title" required>
        <textarea name="content" placeholder="Content" rows="5" required></textarea>
        <button type="submit">Add Post</button>
    </form>
</div>

<!-- Displaying Posts -->
<?php if (count($posts) > 0): ?>
    <?php foreach ($posts as $post): ?>
        <div class="post">
            <h2><?php echo htmlspecialchars($post['title']); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
            <small>Posted on: <?php echo $post['created_at']; ?></small>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p style="text-align: center;">No posts found.</p>
<?php endif; ?>

</body>
</html>
