<?php
session_start();
include '../includes/auth.php'; // Pastikan user login
include '../includes/db.php';  // Pastikan koneksi ke database sudah disertakan

// Mendapatkan ID artikel dari URL
if (isset($_GET['id'])) {
    $article_id = $_GET['id'];

    // Query untuk mengambil data artikel berdasarkan ID dan kategori terkait
    $article_query = "
        SELECT a.*, c.name AS category_name
        FROM articles a
        LEFT JOIN categories c ON a.category_id = c.id
        WHERE a.id = $article_id
    ";
    $article_result = mysqli_query($conn, $article_query);

    // Pastikan artikel ditemukan
    if (mysqli_num_rows($article_result) > 0) {
        $article = mysqli_fetch_assoc($article_result);
    } else {
        echo "Artikel tidak ditemukan.";
        exit;
    }

    // Query untuk mengambil komentar terkait artikel ini
   // Query untuk mengambil komentar terkait artikel ini beserta username
    $comments_query = "
    SELECT comments.comment, users.username
    FROM comments
    LEFT JOIN users ON comments.user_id = users.id
    WHERE comments.article_id = $article_id
    ";
    $comments = mysqli_query($conn, $comments_query);
} else {
    echo "Invalid article ID.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Article</title>
    <link rel="stylesheet" href="../public/style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
            color: #333;
        }

        header {
            background-color: #0056b3;
            color: white;
            padding: 20px;
            text-align: center;
        }

        main {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 2em;
            color: #0056b3;
        }

        .article-image {
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .external-article {
            margin-bottom: 30px;
        }

        .external-article iframe {
            width: 100%;
            height: 600px;
            border-radius: 8px;
            border: none;
        }

        .meta-info {
            font-size: 0.9em;
            color: #666;
            margin-bottom: 20px;
        }

        .content {
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .comments-section {
            margin-top: 40px;
        }

        .comments-section ul {
            list-style-type: none;
            padding: 0;
        }

        .comments-section li {
            background-color: #f9f9f9;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .comment-author {
            font-weight: bold;
            color: #0056b3;
        }

        .comment-form textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            resize: none;
            font-size: 1em;
        }

        .comment-form button {
            background-color: #0056b3;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
        }

        .comment-form button:hover {
            background-color: #0046a0;
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
        <a href="dashboard.php" style="
            color: #0056b3; 
            text-decoration: none; 
            font-weight: bold; 
            margin-right: 20px;
        ">Dashboard</a>

    </div>
    </header>

    <main>
        <!-- Menampilkan Gambar Artikel -->
        <?php if (!empty($article['image'])): ?>
            <img src="../uploads/articles/<?= htmlspecialchars($article['image']) ?>" alt="<?= htmlspecialchars($article['title']) ?>" class="article-image">
        <?php endif; ?>

        <!-- Menampilkan Artikel Eksternal dalam bentuk iframe -->
        <?php if (!empty($article['link'])): ?>
            <div class="external-article">
                <h2>External Article</h2>
                <iframe src="<?= htmlspecialchars($article['link']) ?>" width="100%" height="600" frameborder="0"></iframe>
            </div>
        <?php endif; ?>

        <!-- Menampilkan Detail Artikel -->
        <h1><?= htmlspecialchars($article['title']) ?></h1>
        <p class="meta-info">
            <strong>Category:</strong> <?= htmlspecialchars($article['category_name']) ?> |
            <strong>Published on:</strong> <?= htmlspecialchars($article['created_at']) ?>
        </p>
        <div class="content">
            <?= nl2br(htmlspecialchars($article['content'])) ?>
        </div>

        <!-- Menampilkan Komentar -->
        <!-- Menampilkan Komentar -->
    <div class="comments-section">
        <h2>Comments</h2>
        <ul>
            <?php if ($comments && mysqli_num_rows($comments) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($comments)): ?>
                    <li>
                        <span class="comment-author">
                            <?= htmlspecialchars($row['username'] ?? 'Anonymous') ?>
                        </span>: 
                        <?= htmlspecialchars($row['comment']) ?>
                    </li>
                <?php endwhile; ?>
            <?php else: ?>
                <li>No comments yet. Be the first to comment!</li>
            <?php endif; ?>
        </ul>
    </div>


        <!-- Form Menambahkan Komentar -->
        <!-- Form Menambahkan Komentar -->
        <div class="comment-form">
            <form action="comment.php" method="post">
                <textarea 
                    name="comment" 
                    placeholder="Write a comment..." 
                    required 
                    oninvalid="this.setCustomValidity('Komentar tidak boleh kosong!')" 
                    oninput="this.setCustomValidity('')"
                ></textarea>
                <input type="hidden" name="article_id" value="<?= $article_id ?>">
                <button type="submit">Submit Comment</button>
            </form>
        </div>
    </main>
</body>
</html>
