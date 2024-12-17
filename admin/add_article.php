<?php
session_start();
include '../includes/auth.php';
include '../includes/db.php';

// Cek apakah pengguna adalah admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}

// Proses form saat dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $link = $_POST['link']; // URL opsional
    $category_id = $_POST['category'];
    $error = '';

    // Proses gambar jika diunggah
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_name = basename($_FILES['image']['name']);
        $image_size = $_FILES['image']['size'];
        $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];

        // Validasi file gambar
        if (!in_array($image_ext, $allowed_exts)) {
            $error = "File harus berupa gambar dengan format jpg, jpeg, png, atau gif.";
        } elseif ($image_size > 2 * 1024 * 1024) { // Maksimal 2MB
            $error = "Ukuran file tidak boleh melebihi 2MB.";
        } else {
            // Tentukan direktori penyimpanan gambar
            $upload_dir = '../uploads/articles/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true); // Buat folder jika belum ada
            }

            // Buat nama file unik
            $new_image_name = uniqid('article_', true) . '.' . $image_ext;
            $upload_path = $upload_dir . $new_image_name;

            // Pindahkan file ke direktori
            if (move_uploaded_file($image_tmp, $upload_path)) {
                // Simpan ke database
                $stmt = $conn->prepare("INSERT INTO articles (title, content, link, category_id, image) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssis", $title, $content, $link, $category_id, $new_image_name);

                if ($stmt->execute()) {
                    header("Location: manage_articles.php?success=Article added successfully.");
                    exit;
                } else {
                    $error = "Gagal menambahkan artikel.";
                }
            } else {
                $error = "Gagal mengunggah file gambar.";
            }
        }
    } else {
        $error = "Harap unggah file gambar.";
    }
}

// Ambil kategori untuk dropdown
$categories = $conn->query("SELECT * FROM categories");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Article</title>
    <link rel="stylesheet" href="../public/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('../public/kopi.png'); 
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #333;
        }
        header, nav, footer {
            background-color: #ffffff;
            border-bottom: 2px solid #0056b3;
        }
        h1, h2 {
            margin: 0;
        }
        nav {
            display: flex;
            justify-content: center;
            padding: 15px;
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
        .form-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 600px;
            animation: fadeIn 0.5s;
        }
        .form-container label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        .form-container input, .form-container textarea, .form-container select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-container button {
            margin-top: 15px;
            background-color: #0056b3;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }
        .form-container button:hover {
            background-color: #004494;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
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
        <li><a href="manage_articles.php"style="font-weight: bold; color: #333;">Manage Articles</a></li>
        <li><a href="manage_categories.php">Manage Categories</a></li>
        <li><a href="manage_comments.php">Manage Comments</a></li>
    </ul>
</nav>

    <!-- Main Content -->
    <main>
        <div class="form-container">
            <h2>Add New Article</h2>
            <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
            <form method="POST" enctype="multipart/form-data">
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" required>

                <label for="content">Content:</label>
                <textarea name="content" id="content" rows="5" required></textarea>

                <label for="link">Link (optional):</label>
                <input type="url" name="link" id="link">

                <label for="category">Category:</label>
                <select name="category" id="category" required>
                    <?php while ($category = $categories->fetch_assoc()) { ?>
                        <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                    <?php } ?>
                </select>

                <label for="image">Upload Image:</label>
                <input type="file" name="image" id="image" accept="image/*" required>

                <button type="submit">Add Article</button>
            </form>
        </div>
    </main>


</body>
</html>
