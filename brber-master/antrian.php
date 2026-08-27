<?php
include "../backend/connection.php";
$hari_ini = date('Y-m-d');

// Ambil antrian yang sedang diproses atau approved hari ini
$q_antrian = mysqli_query($koneksi, "SELECT * FROM bookings WHERE booking_date = '$hari_ini' AND status IN ('pending', 'approved') ORDER BY id ASC");
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Monitor Antrian | GHB Barbershop</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body { background: #050505; color: #fff; font-family: 'Montserrat', sans-serif; text-align: center; padding-top: 50px; }
        .queue-box { background: #121212; border: 1px solid #c5a059; padding: 30px; border-radius: 8px; display: inline-block; width: 350px; margin: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h2 style="font-family: 'Playfair Display', serif; color: #c5a059;">STATUS ANTRIAN HARI INI</h2>
        <p class="text-muted"><?= date('d F Y'); ?></p>

        <div class="row justify-content-center mt-4">
            <?php while($row = mysqli_fetch_assoc($q_antrian)): ?>
            <div class="col-md-4">
                <div class="queue-box shadow">
                    <h5 style="color: #c5a059;"><?= htmlspecialchars($row['customer_name']); ?></h5>
                    <h1 style="font-size: 48px; color: #28a745; font-family: monospace; margin: 15px 0;"><?= $row['queue_number']; ?></h1>
                    <p class="mb-1">Kursi: <b><?= htmlspecialchars($row['chair_number']); ?></b></p>
                    <p class="mb-1">Barber: <?= htmlspecialchars($row['barber_id']); ?></p>
                    <span class="badge badge-<?= ($row['status'] == 'approved') ? 'success' : 'warning'; ?> px-3 py-2 mt-2">
                        <?= strtoupper($row['status']); ?>
                    </span>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <div class="mt-5">
            <a href="index.php" class="btn btn-outline-light btn-sm">Kembali ke Beranda</a>
        </div>
    </div>
</body>
</html>