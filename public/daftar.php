<?php
session_start();
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $confirm_password = md5($_POST['confirm_password']);

    // Cek apakah password dan konfirmasi password sama
    if ($password !== $confirm_password) {
        $error = "Password dan konfirmasi password tidak cocok.";
    } else {
        // Cek apakah username sudah digunakan
        $check_query = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check_query->bind_param("s", $username);
        $check_query->execute();
        $result = $check_query->get_result();

        if ($result->num_rows > 0) {
            $error = "Username sudah digunakan. Silahkan pilih username lain.";
        } else {
            // Masukkan data ke database
            $role = 'user';
            $insert_query = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $insert_query->bind_param("sss", $username, $password, $role);

            if ($insert_query->execute()) {
                // Redirect ke login dengan pesan sukses
                header("Location: login.php?register=success");
                exit();
            } else {
                $error = "Terjadi kesalahan, silahkan coba lagi.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
    </style>
</head>
<body>
    <div class="container">
        <img src="logo.png" alt="Logo" class="logo">
        <h1>Register</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
        <form method="POST">
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <label for="confirm_password">Konfirmasi Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit">Daftar</button>
        </form>
        <div class="login-link">
            <p>Sudah punya akun? <a href="login.php">Silahkan login</a></p>
        </div>
    </div>
</body>
</html>
