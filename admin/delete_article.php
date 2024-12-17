<?php
session_start();
include '../includes/auth.php'; // Pastikan user login
include '../includes/db.php';

// Pastikan hanya admin yang bisa mengakses
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}

// Ambil ID artikel dari URL
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: manage_articles.php?error=Invalid article ID.");
    exit;
}

// Query untuk menghapus artikel
$stmt = $conn->prepare("DELETE FROM articles WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: manage_articles.php?success=Article deleted successfully.");
} else {
    header("Location: manage_articles.php?error=Failed to delete article.");
}

$stmt->close();
$conn->close();
