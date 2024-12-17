<?php
session_start();
include '../includes/auth.php';
include '../includes/db.php';

// Cek apakah pengguna adalah admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}

// Proses form tambah kategori
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $error = '';

    if (empty($name)) {
        $error = "Category name cannot be empty.";
    } else {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);

        if ($stmt->execute()) {
            header("Location: manage_categories.php?success=Category added successfully.");
            exit;
        } else {
            $error = "Failed to add category.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <link rel="stylesheet" href="../public/style.css">
    <style>
        /* General styles */
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
            background-color: #0056b3; /* Warna biru tua */
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header h1 {
            margin: 0;
            font-size: 1.8rem;
        }
        header a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }
        header a:hover {
            color: #e6f0ff;
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
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }
        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin: 15px 0;
            width: 100%;
            max-width: 600px;
            animation: fadeIn 0.8s ease-out;
        }
        h2 {
            margin-top: 0;
            text-align: center;
        }
        form label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }
        form input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        form button {
            margin-top: 15px;
            background-color: #0056b3;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        form button:hover {
            background-color: #004494;
        }
        .error-message, .success-message {
            text-align: center;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .error-message {
            color: red;
        }
        .success-message {
            color: green;
        }
        footer {
            background-color: #111;
            color: white;
            padding: 20px;
            text-align: center;
        }
        footer a {
            color: #aaa;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        footer a:hover {
            color: #fff;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
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
        <a href="Dashboard.php" style="
            color: #0056b3; 
            text-decoration: none; 
            font-weight: bold; 
            margin-right: 20px;
        ">Dashboard</a>

    </div>
    </header>

    <!-- Navigation -->
    <nav>
        <ul>
            <li><a href="manage_articles.php">Manage Articles</a></li>
            <li><a href="manage_categories.php"style="font-weight: bold; color: #333;">Manage Categories</a></li>
            <li><a href="manage_comments.php">Manage Comments</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main>
        <div class="card">
            <h2>Add New Category</h2>
            <?php if (!empty($error)): ?>
                <div class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="POST">
                <label for="name">Category Name:</label>
                <input type="text" name="name" id="name" placeholder="Enter category name" required>
                <button type="submit">Add Category</button>
            </form>
        </div>
    </main>

</body>
</html>
