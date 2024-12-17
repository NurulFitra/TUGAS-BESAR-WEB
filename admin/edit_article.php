<?php
session_start();
include '../includes/auth.php';
include '../includes/db.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}

$id = $_GET['id'];

// Ambil data artikel dan kategori
$article = $conn->query("SELECT * FROM articles WHERE id = $id")->fetch_assoc();
$categories = $conn->query("SELECT * FROM categories");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category_id = $_POST['category'];
    $link = $_POST['link'] ?? '';
    $image_name = $article['image']; // Default gambar lama jika tidak diubah

    // Proses unggah gambar jika ada file baru
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        $upload_dir = '../uploads/';

        // Validasi jenis file
        if (in_array($image['type'], $allowed_types)) {
            $image_name = 'article_' . uniqid() . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
            move_uploaded_file($image['tmp_name'], $upload_dir . $image_name);

            // Hapus gambar lama jika ada
            if (!empty($article['image']) && file_exists($upload_dir . $article['image'])) {
                unlink($upload_dir . $article['image']);
            }
        } else {
            $error = "Invalid image type. Only JPG and PNG are allowed.";
        }
    }

    // Update data artikel
    if (!isset($error)) {
        $stmt = $conn->prepare("UPDATE articles SET title = ?, content = ?, category_id = ?, image = ?, link = ? WHERE id = ?");
        $stmt->bind_param("ssissi", $title, $content, $category_id, $image_name, $link, $id);

        if ($stmt->execute()) {
            header("Location: manage_articles.php");
            exit;
        } else {
            $error = "Failed to update article.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Article</title>
    <link rel="stylesheet" href="../public/style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('../public/kopi.png');
            background-size: cover;
            background-attachment: fixed;
            color: #333;
        }

        header {
            background-color: #ffffff;
            border-bottom: 2px solid #0056b3;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
        }

        header img {
            height: 40px;
        }

        header h1 {
            font-size: 1.5em;
            color: #333;
        }

        header h1 span {
            color: #007bff;
        }

        nav {
            display: flex;
            justify-content: center;
            padding: 15px;
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        nav ul {
            display: flex;
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        nav ul li {
            margin: 0 15px;
        }
        nav ul li a {
            text-decoration: none;
            color: #0056b3; /* Warna teks yang konsisten */
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.2s;
        }
        nav ul li a:hover {
            background-color: #e1e4e8; /* Warna hover */
            transform: scale(1.05);
        }

        main {
            padding: 20px;
            display: flex;
            justify-content: center;
        }

        .card {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
        }

        form label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        form input, form textarea, form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        form button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #0056b3;
        }

        footer {
            background-color: #111;
            color: white;
            text-align: center;
            padding: 20px;
        }
        .highlight {
            color: #007bff; /* Ganti warna sesuai kebutuhan */
            
        }
    </style>
</head>
<body>
<header style="
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    background-color: #ffffff; /* Warna putih */
    border-bottom: 2px solid #0056b3; /* Garis bawah biru */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Bayangan lembut */
">
    <div style="display: flex; align-items: center;">
        <img src="../public/logo.png" alt="Logo" style="height: 40px; margin-right: 10px;">
        <h1 style="margin: 0; font-size: 1.5em; color: #333333;">Berpikir Kritis, <span class="highlight">Bertindak Cerdik</span>|</h1>
    </div>
    <div>
        <a href="Dashboard.php" style="
            color: #0056b3; 
            text-decoration: none; 
            font-weight: bold; 
            margin-right: 20px;
        ">Dashboard</a>

    </div>
</header>


<nav>
    <ul>
        <li><a href="manage_articles.php"style="font-weight: bold; color: #333;">Manage Articles</a></li>
        <li><a href="manage_categories.php">Manage Categories</a></li>
        <li><a href="manage_comments.php">Manage Comments</a></li>
    </ul>
</nav>

<main>
    <div class="card">
        <h2>Edit Article</h2>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="POST" enctype="multipart/form-data">
            <label>Title:</label>
            <input type="text" name="title" value="<?= htmlspecialchars($article['title']) ?>" required>

            <label>Content:</label>
            <textarea name="content" required><?= htmlspecialchars($article['content']) ?></textarea>

            <label>Category:</label>
            <select name="category" required>
                <?php while ($category = $categories->fetch_assoc()) { ?>
                    <option value="<?= $category['id'] ?>" <?= $article['category_id'] == $category['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category['name']) ?>
                    </option>
                <?php } ?>
            </select>

            <label>Link (optional):</label>
            <input type="url" name="link" value="<?= htmlspecialchars($article['link'] ?? '') ?>" placeholder="Enter article link">

            <label>Image (optional):</label>
            <input type="file" name="image" accept="image/png, image/jpeg, image/jpg">
            <?php if (!empty($article['image'])) { ?>
                <p>Current Image:</p>
                <img src="../uploads/<?= htmlspecialchars($article['image']) ?>" alt="Current Image" style="max-width: 200px;">
            <?php } ?>

            <button type="submit">Update Article</button>
        </form>
    </div>
</main>

<footer>
    <p>&copy; 2024 inspiRAsi. All rights reserved.</p>
</footer>
</body>
</html>
