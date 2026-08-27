<?php
session_start();
include "../backend/connection.php";

// Jika belum login, lempar ke halaman utama
if (!isset($_SESSION['customer_id'])) {
    header("Location: index.php");
    exit;
}

$customer_id = $_SESSION['customer_id'];

// Ambil data kustomer dari database (sesuaikan nama tabel jika perlu, misal 'customers')
$query_cust = mysqli_query($koneksi, "SELECT * FROM customers WHERE id = $customer_id LIMIT 1");
$cust = $query_cust ? mysqli_fetch_assoc($query_cust) : [
    'name' => isset($_SESSION['customer_name']) ? $_SESSION['customer_name'] : 'Pelanggan',
    'email' => isset($_SESSION['customer_email']) ? $_SESSION['customer_email'] : '-',
    'phone' => '-'
];

// Ambil profil barbershop untuk logo
$query_profile = mysqli_query($koneksi, "SELECT * FROM profile LIMIT 1");
$p = $query_profile ? mysqli_fetch_assoc($query_profile) : ['name' => 'GHD BARBERSHOP', 'logo' => ''];
?>

<!doctype html>
<html class="no-js" lang="id">
<head>
    <meta charset="utf-8">
    <title>Profil Kustomer | <?= htmlspecialchars($p['name']); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="../backend/foto/logo.ico">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        :root {
            --lux-black: #050505;
            --lux-surface: #121212;
            --lux-gold: #c5a059;
            --lux-white: #f8f8f8;
            --lux-text: #a3a3a3;
        }
        body { background: var(--lux-black) !important; color: var(--lux-text) !important; font-family: 'Montserrat', sans-serif; }
        h1, h2, h3, h4 { font-family: 'Playfair Display', serif; color: var(--lux-white); }
        .profile-card { background: var(--lux-surface); border: 1px solid rgba(197, 160, 89, 0.2); padding: 40px; border-radius: 6px; box-shadow: 0 15px 35px rgba(0,0,0,0.8); }
        .btn-lux { background: var(--lux-gold); color: var(--lux-black); font-weight: 600; text-transform: uppercase; padding: 12px 25px; border-radius: 2px; border: none; transition: 0.3s; font-size: 12px; letter-spacing: 1px; }
        .btn-lux:hover { background: #e8d3a2; color: #000; box-shadow: 0 0 15px rgba(197,160,89,0.4); }
    </style>
</head>
<body>

    <!-- HEADER -->
    <header style="position: absolute; top:0; left:0; right:0; z-index:999; background: rgba(5,5,5,0.8); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(255,255,255,0.05);">
        <div style="min-height: 90px; padding: 15px 50px; display: flex; align-items: center; justify-content: space-between;">
            <a href="index.php" style="display: flex; align-items: center; gap: 10px; text-decoration: none;">
                <?php if (!empty($p['logo'])): ?>
                    <img src="../backend/foto/<?= htmlspecialchars($p['logo']); ?>" style="max-height: 45px;">
                <?php endif; ?>
                <h3 style="margin:0; font-size: 20px;"><?= htmlspecialchars($p['name']); ?></h3>
            </a>
            <a href="index.php" style="color: var(--lux-gold); font-size: 12px; text-transform: uppercase; text-decoration: none;"><i class="fas fa-arrow-left mr-1"></i> Beranda</a>
        </div>
    </header>

    <div style="padding: 160px 0 100px 0; min-height: 100vh;" class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="profile-card text-center">
                    <div style="width: 90px; height: 90px; background: rgba(197,160,89,0.1); border: 2px solid var(--lux-gold); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto; font-size: 35px; color: var(--lux-gold);">
                        <i class="fas fa-user"></i>
                    </div>
                    <h2 style="margin-bottom: 5px;"><?= htmlspecialchars($cust['name']); ?></h2>
                    <span style="color: var(--lux-gold); font-size: 12px; letter-spacing: 2px; text-transform: uppercase; display: block; margin-bottom: 25px;">Member Eksklusif GHD Barbershop</span>

                    <div class="text-left" style="background: #0a0a0a; padding: 20px; border-radius: 4px; border: 1px solid rgba(255,255,255,0.05); margin-bottom: 25px;">
                        <p style="margin-bottom: 10px; font-size: 13px;"><i class="fas fa-envelope mr-2" style="color: var(--lux-gold);"></i> Email: <b><?= htmlspecialchars($cust['email'] ?? '-'); ?></b></p>
                        <p style="margin-bottom: 0; font-size: 13px;"><i class="fas fa-phone mr-2" style="color: var(--lux-gold);"></i> Telepon: <b><?= htmlspecialchars($cust['phone'] ?? '-'); ?></b></p>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="riwayat_booking.php" class="btn btn-lux" style="flex: 1; margin-right: 10px; text-decoration: none; display: inline-block;">Riwayat Booking</a>
                        <a href="logout.php" class="btn" style="background: #dc3545; color: #fff; font-weight: 600; text-transform: uppercase; padding: 12px 20px; font-size: 12px; text-decoration: none;" onclick="return confirm('Yakin ingin keluar?')">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/vendor/jquery-1.12.4.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
</body>
</html>