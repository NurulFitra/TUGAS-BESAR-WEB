<?php
session_start();
include '../includes/auth.php';
include '../includes/db.php';

// Pastikan hanya admin yang bisa mengakses
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}

$query = "SELECT a.id, a.title, a.created_at, c.name AS category 
          FROM articles a 
          LEFT JOIN categories c ON a.category_id = c.id";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Articles</title>
    <link rel="stylesheet" href="../public/style.css"> <!-- Pastikan path sesuai -->
    <style>
        /* Global Styles (mirip file pertama) */
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
            margin: 0;
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
            max-width: 800px;
            animation: fadeIn 1s;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
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

        .action-btn {
            display: inline-block;
            padding: 5px 10px;
            margin: 5px;
            text-decoration: none;
            color: white;
            border-radius: 5px;
            font-size: 0.9em;
            transition: background-color 0.3s ease;
        }

        .edit-btn { background-color:#0056b3; }
        .edit-btn:hover { background-color:rgb(132, 142, 237); }

        .delete-btn { background-color:rgb(157, 182, 188); }
        .delete-btn:hover { background-color:rgb(102, 94, 95); }

        .add-btn {
            background-color: #007bff ;
            padding: 10px 15px;
            text-decoration: none;
            color: white;
            border-radius: 5px;
            font-weight: bold;
        }

        footer {
            background-color: #111;
            color: white;
            display: flex;
            justify-content: space-around;
            padding: 20px;
        }

        footer p {
            color: #ccc;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
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
<nav>
    <ul>
    <li><a href="manage_articles.php"style="font-weight: bold; color: #333;">Manage Articles</a></li>
        <li><a href="manage_categories.php">Manage Categories</a></li>
        <li><a href="manage_comments.php" >Manage Comments</a></li>
    </ul>
</nav>

<main>
    <div class="card">
        <h2>Manage Articles</h2>
        <a href="add_article.php" class="add-btn">Add New Article</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= htmlspecialchars($row['category'] ?? 'Uncategorized') ?></td>
                        <td><?= date('F d, Y', strtotime($row['created_at'])) ?></td>
                        <td>
                            <a href="edit_article.php?id=<?= $row['id'] ?>" class="action-btn edit-btn">Edit</a>
                            <a href="delete_article.php?id=<?= $row['id'] ?>" class="action-btn delete-btn" 
                               onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</main>
</body>
</html>
