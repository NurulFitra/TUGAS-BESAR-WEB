<?php
session_start();
include '../includes/auth.php';
include '../includes/db.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}

// Ambil ID dari parameter URL
$id = $_GET['id'] ?? null;

// Cek apakah ID valid
if (!$id) {
    header("Location: manage_categories.php?error=Invalid category ID.");
    exit;
}

// Query untuk mendapatkan data kategori
$stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$category = $result->fetch_assoc();

if (!$category) {
    header("Location: manage_categories.php?error=Category not found.");
    exit;
}

// Proses update kategori
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];

    $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
    $stmt->bind_param("si", $name, $id);
    if ($stmt->execute()) {
        header("Location: manage_categories.php?success=Category updated successfully.");
        exit;
    } else {
        $error = "Failed to update category.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
    <link rel="stylesheet" href="../public/style.css">
    <style>
        /* Gaya Mirip Halaman Manage Categories */
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        header img {
            height: 40px;
        }

        header h1 {
            font-size: 1.5em;
            color: #333;
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
            max-width: 600px;
        }

        h2 {
            margin-top: 0;
            font-size: 1.5em;
            border-bottom: 2px solid #007bff;
            display: inline-block;
            color: #333;
        }

        form label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
            color: #333;
        }

        form input[type="text"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            display: inline-block;
            padding: 8px 12px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #218838;
        }

        a.button-back {
            display: inline-block;
            margin-top: 10px;
            color: #0056b3;
            text-decoration: none;
            font-weight: bold;
        }

        footer {
            background-color: #111;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 20px;
        }
        .highlight {
            color: #007bff; /* Ganti warna sesuai kebutuhan */
            
        }
    </style>
</head>
<body>
    <!-- Header -->
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

    <!-- Navigation -->
    <nav>
        <ul>
            <li><a href="manage_articles.php">Manage Articles</a></li>
            <li><a href="manage_categories.php">Manage Categories</a></li>
            <li><a href="manage_comments.php">Manage Comments</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main>
        <div class="card">
            <h2>Edit Category</h2>
            <?php if (isset($error)) { ?>
                <p style="color: red;"><?= htmlspecialchars($error) ?></p>
            <?php } ?>
            <form method="POST">
                <label for="name">Category Name:</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($category['name']) ?>" required>
                <button type="submit">Update Category</button>
                <br>
                <a href="manage_categories.php" class="button-back">← Back to Manage Categories</a>
            </form>
        </div>
    </main>


</body>
</html>
