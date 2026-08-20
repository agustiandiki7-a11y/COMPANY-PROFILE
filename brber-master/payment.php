<?php
include "../backend/connection.php"; // Sesuaikan path jika file koneksimu berbeda

$code = trim($_GET['code'] ?? '');
if ($code === '') {
    header('Location: booking.php');
    exit;
}

// Ambil data booking & pembayaran dari database
$stmt = mysqli_prepare($koneksi, "SELECT b.*, p.order_id, p.status as payment_status FROM bookings b JOIN payments p ON b.id = p.booking_id WHERE b.booking_code = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "s", $code);
mysqli_stmt_execute($stmt);
$booking = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$booking) {
    exit('Data booking tidak ditemukan.');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran QRIS - GHD Barbershop</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body style="background: #0d0d0d; color: #fff;">
    <header class="navbar">
        <a class="brand" href="index.php" style="color: #fff; text-decoration: none;">GHD <span>BARBERSHOP</span></a>
    </header>

    <main class="container" style="padding: 60px 0;">
        <div class="row justify-content-center">
            <!-- Kotak Utama disesuaikan dengan tema dark-mode website (#171717) -->
            <div class="col-md-6" style="background: #171717; padding: 35px; border-radius: 14px; border: 1px solid #2b2b2b; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                <p style="color: #f0b90b; font-weight: 800; letter-spacing: 2px; margin-bottom: 5px; font-size: 13px;">PAYMENT QRIS</p>
                <h2 style="color: #fff; font-weight: 700; margin-bottom: 25px;">Selesaikan Pembayaran</h2>
                
                <!-- Box Detail Tagihan dengan latar belakang lebih gelap (#0f0f0f) agar kontras rapi -->
                <div style="background: #0f0f0f; border: 1px solid #3a3a3a; border-radius: 10px; padding: 20px; margin: 20px 0; text-align: left;">
                    <p style="margin: 8px 0; color: #a7a7a7;">Kode Booking: <strong style="color: #fff;"><?= htmlspecialchars($booking['booking_code']); ?></strong></p>
                    <p style="margin: 8px 0; color: #a7a7a7;">Nama Pelanggan: <strong style="color: #fff;"><?= htmlspecialchars($booking['customer_name']); ?></strong></p>
                    <p style="margin: 8px 0; color: #a7a7a7;">Total Tagihan: <strong style="color: #f0b90b; font-size: 18px;">Rp <?= number_format($booking['total_price'], 0, ',', '.'); ?></strong></p>
                </div>

                <!-- Bagian Gambar QRIS -->
                <div style="background: #fff; padding: 15px; border-radius: 10px; display: inline-block; margin-bottom: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                    <img src="../backend/foto/qris.png" alt="QRIS Code" style="width: 200px; height: 200px; object-fit: contain; display: block;">
                    <p style="color: #333; font-size: 12px; margin-top: 8px; margin-bottom: 0; font-weight: bold;">Scan pakai m-Banking / E-Wallet</p>
                </div>

                <form action="payment_confirm.php" method="POST">
                    <input type="hidden" name="booking_code" value="<?= htmlspecialchars($booking['booking_code']); ?>">
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; background: #f0b90b; border: none; color: #111; font-weight: 800; border-radius: 8px; cursor: pointer; font-size: 15px;">
                        SAYA SUDAH MEMBAYAR
                    </button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>