<?php
session_start();
include '../includes/db.php';

if (isset($_GET['register']) && $_GET['register'] === 'success') {
    $success = "Registrasi berhasil! Silahkan login.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $remember = isset($_POST['remember']); // Menangani opsi remember me

    $query = $conn->prepare("SELECT id, username, profile_image, role FROM users WHERE username = ? AND password = ?");
    $query->bind_param("ss", $username, $password);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Simpan data ke dalam session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['profile_image'] = $user['profile_image'] ?? 'default.png'; // Default jika kosong

        if ($remember) {
            // Set cookie untuk 'remember me', valid selama 30 hari
            setcookie("username", $username, time() + (30 * 24 * 60 * 60), "/");
            setcookie("password", $_POST['password'], time() + (30 * 24 * 60 * 60), "/");
        }

        // Redirect sesuai role pengguna
        if ($user['role'] === 'admin') {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../user/dashboard.php");
        }
        exit;
    } else {
        $error = "Username atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        h1 {
            color: #003366;
            font-size: 24px;
            margin-bottom: 20px;
        }
        label {
            display: block;
            text-align: left;
            margin: 5px 0;
            color: #343a40;
            font-size: 14px;
        }
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 14px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: rgb(60, 135, 210);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
            transition: background-color 0.3s;
            
        }
        button:first-of-type {
            margin-bottom: 10px; /* Spasi antara tombol pertama dan kedua */
        }
        button:hover {
            background-color: #003366;
        }
        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .login-link {
            margin-top: 15px;
            font-size: 14px;
        }
        .login-link a {
            color: #007bff;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .logo {
            width: 150px;
            margin-bottom: 20px;
        }
        input[type="checkbox"] {
            width: 16px; /* Ukuran standar checkbox */
            height: 16px;
            margin: 0; /* Hilangkan margin bawaan */
        }
        .form-group {
        display: flex;
        align-items: center; /* Sejajarkan vertikal */
        justify-content: flex-start; /* Rata kiri */
        margin-bottom: 15px;
    }
    </style>
</head>
<body>
    <div class="container">
        <img src="logo.png" alt="Logo" class="logo">
        <h1>Login</h1>
        <?php if (isset($success)): ?>
            <p class="success"><?= $success ?></p>
        <?php endif; ?>
        <form method="POST">
            <?php if (isset($error)): ?>
                <p class="error"><?= $error ?></p>
            <?php endif; ?>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember Me</label>
            </div>
            <button type="submit">Login</button>
        </form>
        <div class="login-link">
            <p>Belum punya akun? <a href="daftar.php">Silahkan Daftar</a></p>
        </div>
    </div>
</body>
</html>
