<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login") {
    header("Location: login.php?pesan=belum_login");
    exit;
}

include "connection.php";
// Mengambil data pembayaran atau booking yang masuk melalui sistem QRIS
$query = mysqli_query($koneksi, "
    SELECT b.*, s.name as service_name, s.price 
    FROM bookings b 
    LEFT JOIN services s ON b.service_id = s.id_service 
    ORDER BY b.id DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Data Payments | GHD Barbershop</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/ghd-backend.css" rel="stylesheet">
    <style>
        :root { --lux-black: #050505; --lux-surface: #121212; --lux-gold: #c5a059; --lux-white: #f8f8f8; --lux-text: #a3a3a3; }
        body { background: var(--lux-black) !important; color: var(--lux-text) !important; font-family: 'Montserrat', sans-serif; }
        h1, h2, h3, h4, .card-header h6 { font-family: 'Playfair Display', serif; color: var(--lux-white) !important; }
        .card { background: var(--lux-surface) !important; border: 1px solid rgba(197, 160, 89, 0.2) !important; box-shadow: 0 15px 35px rgba(0,0,0,0.8); }
        .card-header { background: #080808 !important; border-bottom: 1px solid rgba(197, 160, 89, 0.2) !important; }
        .table { color: var(--lux-text) !important; background-color: var(--lux-surface); }
        .table th, .table td { border-color: rgba(197, 160, 89, 0.15) !important; vertical-align: middle; }
        .table th { color: var(--lux-gold) !important; font-family: 'Playfair Display', serif; letter-spacing: 1px; background: #080808; }
        .table-striped tbody tr:nth-of-type(odd) { background-color: rgba(255, 255, 255, 0.02); }
        .table-hover tbody tr:hover { background-color: rgba(197, 160, 89, 0.05); color: var(--lux-white); }
        
        .badge-approved { background: rgba(40, 167, 69, 0.15); color: #28a745; border: 1px solid #28a745; padding: 4px 12px; border-radius: 30px; font-weight: 600; font-size: 10px; text-transform: uppercase; }
        .badge-pending { background: rgba(255, 193, 7, 0.15); color: #ffc107; border: 1px solid #ffc107; padding: 4px 12px; border-radius: 30px; font-weight: 600; font-size: 10px; text-transform: uppercase; }
        .badge-cancelled { background: rgba(220, 53, 69, 0.15); color: #dc3545; border: 1px solid #dc3545; padding: 4px 12px; border-radius: 30px; font-weight: 600; font-size: 10px; text-transform: uppercase; }
    </style>
</head>
<body id="page-top">
    <div id="wrapper">
        <?php include "sidebar.php"; ?>
        <div id="content-wrapper" class="d-flex flex-column" style="background: var(--lux-black);">
            <div id="content">
                <?php include "topbar.php"; ?>

                <div class="container-fluid py-4">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <div>
                            <span style="color: var(--lux-gold); font-size: 11px; letter-spacing: 3px; text-transform: uppercase;">Transaction Logs</span>
                            <h1 class="h3 mb-0" style="color: var(--lux-white);">Data Payments (QRIS / Tagihan)</h1>
                        </div>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold" style="color: var(--lux-gold);">Riwayat Pembayaran Kustomer</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Booking</th>
                                            <th>Nama Kustomer</th>
                                            <th>Layanan</th>
                                            <th>Total Harga</th>
                                            <th>Metode</th>
                                            <th>Status Pembayaran</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; while ($row = mysqli_fetch_assoc($query)) : 
                                            $status = strtolower(trim($row['status'] ?? 'pending'));
                                        ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><b class="text-white" style="font-family: monospace; color: var(--lux-gold) !important;"><?= htmlspecialchars($row['booking_code']); ?></b></td>
                                            <td><?= htmlspecialchars($row['customer_name']); ?></td>
                                            <td><?= htmlspecialchars($row['service_name'] ?? 'Cukur Standar'); ?></td>
                                            <td><b class="text-white">Rp <?= number_format($row['price'] ?? 50000, 0, ',', '.'); ?></b></td>
                                            <td><span class="badge badge-secondary p-2">QRIS GHD</span></td>
                                            <td>
                                                <?php if ($status == 'approved' || $status == 'completed'): ?>
                                                    <span class="badge-approved"><i class="fas fa-check-circle mr-1"></i> Lunas (Paid)</span>
                                                <?php elseif ($status == 'cancelled'): ?>
                                                    <span class="badge-cancelled"><i class="fas fa-ban mr-1"></i> Dibatalkan</span>
                                                <?php else: ?>
                                                    <span class="badge-pending"><i class="fas fa-hourglass-half mr-1"></i> Menunggu Konfirmasi</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
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