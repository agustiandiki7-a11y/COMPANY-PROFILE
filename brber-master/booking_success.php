<?php
include "backend/connection.php"; // Sesuaikan path jika file koneksimu berbeda

$code = trim($_GET['code'] ?? '');
if ($code === '') {
    header('Location: booking.php');
    exit;
}

// Ambil data booking berdasarkan kode[cite: 1]
$stmt = mysqli_prepare($koneksi, "SELECT * FROM bookings WHERE booking_code = ? LIMIT 1");
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
    <title>Booking Berhasil - GHD Barbershop</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body style="background: #0d0d0d; color: #fff;">
    <main class="container" style="padding: 80px 0; text-align: center;">
        <div class="row justify-content-center">
            <div class="col-md-6" style="background: #171717; padding: 40px; border-radius: 12px; border: 1px solid #2b2b2b;">
                <div style="width: 70px; height: 70px; border-radius: 50%; background: #f0b90b; color: #111; font-size: 40px; line-height: 70px; margin: 0 auto 20px; font-weight: bold;">✓</div>
                <p style="color: #f0b90b; font-weight: 800; letter-spacing: 2px;">BOOKING BERHASIL</p>
                <h1>Sampai Jumpa di GHD!</h1>
                <p style="color: #a7a7a7;">Tunjukkan kode booking berikut saat datang ke barbershop.</p>
                
                <div style="font-size: 26px; font-weight: 900; letter-spacing: 2px; padding: 15px; border: 2px dashed #f0b90b; border-radius: 10px; margin: 25px 0; color: #fff;">
                    <?= htmlspecialchars($booking['booking_code']); ?>
                </div>
                
                <p style="margin-bottom: 25px;">Status: <strong style="color: #28a745; text-transform: uppercase;"><?= htmlspecialchars($booking['status']); ?></strong></p>
                
                <a href="index.php" class="btn btn-primary" style="background: #f0b90b; border: none; color: #111; font-weight: bold; padding: 12px 25px; border-radius: 8px; text-decoration: none; display: inline-block;">Kembali ke Home</a>
            </div>
        </div>
    </main>
</body>
</html>