<?php
session_start();
include '../includes/auth.php';
include '../includes/db.php';

// Pastikan hanya admin yang dapat mengakses halaman ini
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}

// Ambil semua komentar dari database
$stmt = $conn->prepare("SELECT c.id, c.comment, a.title, u.username FROM comments c
                         JOIN articles a ON c.article_id = a.id
                         JOIN users u ON c.user_id = u.id");
$stmt->execute();
$result = $stmt->get_result();
$comments = $result->fetch_all(MYSQLI_ASSOC);

// Hapus komentar jika diminta
if (isset($_POST['delete_comment'])) {
    $comment_id = $_POST['comment_id'];

    // Pastikan comment_id tidak kosong dan valid
    if (!empty($comment_id)) {
        $delete_stmt = $conn->prepare("DELETE FROM comments WHERE id = ?");
        $delete_stmt->bind_param("i", $comment_id);
        $delete_stmt->execute();

        // Cek apakah penghapusan berhasil
        if ($delete_stmt->affected_rows > 0) {
            header("Location: manage_comments.php?success=Comment deleted.");
        } else {
            header("Location: manage_comments.php?error=Failed to delete comment.");
        }
        exit;
    } else {
        header("Location: manage_comments.php?error=Invalid comment ID.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Comments</title>
    <link rel="stylesheet" href="../public/style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('../public/kopi.png'); /* Background konsisten */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #333;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background-color: #ffffff;
            border-bottom: 2px solid #0056b3;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            margin: 0;
            font-size: 1.5em;
            color: #333333;
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
            color: #0056b3;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.2s;
        }

        nav ul li a:hover {
            background-color: #e1e4e8;
            transform: scale(1.05);
        }

        main {
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .table-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0px 2px 8px rgba(0,0,0,0.1);
            width: 90%;
            max-width: 1000px;
            margin-top: 20px;
            animation: slideIn 0.5s forwards;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color:#0056b3;
            color: white;
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        button {
            background-color:rgb(157, 182, 188);
            color: white;
            border: none;
            padding: 6px 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        button:hover {
            background-color: #c82333;
        }

        footer {
            background-color: #111;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
            flex-wrap: wrap;
        }

        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
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

<nav>
    <ul>
        <li><a href="manage_articles.php">Manage Articles</a></li>
        <li><a href="manage_categories.php">Manage Categories</a></li>
        <li><a href="manage_comments.php" style="font-weight: bold; color: #333;">Manage Comments</a></li>
    </ul>
</nav>

<main>
    <div class="table-container">
        <h2 style="margin-left: 10px; color: #007bff;">Manage Comments</h2>

        <?php if (isset($_GET['success'])): ?>
            <p style="color: green; margin-left: 10px;"> <?php echo $_GET['success']; ?> </p>
        <?php elseif (isset($_GET['error'])): ?>
            <p style="color: red; margin-left: 10px;"> <?php echo $_GET['error']; ?> </p>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Article Title</th>
                    <th>Username</th>
                    <th>Comment</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($comments as $comment): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($comment['id']); ?></td>
                        <td><?php echo htmlspecialchars($comment['title']); ?></td>
                        <td><?php echo htmlspecialchars($comment['username']); ?></td>
                        <td><?php echo htmlspecialchars($comment['comment']); ?></td>
                        <td>
                            <form action="manage_comments.php" method="post" onsubmit="return confirm('Are you sure you want to delete this comment?');">
                                <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                                <button type="submit" name="delete_comment">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

</body>
</html>
