<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login') {
    header("Location: login.php");
    exit;
}
include "../backend/connection.php";

// Aksi jika admin klik Terima / Tolak
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action == 'accept') {
        mysqli_query($koneksi, "UPDATE bookings SET status_booking='Confirmed' WHERE id_booking=$id");
        mysqli_query($koneksi, "UPDATE payments SET status_payment='Verified' WHERE booking_id=$id");
    } elseif ($action == 'reject') {
        mysqli_query($koneksi, "UPDATE bookings SET status_booking='Rejected' WHERE id_booking=$id");
        mysqli_query($koneksi, "UPDATE payments SET status_payment='Rejected' WHERE booking_id=$id");
    }
    header("Location: admin_verifikasi.php");
    exit;
}

$query = mysqli_query($koneksi, "SELECT b.*, s.name as service_name, s.price, p.proof_image FROM bookings b 
          JOIN services s ON b.service_id = s.id_service 
          LEFT JOIN payments p ON b.id_booking = p.booking_id 
          ORDER BY b.id_booking DESC");
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Admin Verifikasi Pembayaran - GHD Barbershop</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <style>
        body { background: #0d0d0d; color: #fff; font-family: 'Segoe UI', sans-serif; }
        .table-dark { background: #171717; color: #fff; border-color: #2b2b2b; }
    </style>
</head>
<body>
    <div class="container" style="padding: 40px 0;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Panel Admin - Verifikasi Pembayaran Booking</h2>
            <a href="booking.php" class="btn btn-secondary btn-sm">Kembali ke Booking</a>
        </div>

        <div class="table-responsive">
            <table class="table table-dark table-striped table-bordered align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pelanggan</th>
                        <th>Layanan</th>
                        <th>Jadwal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                    <tr>
                        <td>#<?= $row['id_booking']; ?></td>
                        <td><?= htmlspecialchars($row['customer_name']); ?><br><small style="color:#aaa;"><?= $row['customer_phone']; ?></small></td>
                        <td><?= htmlspecialchars($row['service_name']); ?><br><strong>Rp <?= number_format($row['price'], 0, ',', '.'); ?></strong></td>
                        <td><?= $row['booking_date']; ?> <br><?= $row['booking_time']; ?></td>
                        <td>
                            <?php if($row['status_booking'] == 'Waiting Verification'): ?>
                                <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                            <?php elseif($row['status_booking'] == 'Confirmed'): ?>
                                <span class="badge bg-success">Confirmed</span>
                            <?php elseif($row['status_booking'] == 'Rejected'): ?>
                                <span class="badge bg-danger">Ditolak</span>
                            <?php else: ?>
                                <span class="badge bg-secondary"><?= $row['status_booking']; ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($row['status_booking'] == 'Waiting Verification'): ?>
                                <a href="admin_verifikasi.php?action=accept&id=<?= $row['id_booking']; ?>" class="btn btn-success btn-sm mb-1">Terima</a>
                                <a href="admin_verifikasi.php?action=reject&id=<?= $row['id_booking']; ?>" class="btn btn-danger btn-sm mb-1">Tolak</a>
                            <?php else: ?>
                                <small style="color: #777;">Selesai</small>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>