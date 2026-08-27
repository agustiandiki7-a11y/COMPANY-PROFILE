<?php
session_start();
include "../backend/connection.php";

if (!isset($_GET['id'])) {
    header("Location: booking.php");
    exit;
}

$id_booking = intval($_GET['id']);
$query = mysqli_query($koneksi, "
    SELECT b.*, s.name as service_name, s.price, bar.name as barber_name 
    FROM bookings b 
    LEFT JOIN services s ON b.service_id = s.id_service 
    LEFT JOIN barbers bar ON b.barber_id = bar.id_barber 
    WHERE b.id = $id_booking
");
$row = mysqli_fetch_assoc($query);

if (!$row) {
    echo "Data booking tidak ditemukan!";
    exit;
}

// Jika tombol konfirmasi dibayar diklik
if (isset($_POST['confirm_payment'])) {
    mysqli_query($koneksi, "UPDATE bookings SET payment_status = 'paid' WHERE id = $id_booking");
    header("Location: success_booking.php?id=$id_booking");
    exit;
}
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Pembayaran QRIS | GHB Barbershop</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body { background: #050505; color: #fff; font-family: 'Montserrat', sans-serif; }
        .qris-card { background: #121212; border: 1px solid #c5a059; padding: 40px; border-radius: 8px; max-width: 500px; margin: 60px auto; text-align: center; }
        .qris-img { width: 220px; height: 220px; background: #fff; padding: 10px; margin: 20px auto; border-radius: 4px; }
        .btn-pay { background: #c5a059; color: #000; font-weight: bold; width: 100%; padding: 15px; border-radius: 2px; text-transform: uppercase; border: none; }
        .btn-pay:hover { background: #e8d3a2; }
    </style>
</head>
<body>
    <div class="container">
        <div class="qris-card shadow-lg">
            <span style="color: #c5a059; font-size: 11px; letter-spacing: 2px; text-transform: uppercase;">Scan QRIS untuk Pembayaran</span>
            <h3 style="font-family: 'Playfair Display', serif; margin-top: 10px;">GHB BARBERSHOP</h3>
            
            <div class="my-3">
                <p class="mb-1 text-muted">Nomor Antrian Anda:</p>
                <h2 style="color: #28a745; font-family: monospace;"><?= $row['queue_number']; ?></h2>
            </div>

            <div class="qris-img">
                <!-- Simulasi Gambar QR Code -->
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=GHB-BARBERSHOP-PAYMENT-<?= $row['booking_code']; ?>" alt="QRIS Code" style="width: 100%;">
            </div>

            <h4 class="text-warning mb-1">Rp <?= number_format($row['price'], 0, ',', '.'); ?></h4>
            <p class="text-muted" style="font-size: 12px;">Scan menggunakan GoPay, OVO, Dana, BCA, Mobile Banking, atau QRIS Lainnya.</p>

            <form action="" method="POST" class="mt-4">
                <button type="submit" name="confirm_payment" class="btn btn-pay">Saya Sudah Membayar</button>
            </form>
            <a href="booking.php" class="d-block mt-3 text-muted" style="font-size: 12px; text-decoration: underline;">Batalkan / Kembali</a>
        </div>
    </div>
</body>
</html>