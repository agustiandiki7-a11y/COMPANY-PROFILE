<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login") {
    header("Location: login.php?pesan=belum_login");
    exit;
}

include "connection.php";

// Ambil data statistik penting untuk Dashboard
$q_book = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM bookings"));
$q_pending = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM bookings WHERE status = 'Pending' OR status = 'pending'"));
$q_serv = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM services"));

// Hitung estimasi total pendapatan dari booking yang disetujui / lunas
$q_rev = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT SUM(s.price) as total_pendapatan 
    FROM bookings b 
    LEFT JOIN services s ON b.service_id = s.id_service 
    WHERE b.status = 'Disetujui' OR b.status = 'approved' OR b.status = 'completed'
"));
$total_pendapatan = $q_rev['total_pendapatan'] ?? 0;

// Ambil 5 data booking terbaru
$recent_bookings = mysqli_query($koneksi, "
    SELECT b.*, s.name as service_name, s.price 
    FROM bookings b 
    LEFT JOIN services s ON b.service_id = s.id_service 
    ORDER BY b.id DESC LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Dashboard Admin | GHD Barbershop</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/ghd-backend.css" rel="stylesheet">
    <style>
        :root {
            --lux-black: #050505;
            --lux-surface: #121212;
            --lux-gold: #c5a059;
            --lux-white: #f8f8f8;
            --lux-text: #a3a3a3;
        }
        body { background: var(--lux-black) !important; color: var(--lux-text) !important; font-family: 'Montserrat', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Playfair Display', serif; color: var(--lux-white) !important; }
        
        /* Kartu Statistik GHD */
        .stat-card {
            background: var(--lux-surface);
            border: 1px solid rgba(197, 160, 89, 0.2);
            border-left: 4px solid var(--lux-gold);
            border-radius: 6px;
            padding: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.8);
            height: 100%;
        }
        .admin-card {
            background: var(--lux-surface);
            border: 1px solid rgba(197, 160, 89, 0.2);
            border-radius: 6px;
            padding: 25px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.8);
            margin-bottom: 25px;
        }
        .table { color: var(--lux-text) !important; }
        .table th {
            background: #080808;
            color: var(--lux-gold) !important;
            font-family: 'Playfair Display', serif;
            letter-spacing: 1px;
            border-color: rgba(197, 160, 89, 0.2);
        }
        .table td { border-color: rgba(197, 160, 89, 0.1); vertical-align: middle; }
        .table-hover tbody tr:hover { background-color: rgba(197, 160, 89, 0.05); color: var(--lux-white); }
        
        .btn-lux {
            background: var(--lux-gold);
            color: var(--lux-black);
            font-weight: 600;
            text-transform: uppercase;
            padding: 10px 20px;
            border-radius: 4px;
            border: none;
            letter-spacing: 1px;
            font-size: 11px;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-lux:hover { background: #e8d3a2; color: var(--lux-black); text-decoration: none; box-shadow: 0 0 15px rgba(197,160,89,0.4); }
        .badge-status { padding: 5px 10px; font-size: 10px; font-weight: 600; border-radius: 4px; text-transform: uppercase; }
    </style>
</head>
<body id="page-top">
    <div id="wrapper">
        
        <!-- SIDEBAR UTAMA -->
        <?php include "sidebar.php"; ?>

        <div id="content-wrapper" class="d-flex flex-column" style="background: var(--lux-black);">
            <div id="content">
                
                <!-- TOPBAR UTAMA -->
                <?php include "topbar.php"; ?>

                <!-- KONTEN UTAMA DASHBOARD -->
                <div class="container-fluid py-4">
                    
                    <!-- BANNER SELAMAT DATANG -->
                    <div class="admin-card d-flex justify-content-between align-items-center flex-wrap" style="background: linear-gradient(135deg, #080808 0%, #1a1a1a 100%); gap: 15px;">
                        <div>
                            <span style="color: var(--lux-gold); font-size: 11px; letter-spacing: 3px; text-transform: uppercase;">Executive Dashboard</span>
                            <h2 class="h3 mb-1" style="color: var(--lux-white);">Selamat Datang, <?= htmlspecialchars($_SESSION['username']); ?>!</h2>
                            <p class="text-muted m-0" style="font-size: 13px;">Kelola jadwal cukur, validasi pembayaran QRIS, dan pantau operasional GHD Barbershop.</p>
                        </div>
                        <div>
                            <a href="tabel_bookings.php" class="btn-lux"><i class="fas fa-calendar-check mr-1"></i> Kelola Booking</a>
                        </div>
                    </div>

                    <!-- 4 KARTU STATISTIK -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="stat-card">
                                <span style="color: #888; font-size: 11px; text-transform: uppercase; font-weight: 600;"><i class="fas fa-calendar-alt text-warning mr-1"></i> Total Pesanan</span>
                                <h2 style="font-size: 26px; font-weight: 700; color: var(--lux-white); margin: 8px 0 0 0;"><?= $q_book['total']; ?></h2>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="stat-card" style="border-left-color: #ffc107;">
                                <span style="color: #888; font-size: 11px; text-transform: uppercase; font-weight: 600;"><i class="fas fa-hourglass-half text-warning mr-1"></i> Pending Validasi</span>
                                <h2 style="font-size: 26px; font-weight: 700; color: #ffc107; margin: 8px 0 0 0;"><?= $q_pending['total']; ?></h2>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="stat-card" style="border-left-color: #28a745;">
                                <span style="color: #888; font-size: 11px; text-transform: uppercase; font-weight: 600;"><i class="fas fa-wallet text-success mr-1"></i> Total Pendapatan</span>
                                <h2 style="font-size: 20px; font-weight: 700; color: #28a745; margin: 10px 0 0 0;">Rp <?= number_format($total_pendapatan, 0, ',', '.'); ?></h2>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="stat-card" style="border-left-color: #17a2b8;">
                                <span style="color: #888; font-size: 11px; text-transform: uppercase; font-weight: 600;"><i class="fas fa-cut text-info mr-1"></i> Total Layanan</span>
                                <h2 style="font-size: 26px; font-weight: 700; color: var(--lux-white); margin: 8px 0 0 0;"><?= $q_serv['total']; ?></h2>
                            </div>
                        </div>
                    </div>

                    <!-- TABEL AKTIVITAS BOOKING TERBARU -->
                    <div class="admin-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 style="font-weight: 700; color: var(--lux-white); margin: 0;"><i class="fas fa-history text-warning mr-2"></i> 5 Pesanan Booking Terbaru</h5>
                            <a href="tabel_bookings.php" style="font-size: 12px; color: var(--lux-gold); text-decoration: none;">Lihat Semua &rarr;</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                               <thead>
                                    <tr>
                                        <th>Kode Booking</th>
                                        <th>Pelanggan</th>
                                        <th>Layanan</th>
                                        <th>Jadwal</th>
                                        <th>Kursi</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(mysqli_num_rows($recent_bookings) > 0): ?>
                                        <?php while($rb = mysqli_fetch_assoc($recent_bookings)): 
                                            $st = strtolower(trim($rb['status'] ?? 'pending'));
                                        ?>
                                        <tr>
                                            <td><strong style="color: var(--lux-gold); font-family: monospace;"><?= htmlspecialchars($rb['booking_code']); ?></strong></td>
                                            <td><b class="text-white"><?= htmlspecialchars($rb['customer_name']); ?></b></td>
                                            <td><?= htmlspecialchars($rb['service_name'] ?? 'Cukur Standar'); ?></td>
                                            <td><?= date('d M Y', strtotime($rb['booking_date'])); ?> (<?= $rb['booking_time']; ?>)</td>
                                            <td><span style="color: #28a745; font-weight: bold;"><?= $rb['chair_number']; ?></span></td>
                                            <td>
                                                <?php if($st == 'disetujui' || $st == 'approved'): ?>
                                                    <span class="badge bg-success badge-status" style="background-color: #28a745; color: #fff;">Disetujui</span>
                                                <?php elseif($st == 'ditolak' || $st == 'rejected'): ?>
                                                    <span class="badge bg-danger badge-status" style="background-color: #dc3545; color: #fff;">Ditolak</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning badge-status" style="background-color: #ffc107; color: #000;">Pending</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada aktivitas booking masuk.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>
</html>