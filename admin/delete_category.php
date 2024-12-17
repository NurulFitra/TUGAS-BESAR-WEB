<?php
session_start();
include '../includes/auth.php';
include '../includes/db.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: manage_categories.php?success=Category deleted successfully.");
    } else {
        header("Location: manage_categories.php?error=Failed to delete category.");
    }
    exit;
}
header("Location: manage_categories.php?error=Invalid category ID.");
?>