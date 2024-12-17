<?php
session_start();
include '../includes/auth.php';
include '../includes/db.php';

if ($_SESSION['role'] !== 'user') {
    header("Location: ../public/login.php");
    exit;
}
// Ambil data session
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$profile_image = $_SESSION['profile_image'];

// Query untuk kategori
$category_query = "SELECT * FROM categories";
$categories = mysqli_query($conn, $category_query);

// Filter kategori berdasarkan pilihan
$selected_category = isset($_GET['category']) ? $_GET['category'] : null;

if ($selected_category) {
    $article_query = "SELECT a.id, a.title, a.content, a.image, c.name AS category 
                      FROM articles a 
                      JOIN categories c ON a.category_id = c.id 
                      WHERE a.category_id = '$selected_category' 
                      ORDER BY a.created_at DESC";
} else {
    $article_query = "SELECT a.id, a.title, a.content, a.image, c.name AS category 
                      FROM articles a 
                      JOIN categories c ON a.category_id = c.id 
                      ORDER BY c.id, a.created_at DESC";
}

$articles = mysqli_query($conn, $article_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        /* Header Pertama */
        .header-main {
            background-color: #ffffff;
            color: #000;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .logo-title {
            display: flex;
            align-items: center;
        }

        .logo-title img {
            width: 100px;
            height: 70px;
            margin-right: 10px;
        }

        .header-main h1 {
            margin: 0;
            font-size: 1.5em;
        }

        .header-main a {
            text-decoration: none;
            font-weight: bold;
            color: #000;
            margin-left: 15px;
        }

        .filter {
            margin-right: 10px;
        }

        /* Header Kedua */
        .header-banner {
            position: relative;
            background: url('../public/artikel.jpg') no-repeat center center/cover;
            height: 300px;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: #fff;
        }

        .header-banner::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }

        .header-banner .banner-content {
            position: relative;
            z-index: 10;
        }

        .header-banner h1 {
            font-size: 2.5em;
            margin: 0;
        }

        .header-banner p {
            font-size: 1.2em;
            margin-top: 10px;
        }

        /* Kategori dan Artikel */
        .category-section {
            margin: 20px;
        }

        .category-section h2 {
            font-size: 1.5em;
            border-bottom: 2px solid #007bff;
            margin-bottom: 10px;
            padding-bottom: 5px;
        }
        .logout {
        display: flex;
        align-items: center; /* Agar sejajar vertikal */
        }
        .logout .filter {
            margin-right: 20px; /* Jarak antara filter dan tombol Logout */
        }

    .logout a {
        text-decoration: none;
        font-weight: bold;
        color: #007bff; /* Warna biru agar lebih menonjol */
        padding: 5px 10px;
        border: 1px solid #007bff;
        border-radius: 5px;
        transition: background-color 0.3s, color 0.3s;
    }
    .logout a:hover {
        background-color: #007bff;
        color: #fff;
    }

        .articles {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            text-align: center;
        }

        .card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .card h3 {
            margin: 10px 0;
            font-size: 1.2em;
        }

        .card p {
            padding: 0 10px 10px;
            color: #666;
            font-size: 0.9em;
        }

        .card a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }

        /* Footer */
        footer {
            background-color: #111;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
            flex-wrap: wrap;
        }

        footer h3 {
            margin-top: 0;
        }

        footer p, footer a {
            color: #ccc;
            font-size: 0.9em;
            text-decoration: none;
        }

        footer .social-icons img {
            width: 20px;
            margin-right: 10px;
        }

        footer input, footer button {
            padding: 5px;
            border: none;
            border-radius: 5px;
        }

        footer button {
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }
        .highlight {
            color: #007bff; /* Ganti warna sesuai kebutuhan */
            display: inline-block;
            animation: moving 4s linear infinite; /* Animasi bergerak */
            white-space: nowrap; /* Mencegah pembungkusan */
            padding-right: 10px; /* Memberi ruang untuk animasi */
        }
        
    </style>
</head>
<body>
    <?php if (isset($_SESSION['success'])): ?>
        <div style="color: green;"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php elseif (isset($_SESSION['error'])): ?>
        <div style="color: red;"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <!-- Header -->
    <header>
        <!-- Header Pertama -->
        <div class="header-main">
            <div class="logo-title">
                <img src="../public/logo.png" alt="Logo"> <!-- Ganti dengan path logo -->
                <h1>Berpikir Kritis, <span class="highlight">Tindak Cerdik</span>|</h1>
            </div>
            <div class="logout">
                <div class="filter">
                    <form method="GET" action="">
                        <label for="category">Pilih Kategori:</label>
                        <select name="category" id="category" onchange="this.form.submit()">
                            <option value="">Semua Kategori</option>
                            <?php while ($category = mysqli_fetch_assoc($categories)): ?>
                                <option value="<?= $category['id']; ?>" <?= ($selected_category == $category['id']) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </form>
                </div>
                 <div class="profile-menu" style="display: flex; align-items: center; margin-right: 20px; cursor: pointer;">
                    <img src="<?= '../uploads/' . ($_SESSION['profile_image'] ?? 'default.png'); ?>" 
                        alt="Profil" 
                        style="width:50px; height:50px; border-radius:50%; margin-right: 10px;">
                    <p style="margin: 0;"><?= htmlspecialchars($_SESSION['username']); ?></p>
                </div>
                <div class="profile-menu">
                    <a href="#" id="profile-button">Profil</a>
                    <a href="../public/logout.php">Logout</a>
                </div>
            </div>
        </div>
        <!-- Header Kedua -->
        <div class="header-banner">
            <div class="banner-content">
                <h1>Selamat datang di InspiRAsi</h1>
                <p>Jelajahi banyak pengetahuan dan berita yang menarik</p>
            </div>
        </div>
        <!-- Modal untuk Edit Profil -->
        <div id="profile-modal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%) scale(0.9); background:white; padding:20px; box-shadow:0 10px 25px rgba(0,0,0,0.3); border-radius:12px; z-index:1000; transition:all 0.3s ease-in-out;">
            <h2 style="margin-top:0; text-align:center; color:#333;">Edit Profil</h2>
            <form method="POST" action="update_profile.php" enctype="multipart/form-data" style="display:flex; flex-direction:column; gap:15px;">
                <!-- Input Gambar -->
                <div style="display:flex; flex-direction:column;">
                    <label for="profile_image" style="font-weight:bold; margin-bottom:5px; color:#555;">Gambar Profil</label>
                    <input type="file" id="profile_image" name="profile_image" accept="image/*" style="padding:8px; border:1px solid #ddd; border-radius:6px; outline:none; transition:border 0.3s;">
                </div>
                <!-- Input Username -->
                <div style="display:flex; flex-direction:column;">
                    <label for="username" style="font-weight:bold; margin-bottom:5px; color:#555;">Username</label>
                    <input type="text" id="username" name="username" value="<?= htmlspecialchars($_SESSION['username']); ?>" required
                        style="padding:10px; border:1px solid #ddd; border-radius:6px; outline:none; transition:border 0.3s;">
                </div>
                <!-- Input Password -->
                <div style="display:flex; flex-direction:column;">
                    <label for="password" style="font-weight:bold; margin-bottom:5px; color:#555;">Password Baru (Opsional)</label>
                    <input type="password" id="password" name="password"
                        style="padding:10px; border:1px solid #ddd; border-radius:6px; outline:none; transition:border 0.3s;">
                </div>
                <!-- Tombol Submit dan Tutup -->
                <div style="display:flex; justify-content:space-between; gap:10px;">
                    <button type="submit" style="padding:10px 15px; background-color:#007BFF; color:white; border:none; border-radius:6px; cursor:pointer; font-weight:bold; transition:background-color 0.3s;">Simpan</button>
                    <button type="button" id="close-modal" style="padding:10px 15px; background-color:#DC3545; color:white; border:none; border-radius:6px; cursor:pointer; font-weight:bold; transition:background-color 0.3s;">Tutup</button>
                </div>
                </form>
               
            </form>
        </div>

        <!-- Backdrop untuk Modal -->
        <div id="modal-backdrop" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999;"></div>

        <script>
            // Ambil elemen modal dan backdrop
            const modal = document.getElementById('profile-modal');
            const backdrop = document.getElementById('modal-backdrop');
            const openModal = document.getElementById('edit-profile-btn'); // Tombol untuk membuka modal
            const closeModal = document.getElementById('close-modal');

            // Buka Modal
            openModal.addEventListener('click', () => {
                modal.style.display = 'block';
                backdrop.style.display = 'block';
                setTimeout(() => {
                    modal.style.transform = 'translate(-50%, -50%) scale(1)';
                }, 50); // Efek animasi smooth
            });

            // Tutup Modal
            closeModal.addEventListener('click', () => {
                modal.style.transform = 'translate(-50%, -50%) scale(0.9)';
                setTimeout(() => {
                    modal.style.display = 'none';
                    backdrop.style.display = 'none';
                }, 200);
            });

            // Tutup Modal dengan klik backdrop
            backdrop.addEventListener('click', () => {
                closeModal.click();
            });
        </script>

    </header>
    <!-- Konten -->
    <main>
        <?php
        mysqli_data_seek($categories, 0);

        if ($selected_category) {
            $category_name_query = "SELECT name FROM categories WHERE id = '$selected_category'";
            $category_name_result = mysqli_query($conn, $category_name_query);
            $category_name = mysqli_fetch_assoc($category_name_result)['name'];
            echo "<div class='category-section'>";
            echo "<h2>Kategori: " . htmlspecialchars($category_name) . "</h2>";
            echo "<div class='articles'>";
            while ($article = mysqli_fetch_assoc($articles)) {
                include 'card.php';
            }
            echo "</div></div>";
        } else {
            while ($category = mysqli_fetch_assoc($categories)) {
                echo "<div class='category-section'>";
                echo "<h2>" . htmlspecialchars($category['name']) . "</h2>";
                echo "<div class='articles'>";
                mysqli_data_seek($articles, 0);
                while ($article = mysqli_fetch_assoc($articles)) {
                    if ($article['category'] == $category['name']) {
                        include 'card.php';
                    }
                }
                echo "</div></div>";
            }
        }
        ?>
    </main>

    <!-- Footer -->
    <footer>
        <div class="footer-section">
            <h3>inspiRAsi</h3>
            <p>Portal yang menyenangkan untuk menambah wawasan, ilmu, pengetahuan dan keterampilan. Jelajari lebih banyak untuk ilmu yang maksimal</p>
            <div class="social-icons">
                <img src="../public/facebook.png" alt="Facebook">
                <img src="../public/instagram.jpg" alt="Instagram">
                <img src="../public/tiktok.jpg" alt="TikTok">
            </div>
        </div>
        <div class="footer-section">
            <h3>Hubungi Kami</h3>
            <p>0834678921</p>
            <p>info@InspiRAsi.com</p>
        </div>
        <div class="footer-section">
            <h3>Berlangganan</h3>
            <form>
                <input type="email" placeholder="Email untuk berlangganan">
                <button type="submit">Daftar untuk berlangganan</button>
            </form>
        </div>
    </footer>
<script>
    document.getElementById('profile-button').addEventListener('click', function() {
        document.getElementById('profile-modal').style.display = 'block';
    });

    document.getElementById('close-modal').addEventListener('click', function() {
        document.getElementById('profile-modal').style.display = 'none';
    });
</script>
</body>
</html>
