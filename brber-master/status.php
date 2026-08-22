<?php
include "../backend/connection.php";
$booking_id = intval($_GET['id'] ?? 0);

$query = mysqli_query($koneksi, "SELECT b.*, s.name as service_name, s.price, bar.name as barber_name FROM bookings b 
          JOIN services s ON b.service_id = s.id_service 
          JOIN barbers bar ON b.barber_id = bar.id_barber 
          WHERE b.id_booking = $booking_id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Data booking tidak ditemukan.";
    exit;
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Status Booking - GHD Barbershop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <style>
        body { background: #0d0d0d; color: #fff; font-family: 'Segoe UI', sans-serif; }
        .card-box { background: #171717; padding: 40px; border-radius: 16px; border: 1px solid #2b2b2b; text-align: center; box-shadow: 0 15px 35px rgba(0,0,0,0.6); }
        .badge-status { padding: 10px 20px; border-radius: 30px; font-weight: bold; font-size: 14px; display: inline-block; margin: 15px 0; }
        .status-waiting { background: rgba(255, 193, 7, 0.15); color: #ffc107; border: 1px solid #ffc107; }
        .status-confirmed { background: rgba(40, 167, 69, 0.15); color: #28a745; border: 1px solid #28a745; }
        .status-rejected { background: rgba(220, 53, 69, 0.15); color: #dc3545; border: 1px solid #dc3545; }
    </style>
</head>
<body>
    <div class="container" style="padding: 60px 0;">
        <div class="row justify-content-center">
            <div class="col-md-6 card-box">
                <h3 style="font-weight: 900; color: #fff;">STATUS PESANAN</h3>
                <p style="color: #a7a7a7;">ID Booking: #<?= $data['id_booking']; ?></p>

                <hr style="border-color: #2b2b2b;">

                <div style="text-align: left; margin: 20px 0; font-size: 14px; color: #ddd;">
                    <p><strong>Nama:</strong> <?= htmlspecialchars($data['customer_name']); ?></p>
                    <p><strong>Layanan:</strong> <?= htmlspecialchars($data['service_name']); ?> (Rp <?= number_format($data['price'], 0, ',', '.'); ?>)</p>
                    <p><strong>Barber:</strong> <?= htmlspecialchars($data['barber_name']); ?></p>
                    <p><strong>Jadwal:</strong> <?= $data['booking_date']; ?> jam <?= $data['booking_time']; ?></p>
                </div>

                <?php if ($data['status_booking'] == 'Waiting Verification') : ?>
                    <div class="badge-status status-waiting">Menunggu Verifikasi Admin</div>
                    <p style="font-size: 13px; color: #888;">Admin sedang memeriksa mutasi pembayaran Anda. Halaman ini akan diperbarui otomatis saat dikonfirmasi.</p>
                
                <?php elseif ($data['status_booking'] == 'Confirmed') : ?>
                    <div class="badge-status status-confirmed">Booking Confirmed (Berhasil)</div>
                    <p style="font-size: 13px; color: #888;">Pembayaran Anda telah diterima. Tunjukkan halaman ini atau sebutkan nama saat datang ke barbershop.</p>
                
                <?php elseif ($data['status_booking'] == 'Rejected') : ?>
                    <div class="badge-status status-rejected">Pembayaran Ditolak</div>
                    <p style="font-size: 13px; color: #ff6b6b;">Maaf, pembayaran Anda ditolak oleh admin (bukti tidak valid/dana tidak masuk).</p>
                    <a href="payment.php?id=<?= $data['id_booking']; ?>" class="btn btn-warning" style="font-weight: bold; margin-top: 10px;">Bayar Ulang / Upload Bukti Baru</a>
                <?php endif; ?>

                <div style="margin-top: 30px;">
                    <a href="index.php" style="color: #aaa; font-size: 13px; text-decoration: none;">&larr; Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>