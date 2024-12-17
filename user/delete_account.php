<?php
session_start();
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];

    $delete_query = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        session_destroy(); // Hapus semua sesi
        header("Location: ../public/login.php");
        exit;
    } else {
        $_SESSION['error'] = "Gagal menghapus akun.";
        header("Location: dashboard.php");
        exit;
    }
}
?>
