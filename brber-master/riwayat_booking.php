<?php
session_start();
include "../backend/connection.php";

// Pastikan kustomer sudah login
if (!isset($_SESSION['customer_id'])) {
    header("Location: index.php");
    exit;
}

$customer_id = $_SESSION['customer_id'];
$customer_name = isset($_SESSION['customer_name']) ? $_SESSION['customer_name'] : '';

/* 
  CATATAN: 
  Jika tabel bookings menggunakan kolom 'customer_id', gunakan baris query pertama.
  Jika tabel bookings menggunakan kolom nama ('name' atau 'customer_name'), gunakan baris query kedua.
*/

// Opsi A: Jika tabel bookings pakai customer_id (pastikan kolom ini ada di database)
// $query_bookings = mysqli_query($koneksi, "SELECT * FROM bookings WHERE customer_id = $customer_id ORDER BY id DESC");

// Opsi B: Jika tabel bookings menyimpan nama kustomer (Sangat umum di template PHP native)
$query_bookings = mysqli_query($koneksi, "SELECT * FROM bookings WHERE name = '$customer_name' OR customer_id = $customer_id ORDER BY id DESC");

// Ambil data profil untuk header/footer
$query_profile = mysqli_query($koneksi, "SELECT * FROM profile LIMIT 1");
$p = $query_profile ? mysqli_fetch_assoc($query_profile) : ['name' => 'GHD BARBERSHOP', 'logo' => ''];
?>

<!doctype html>
<html class="no-js" lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Riwayat Booking | <?= htmlspecialchars($p['name']); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/x-icon" href="../backend/foto/logo.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        :root {
            --lux-black: #050505;
            --lux-dark: #0a0a0a;
            --lux-surface: #121212;
            --lux-gold: #c5a059;
            --lux-white: #f8f8f8;
            --lux-text: #a3a3a3;
        }
        body { background: var(--lux-black) !important; color: var(--lux-text) !important; font-family: 'Montserrat', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Playfair Display', serif; color: var(--lux-white); }
        .header-area { position: absolute; top: 0; left: 0; right: 0; width: 100%; z-index: 999; background: rgba(5, 5, 5, 0.8); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(255,255,255,0.05); }
        .main-header { min-height: 90px; padding: 15px 50px !important; }
        .content-wrapper { padding: 160px 0 100px 0; min-height: 100vh; }
        .table-dark-lux { background: var(--lux-surface); color: var(--lux-white); border: 1px solid rgba(197, 160, 89, 0.2); }
        .table-dark-lux th, .table-dark-lux td { border-color: rgba(255, 255, 255, 0.05); vertical-align: middle; padding: 15px; }
        .table-dark-lux th { color: var(--lux-gold); font-family: 'Playfair Display', serif; letter-spacing: 1px; }
        .badge-pending { background: rgba(255, 193, 7, 0.15); color: #ffc107; border: 1px solid #ffc107; padding: 6px 12px; border-radius: 4px; font-size: 11px; }
        .badge-approved { background: rgba(40, 167, 69, 0.15); color: #28a745; border: 1px solid #28a745; padding: 6px 12px; border-radius: 4px; font-size: 11px; }
        .badge-cancelled { background: rgba(220, 53, 69, 0.15); color: #dc3545; border: 1px solid #dc3545; padding: 6px 12px; border-radius: 4px; font-size: 11px; }
        .btn-batal { background: #dc3545; color: #fff; font-size: 11px; font-weight: 600; padding: 6px 12px; border-radius: 2px; text-transform: uppercase; border: none; transition: 0.3s; }
        .btn-batal:hover { background: #bd2130; }
    </style>
</head>
<body>

    <!-- HEADER -->
    <header>
        <div class="header-area">
            <div class="main-header">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <a href="index.php" style="display: flex; align-items: center; gap: 10px; text-decoration: none;">
                                <?php if (!empty($p['logo'])): ?>
                                    <img src="../backend/foto/<?= htmlspecialchars($p['logo']); ?>" alt="Logo" style="max-height: 50px;">
                                <?php endif; ?>
                                <h3 style="margin: 0; font-size: 20px;"><?= htmlspecialchars($p['name']); ?></h3>
                            </a>
                        </div>
                        <div class="col-6 text-right">
                            <a href="index.php" style="color: var(--lux-gold); font-size: 12px; font-weight: 600; text-transform: uppercase; text-decoration: none;">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Beranda
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="content-wrapper">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 style="font-size: 32px; margin-bottom: 5px;">Bukti & Riwayat Booking</h2>
                    <p class="text-muted" style="font-size: 13px;">Halo, <b><?= htmlspecialchars($customer_name); ?></b>. Tunjukkan bukti booking ini ke kasir saat Anda tiba di toko.</p>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-dark-lux table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Booking</th>
                                    <th>Jam</th>
                                    <th>No Kursi</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($query_bookings && mysqli_num_rows($query_bookings) > 0): ?>
                                    <?php $no = 1; while ($row = mysqli_fetch_assoc($query_bookings)): ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= htmlspecialchars($row['booking_date']); ?></td>
                                            <td><?= htmlspecialchars($row['booking_time']); ?></td>
                                            <td><b>Kursi <?= htmlspecialchars($row['chair_number']); ?></b></td>
                                            <td>
                                                <?php if ($row['status'] == 'Pending'): ?>
                                                    <span class="badge-pending">Pending</span>
                                                <?php elseif ($row['status'] == 'Disetujui'): ?>
                                                    <span class="badge-approved">Disetujui</span>
                                                <?php else: ?>
                                                    <span class="badge-cancelled"><?= htmlspecialchars($row['status']); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($row['status'] == 'Pending'): ?>
                                                    <a href="cancel_booking.php?id=<?= $row['id']; ?>" class="btn-batal" onclick="return confirm('Yakin ingin membatalkan booking kursi ini?')">Batalkan</a>
                                                <?php else: ?>
                                                    <span class="text-muted" style="font-size: 11px;">Terkunci</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">Belum ada riwayat booking yang tercatat.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/vendor/jquery-1.12.4.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
</body>
</html>