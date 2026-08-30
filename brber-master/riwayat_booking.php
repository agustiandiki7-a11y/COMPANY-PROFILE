<?php
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

include "../backend/connection.php";

/* =========================
   PROFILE / TOKO INFO
========================= */
$query_profile = mysqli_query($koneksi, "SELECT * FROM profile LIMIT 1");
$p = $query_profile ? mysqli_fetch_assoc($query_profile) : false;

if (!$p) {
    $p = [
        'name' => 'GHD BARBERSHOP',
        'logo' => ''
    ];
}

$logo_fav = !empty($p['logo']) ? '../backend/foto/' . $p['logo'] : 'assets/img/logo.ico';
$nama_toko = !empty($p['name']) ? $p['name'] : 'GHD BARBERSHOP';

/* =========================
   PENCARIAN RIWAYAT BOOKING
========================= */
$keyword = isset($_GET['cari']) ? mysqli_real_escape_string($koneksi, $_GET['cari']) : '';
$data_booking = null;

if (!empty($keyword)) {
    $query = "SELECT * FROM bookings WHERE customer_phone LIKE '%$keyword%' OR booking_code LIKE '%$keyword%' ORDER BY id DESC";
    $data_booking = mysqli_query($koneksi, $query);
}

function e($value)
{
    return htmlspecialchars(isset($value) ? $value : '', ENT_QUOTES, 'UTF-8');
}
?>

<!doctype html>
<html class="no-js" lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Riwayat Booking | <?php echo e($nama_toko); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= $logo_fav; ?>">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- LUXURY CUSTOM STYLING -->
    <style>
        :root {
            --lux-black: #050505;
            --lux-dark: #0a0a0a;
            --lux-surface: #121212;
            --lux-gold: #c5a059;
            --lux-gold-light: #e8d3a2;
            --lux-white: #f8f8f8;
            --lux-text: #a3a3a3;
            --font-head: 'Playfair Display', serif;
            --font-body: 'Montserrat', sans-serif;
            --transition-smooth: all 0.4s ease;
        }

        body {
            background: var(--lux-black) !important;
            color: var(--lux-text) !important;
            font-family: var(--font-body) !important;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-head) !important;
            color: var(--lux-white) !important;
        }

        .history-header {
            background: rgba(5, 5, 5, 0.95);
            border-bottom: 1px solid rgba(197, 160, 89, 0.2);
            padding: 20px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .history-logo a {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .history-logo img {
            max-height: 40px;
            object-fit: contain;
        }

        .history-logo h3 {
            color: var(--lux-white);
            font-size: 18px;
            margin: 0;
            font-weight: 700;
        }

        .history-container {
            padding: 80px 0;
            min-height: 80vh;
        }

        .card-search {
            background: var(--lux-surface);
            border: 1px solid rgba(197, 160, 89, 0.2);
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.8);
            margin-bottom: 50px;
        }

        .form-control-lux {
            background: var(--lux-black) !important;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--lux-white) !important;
            border-radius: 6px;
            padding: 14px 20px;
            font-size: 14px;
        }

        .form-control-lux:focus {
            border-color: var(--lux-gold);
            box-shadow: 0 0 10px rgba(197, 160, 89, 0.2);
        }

        .btn-lux {
            background: var(--lux-gold);
            color: var(--lux-black);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            padding: 14px 25px;
            border-radius: 6px;
            border: none;
            transition: var(--transition-smooth);
            font-size: 12px;
        }

        .btn-lux:hover {
            background: var(--lux-gold-light);
            box-shadow: 0 0 15px rgba(197, 160, 89, 0.4);
            color: var(--lux-black);
        }

        .table-lux {
            width: 100%;
            background: var(--lux-surface);
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid rgba(197, 160, 89, 0.15);
        }

        .table-lux th {
            background: #151515;
            color: var(--lux-gold);
            font-family: var(--font-head);
            font-size: 15px;
            letter-spacing: 1px;
            padding: 18px 20px;
            border-bottom: 1px solid rgba(197, 160, 89, 0.2);
        }

        .table-lux td {
            padding: 16px 20px;
            color: #d1d1d1;
            font-size: 14px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            vertical-align: middle;
        }

        .table-lux tr:hover {
            background: rgba(197, 160, 89, 0.02);
        }

        .badge-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .badge-pending { background: rgba(255, 193, 7, 0.15); color: #ffc107; border: 1px solid rgba(255, 193, 7, 0.3); }
        .badge-approved { background: rgba(40, 167, 69, 0.15); color: #28a745; border: 1px solid rgba(40, 167, 69, 0.3); }
        .badge-rejected { background: rgba(220, 53, 69, 0.15); color: #dc3545; border: 1px solid rgba(220, 53, 69, 0.3); }

        .btn-review {
            background: transparent;
            color: var(--lux-gold);
            border: 1px solid var(--lux-gold);
            padding: 6px 14px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            border-radius: 4px;
            text-decoration: none;
            transition: var(--transition-smooth);
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-review:hover {
            background: var(--lux-gold);
            color: var(--lux-black);
            text-decoration: none;
            box-shadow: 0 0 10px rgba(197, 160, 89, 0.4);
        }

        @media (max-width: 768px) {
            .history-header { padding: 15px 20px; }
            .card-search { padding: 25px 20px; }
        }
    </style>
</head>

<body>

    <header class="history-header">
        <div class="history-logo">
            <a href="index.php">
                <?php if (!empty($p['logo'])): ?>
                    <img src="../backend/foto/<?php echo e($p['logo']); ?>" alt="<?php echo e($nama_toko); ?>">
                <?php else: ?>
                    <i class="fas fa-cut fa-2x" style="color: var(--lux-gold);"></i>
                <?php endif; ?>
                <h3><?php echo e($nama_toko); ?></h3>
            </a>
        </div>
        <div>
            <a href="index.php" class="btn-review"><i class="fas fa-arrow-left"></i> Kembali ke Beranda</a>
        </div>
    </header>

    <div class="container history-container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                <div class="text-center mb-5">
                    <span style="color: var(--lux-gold); font-size: 11px; letter-spacing: 3px; text-transform: uppercase; display: block; margin-bottom: 10px;">Tracking System</span>
                    <h2>Cek Riwayat Booking Anda</h2>
                    <p style="font-size: 14px; color: var(--lux-text);">Masukkan Nomor WhatsApp atau Kode Booking Anda untuk melihat status antrean kursi.</p>
                </div>

                <div class="card-search">
                    <form method="GET" action="">
                        <div class="row align-items-center">
                            <div class="col-md-9 mb-3 mb-md-0">
                                <input type="text" name="cari" class="form-control form-control-lux" placeholder="Masukkan No. WhatsApp atau Kode Booking..." value="<?php echo e($keyword); ?>" required>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-lux w-100">Cari Riwayat</button>
                            </div>
                        </div>
                    </form>
                </div>

                <?php if (!empty($keyword)): ?>
                    <div class="mb-3">
                        <h4 style="font-size: 18px; color: var(--lux-white);">Hasil Pencarian untuk: "<span style="color: var(--lux-gold);"><?php echo e($keyword); ?></span>"</h4>
                    </div>

                    <?php if ($data_booking && mysqli_num_rows($data_booking) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-lux">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Tanggal & Waktu</th>
                                        <th>Pelanggan</th>
                                        <th>Kursi</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi Ulasan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($data_booking)): ?>
                                        <tr>
                                            <td><strong style="color: var(--lux-gold);"><?php echo e($row['booking_code']); ?></strong></td>
                                            <td>
                                                <?php echo e($row['booking_date']); ?><br>
                                                <small style="color: #777;"><?php echo e($row['booking_time']); ?></small>
                                            </td>
                                            <td>
                                                <?php echo e($row['customer_name']); ?><br>
                                                <small style="color: #777;"><?php echo e(isset($row['customer_phone']) ? $row['customer_phone'] : '-'); ?></small>
                                            </td>
                                            <td><span style="font-weight: 600; color: #fff;"><?php echo e($row['chair_number']); ?></span></td>
                                            <td>
                                                <?php 
                                                    $status = strtolower(trim($row['status']));
                                                    $badge_class = 'badge-pending';
                                                    if ($status == 'approved' || $status == 'completed' || $status == 'disetujui' || $status == 'selesai') $badge_class = 'badge-approved';
                                                    if ($status == 'rejected' || $status == 'ditolak') $badge_class = 'badge-rejected';
                                                ?>
                                                <span class="badge-status <?php echo $badge_class; ?>"><?php echo e($row['status']); ?></span>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($status == 'approved' || $status == 'completed' || $status == 'disetujui' || $status == 'selesai'): ?>
                                                    <a href="beri_testimoni.php" class="btn-review">
                                                        <i class="fas fa-star"></i> Beri Ulasan
                                                    </a>
                                                <?php else: ?>
                                                    <span style="font-size: 12px; color: #555;">Belum Tersedia</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5" style="background: var(--lux-surface); border-radius: 12px; border: 1px solid rgba(255,255,255,0.03);">
                            <i class="fas fa-search fa-3x mb-3" style="color: var(--lux-gold); opacity: 0.5;"></i>
                            <h4 style="font-size: 20px; color: #fff;">Tidak Ada Riwayat Ditemukan</h4>
                            <p style="font-size: 13px; color: #888;">Pastikan nomor WhatsApp atau kode booking yang Anda masukkan sudah benar.</p>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="assets/js/vendor/jquery-1.12.4.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
</body>
</html>