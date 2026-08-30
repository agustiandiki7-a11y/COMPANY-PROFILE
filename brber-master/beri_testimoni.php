<?php
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}
include "../backend/connection.php";

/* =========================
   AMBIL DATA PROFIL / LOGO TOKO
========================= */
$query_profile = mysqli_query($koneksi, "SELECT * FROM profile LIMIT 1");
$p = $query_profile ? mysqli_fetch_assoc($query_profile) : false;

$logo_toko = !empty($p['logo']) ? '../backend/foto/' . $p['logo'] : '';
$nama_toko = !empty($p['name']) ? $p['name'] : 'GHD BARBERSHOP';
$logo_fav  = !empty($p['logo']) ? '../backend/foto/' . $p['logo'] : 'assets/img/logo.ico';

$pesan = "";
$success = false;

if (isset($_POST['submit_review'])) {
    $phone = mysqli_real_escape_string($koneksi, $_POST['phone']);
    $rating = (int)$_POST['rating'];
    $message = mysqli_real_escape_string($koneksi, $_POST['message']);

    $cek_booking = mysqli_query($koneksi, "SELECT * FROM bookings WHERE customer_phone = '$phone' AND status IN ('approved', 'completed', 'Disetujui', 'Selesai') LIMIT 1");

    if (mysqli_num_rows($cek_booking) > 0) {
        $data_book = mysqli_fetch_assoc($cek_booking);
        $nama_pelanggan = $data_book['customer_name'];

        $query_insert = mysqli_query($koneksi, "INSERT INTO testimonials (name, rating, message) VALUES ('$nama_pelanggan', '$rating', '$message')");

        if ($query_insert) {
            $success = true;
            $pesan = "Terima kasih! Ulasan Anda berhasil dikirim dan tampil di beranda.";
        } else {
            $pesan = "Terjadi kesalahan sistem saat menyimpan ulasan.";
        }
    } else {
        $pesan = "Akses ditolak! Nomor WhatsApp ini belum memiliki riwayat booking yang disetujui (Approved / Completed).";
    }
}
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Beri Ulasan | <?= htmlspecialchars($nama_toko); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Favicon / Logo Tab Browser -->
    <link rel="icon" type="image/x-icon" href="<?= htmlspecialchars($logo_fav); ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/fontawesome-all.min.css">

    <style>
        :root {
            --lux-black: #050505;
            --lux-surface: #121212;
            --lux-gold: #c5a059;
            --lux-gold-light: #e8d3a2;
            --lux-white: #f8f8f8;
            --lux-text: #a3a3a3;
        }

        body {
            background-color: var(--lux-black);
            color: var(--lux-text);
            font-family: 'Montserrat', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 30px 15px;
        }

        .review-wrapper {
            width: 100%;
            max-width: 520px;
        }

        .review-card {
            background: var(--lux-surface);
            border: 1px solid rgba(197, 160, 89, 0.3);
            border-radius: 12px;
            padding: 40px 35px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.9);
            position: relative;
        }

        .brand-logo img {
            max-height: 50px;
            object-fit: contain;
            margin-bottom: 12px;
        }

        h2 {
            font-family: 'Playfair Display', serif;
            color: var(--lux-white);
            font-size: 26px;
            font-weight: 700;
        }

        .form-label-lux {
            color: var(--lux-gold);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 6px;
            display: block;
        }

        .input-group-custom {
            display: flex;
            align-items: center;
            background: #181818;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 6px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .input-group-custom:focus-within {
            border-color: var(--lux-gold);
            box-shadow: 0 0 10px rgba(197, 160, 89, 0.2);
        }

        .input-group-custom .icon-box {
            padding: 0 15px;
            color: var(--lux-gold);
            background: transparent;
        }

        .form-control-lux {
            background: transparent !important;
            border: none !important;
            color: var(--lux-white) !important;
            padding: 12px 15px;
            font-size: 14px;
            width: 100%;
        }

        .form-control-lux:focus {
            box-shadow: none !important;
            outline: none !important;
        }

        select.form-control-lux option {
            background-color: #1a1a1a !important;
            color: #ffffff !important;
            padding: 10px;
        }

        textarea.form-control-lux {
            background: #181818 !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            border-radius: 6px !important;
            resize: none;
        }

        textarea.form-control-lux:focus {
            border-color: var(--lux-gold) !important;
            box-shadow: 0 0 10px rgba(197, 160, 89, 0.2) !important;
        }

        .btn-lux {
            background: var(--lux-gold);
            color: var(--lux-black);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            padding: 12px;
            width: 100%;
            border-radius: 6px;
            border: none;
            transition: all 0.3s ease;
            font-size: 12px;
            margin-top: 5px;
        }

        .btn-lux:hover {
            background: var(--lux-gold-light);
            box-shadow: 0 0 15px rgba(197, 160, 89, 0.4);
            color: var(--lux-black);
        }

        .back-link {
            color: var(--lux-text);
            font-size: 12px;
            text-decoration: none;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 25px;
        }

        .back-link:hover {
            color: var(--lux-gold);
            text-decoration: none;
        }

        .alert-custom-success {
            background: rgba(40, 167, 69, 0.15);
            border: 1px solid rgba(40, 167, 69, 0.3);
            color: #28a745;
            border-radius: 6px;
            padding: 12px;
            font-size: 13px;
        }

        .alert-custom-danger {
            background: rgba(220, 53, 69, 0.15);
            border: 1px solid rgba(220, 53, 69, 0.3);
            color: #dc3545;
            border-radius: 6px;
            padding: 12px;
            font-size: 13px;
        }
    </style>
</head>
<body>

    <div class="review-wrapper">
        <div class="review-card">
            
            <div class="text-center mb-4">
                <div class="brand-logo">
                    <?php if (!empty($p['logo'])): ?>
                        <img src="../backend/foto/<?= htmlspecialchars($p['logo']); ?>" alt="<?= htmlspecialchars($nama_toko); ?>">
                    <?php else: ?>
                        <i class="fas fa-cut fa-2x mb-2" style="color: var(--lux-gold);"></i>
                    <?php endif; ?>
                </div>
                <h2>Beri Ulasan Pelanggan</h2>
                <p style="font-size: 13px; color: var(--lux-text); margin-top: 5px;">Masukkan nomor WhatsApp Anda yang terdaftar pada booking untuk verifikasi.</p>
            </div>

            <?php if (!empty($pesan)): ?>
                <div class="<?= $success ? 'alert-custom-success' : 'alert-custom-danger'; ?> mb-4 text-center">
                    <i class="fas <?= $success ? 'fa-check-circle' : 'fa-exclamation-triangle'; ?> mr-2"></i> <?= $pesan; ?>
                </div>
            <?php endif; ?>

            <?php if (!$success): ?>
            <form method="POST" action="">
                <div class="form-group mb-3">
                    <label class="form-label-lux">Nomor WhatsApp</label>
                    <div class="input-group-custom">
                        <span class="icon-box"><i class="fab fa-whatsapp"></i></span>
                        <input type="text" name="phone" class="form-control-lux" placeholder="Contoh: 081234567890" required>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label-lux">Rating Bintang</label>
                    <div class="input-group-custom">
                        <span class="icon-box"><i class="fas fa-star"></i></span>
                        <select name="rating" class="form-control-lux" required>
                            <option value="5">★★★★★ - Luar Biasa (5 Bintang)</option>
                            <option value="4">★★★★☆ - Sangat Baik (4 Bintang)</option>
                            <option value="3">★★★☆☆ - Cukup (3 Bintang)</option>
                            <option value="2">★★☆☆☆ - Kurang (2 Bintang)</option>
                            <option value="1">★☆☆☆☆ - Buruk (1 Bintang)</option>
                        </select>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label-lux">Pesan Ulasan Anda</label>
                    <textarea name="message" class="form-control-lux p-3" rows="4" placeholder="Tuliskan pengalaman cukur rambut Anda di sini..." required></textarea>
                </div>

                <button type="submit" name="submit_review" class="btn btn-lux">Kirim Ulasan</button>
            </form>
            <?php endif; ?>

            <div class="text-center">
                <a href="index.php" class="back-link"><i class="fas fa-arrow-left"></i> Kembali ke Beranda Utama</a>
            </div>

        </div>
    </div>

</body>
</html>