<?php
session_start();
include '../includes/auth.php';
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? null;
    $password = !empty($_POST['password']) ? $_POST['password'] : null;

    // Proses Upload Gambar
    $image_path = null;
    if (!empty($_FILES['profile_image']['name'])) {
        $image_name = time() . '_' . basename($_FILES['profile_image']['name']);
        $upload_dir = '../uploads/';
        $target_file = $upload_dir . $image_name;

        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
            $image_path = $image_name;
        } else {
            $_SESSION['error'] = "Gagal mengupload gambar.";
            header("Location: dashboard.php");
            exit;
        }
    }

    // Validasi Username
    if (!$username) {
        $_SESSION['error'] = "Username tidak boleh kosong.";
        header("Location: dashboard.php");
        exit;
    }

    // Query Update
    $query = "UPDATE users SET username = ?";
    $params = [$username];
    $types = "s";

    if ($password) {
        $query .= ", password = ?";
        $params[] = password_hash($password, PASSWORD_DEFAULT);
        $types .= "s";
    }

    if ($image_path) {
        $query .= ", profile_image = ?";
        $params[] = $image_path;
        $types .= "s";
    }

    $query .= " WHERE id = ?";
    $params[] = $user_id;
    $types .= "i";

    // Debugging Query
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Error Prepare: " . $conn->error);
    }
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        $_SESSION['username'] = $username;
        if ($image_path) {
            $_SESSION['profile_image'] = $image_path;
        }
        $_SESSION['success'] = "Profil berhasil diperbarui!";
    } else {
        $_SESSION['error'] = "Error: " . $stmt->error;
    }

    header("Location: dashboard.php");
    exit;
}
?>
