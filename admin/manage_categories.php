<?php
session_start();
include '../includes/auth.php';
include '../includes/db.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}

// Query untuk mendapatkan semua kategori dan jumlah artikel
$query = "
    SELECT c.id, c.name, COUNT(a.id) AS article_count
    FROM categories c
    LEFT JOIN articles a ON c.id = a.category_id
    GROUP BY c.id, c.name
";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
    <link rel="stylesheet" href="../public/style.css"> <!-- Pastikan path sesuai -->
    <style>
        /* Tambahan CSS agar mirip dengan Manage Articles */
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
            margin: 15px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 1000px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        table th {
            background-color: #007bff;
           
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        a.button {
            display: inline-block;
            padding: 8px 12px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 0.9em;
        }

        a.button:hover {
            background-color: #0056b3;
        }

        footer {
            background-color: #111;
            color: white;
            display: flex;
            justify-content: center;
            padding: 20px;
        }

        footer p {
            color: #ccc;
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

<nav>
    <ul>
        <li><a href="manage_articles.php">Manage Articles</a></li>
        <li><a href="manage_categories.php"style="font-weight: bold; color: #333;">Manage Categories</a></li>
        <li><a href="manage_comments.php">Manage Comments</a></li>
    </ul>
</nav>

<main>
    <div class="card">
        <h2>Manage Categories</h2>
        <a href="add_category.php" class="button" style="margin-bottom: 10px; display: inline-block;">Add New Category</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Kategori</th>
                    <th>Jumlah Artikel</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($category = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= htmlspecialchars($category['id']) ?></td>
                        <td><?= htmlspecialchars($category['name']) ?></td>
                        <td><?= htmlspecialchars($category['article_count']) ?></td>
                        <td>
                            <a href="edit_category.php?id=<?= $category['id'] ?>" class="button" style="background-color:#0056b3;">Edit</a>
                            <a href="delete_category.php?id=<?= $category['id'] ?>" class="button" style="background-color:rgb(157, 182, 188);" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</main>
</body>
</html>
