<?php
session_start();
include '../includes/auth.php'; // Pastikan user admin
include '../includes/db.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}
$latest_articles_query = "
    SELECT title, created_at, id 
    FROM articles 
    ORDER BY created_at DESC 
    LIMIT 3
";
$latest_articles_result = mysqli_query($conn, $latest_articles_query);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../public/style.css"> <!-- Pastikan path sesuai -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('../public/kopi.png'); /* Path gambar Anda */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #333;
        }
        header {
            background-color: #0056b3; /* Warna biru yang konsisten */
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        h1 {
            margin: 0;
            animation: fadeIn 1s;
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
            flex-direction: column;
            align-items: center;
        }
        .card {
            background-color: white;
            padding: 20px;
            margin: 15px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            text-align: center;
            animation: slideIn 0.5s forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .image-container {
            margin: 20px 0;
        }
        .image-container img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
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
        <h1 style="margin: 0; font-size: 1.5em; color: #333333;">Berpikir Kritis, <span class="highlight">Tindak Cerdik</span>|</h1>
    </div>
    <div>
        <a href="../public/logout.php" style="
            color: #0056b3; 
            text-decoration: none; 
            font-weight: bold; 
            margin-right: 20px;
        ">Log Out</a>

    </div>
    </header>
    <nav>
        <ul>
            <li><a href="manage_articles.php">Manage Articles</a></li>
            <li><a href="manage_categories.php">Manage Categories</a></li>
            <li><a href="manage_comments.php">Manage Comments</a></li>

        </ul>
    </nav>
    <main>
        <div class="card">
            <h2>Welcome, Admin!</h2>
            <p>Tetap semangat, Admin! Kata-kata yang kamu tulis hari ini mungkin menjadi alasan seseorang menemukan harapan atau pengetahuan baru esok hari.</p>
        </div>
    <!-- Tambahkan ini di bagian Latest Articles -->
    <div class="card">
        <h3>Latest Articles</h3>
        <?php if (mysqli_num_rows($latest_articles_result) > 0): ?>
            <ul style="list-style: none; padding: 0;">
                <?php while ($article = mysqli_fetch_assoc($latest_articles_result)): ?>
                    <li style="margin: 10px 0;">
                        <a href="edit_article.php?id=<?= $article['id'] ?>" style="
                            text-decoration: none; 
                            color: #0056b3; 
                            font-weight: bold; 
                            font-size: 1.1em;
                        ">
                            <?= htmlspecialchars($article['title']) ?>
                        </a>
                        <br>
                        <small style="color: #666;">
                            Published on: <?= date('F d, Y', strtotime($article['created_at'])) ?>
                        </small>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No articles found.</p>
        <?php endif; ?>
        </div>
            
        </div>
    </main>
</body>
</html>