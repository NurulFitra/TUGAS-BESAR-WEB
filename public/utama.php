<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contoh Desain</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #343a40;
        }

        .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 100px; /* Diperbesar */
            height: 100vh;
        }

        .text-section {
            max-width: 600px;
        }

        h1 {
            font-size: 4.5em; /* Dikecilkan sedikit */
            margin: 0;
            overflow: hidden; /* Menghilangkan bagian yang tidak terlihat */
        }

        .highlight {
            color: #007bff; /* Ganti warna sesuai kebutuhan */
            display: inline-block;
            animation: moving 4s linear infinite; /* Animasi bergerak */
            white-space: nowrap; /* Mencegah pembungkusan */
            padding-right: 10px; /* Memberi ruang untuk animasi */
        }

        @keyframes moving {
            0% { transform: translateX(0); }
            50% { transform: translateX(10px); } /* Bergerak ke kanan */
            100% { transform: translateX(0); }
        }

        p {
            font-size: 1.8em; /* Dikecilkan sedikit */
            margin: 20px 0;
        }

        .button {
            background-color: #007bff; /* Ganti warna sesuai kebutuhan */
            color: white;
            padding: 20px 30px; /* Dikecilkan sedikit */
            font-size: 1.8em; /* Dikecilkan sedikit */
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        

        .button:hover {
            background-color: #0056b3; /* Ganti warna sesuai kebutuhan */
        }

        .image-section {
            flex: 1;
            text-align: right;
        }

        .image-section img {
            max-width: 100%;
            height: auto;
            border-radius: 10px; /* Gaya sudut gambar */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="text-section">
            <img src="logo.png" alt="Character Image">
            <h1>Berpikir Kritis, <span class="highlight">Tindak Cerdik</span>|</h1>
            <p>Semakin banyak kita tahu, semakin besar dunia kita</p>
            <a href="login.php" class="button"> Log In </a>

        </div>
        <div class="image-section">
            <img src="inspirasi.jpg" alt="Character Image">
        </div>
    </div>
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Aplikasi</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #343a40;
        }

        header {
            background-color: #003366; /* Warna latar belakang gelap */
            color: white;
            padding: 50px;
            text-align: center;
        }

        .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 50px;
            background-color: white;
            border-bottom: 2px solid #e0e0e0;
        }

        .text-section {
            max-width: 600px;
        }

        h2 {
            font-size: 2.5em;
            margin: 0;
            color: #003366; /* Warna judul */
        }

        p {
            font-size: 1.2em;
            line-height: 1.6;
            margin: 20px 0;
        }

        .image-section {
            flex: 1;
            text-align: center;
        }

        .image-section img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #003366; /* Warna latar belakang gelap */
            color: white;
        }
    </style>
</head>
<body>

    <header>
        <h1>  APA ITU <span style="color: #ffcc00;">InspiRAsi?</span></h1>
    </header>

    <div class="container">
        <div class="text-section">
            <h2>InspiRAsi</h2>
            <p>inspirasi adalah portal yang menyediakan berbagai pilihan artikel dari berbagai kategori yang dapat memperluas wawasan anda terkait bagaimana dunia ini berjalan.</p>
            <p><strong>Nurul Fitra</strong><br>60900122069, Sistem Informasi</p>
        </div>
        <div class="image-section">
            <img src="kisah-inspirasi.jpg" alt="Gambar Aplikasi">
        </div>
    </div>

    <footer>
        <p>View your repository | Read blog | View gallery</p>
    </footer>

</body>
</html>
</body>
</html>