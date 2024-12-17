<?php
session_start();
include '../includes/auth.php'; // Pastikan user login
include '../includes/db.php';   // Koneksi database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi data
    $article_id = isset($_POST['article_id']) ? (int) $_POST['article_id'] : 0;
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
    $user_name = $_SESSION['user_id'] ?? 'Anonymous'; // Ganti sesuai sistem user Anda

    if ($article_id > 0 && !empty($comment)) {
        // Query untuk menyimpan komentar
        $stmt = $conn->prepare("INSERT INTO comments (article_id, user_id, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $article_id, $user_name, $comment);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            header("Location: view_article.php?id=$article_id");
            exit;
        } else {
            echo "Failed to save the comment.";
        }
    } else {
        echo "Invalid input.";
    }
} else {
    echo "Invalid request.";
}
?>
