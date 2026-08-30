<?php
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}
include "../backend/connection.php";

$booking_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil logo untuk favicon dari database
$q_favicon = mysqli_query($koneksi, "SELECT logo FROM profile LIMIT 1");
$row_fav = $q_favicon ? mysqli_fetch_assoc($q_favicon) : [];
$logo_fav = !empty($row_fav['logo']) ? '../backend/foto/' . $row_fav['logo'] : '';

$cek_kolom = mysqli_query($koneksi, "SHOW COLUMNS FROM bookings LIKE 'id_booking'");
$id_col = (mysqli_num_rows($cek_kolom) > 0) ? 'id_booking' : 'id';

$query = mysqli_query($koneksi, "
    SELECT b.*, s.name as service_name, s.price, bar.name as barber_name 
    FROM bookings b 
    LEFT JOIN services s ON b.service_id = s.id_service 
    LEFT JOIN barbers bar ON b.barber_id = bar.id_barber 
    WHERE b.$id_col = $booking_id
");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data booking tidak ditemukan!'); window.location.href='index.php';</script>";
    exit;
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>E-Ticket Bukti Booking | GHD Barbershop</title>
    <?php if (!empty($logo_fav)): ?>
    <link rel="icon" type="image/x-icon" href="<?= $logo_fav; ?>">
    <?php endif; ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/fontawesome-all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --lux-black: #050505;
            --lux-surface: #121212;
            --lux-gold: #c5a059;
            --lux-white: #f8f8f8;
            --lux-text: #a3a3a3;
        }
        body { background: var(--lux-black) !important; color: var(--lux-text) !important; font-family: 'Montserrat', sans-serif; padding: 40px 0; }
        .ticket-card { background: var(--lux-surface); border: 2px dashed var(--lux-gold); border-radius: 12px; box-shadow: 0 20px 40px rgba(0,0,0,0.9); padding: 35px; }
        h2 { font-family: 'Playfair Display', serif; color: var(--lux-white); }
        .table-ticket td { border: none !important; color: #fff; padding: 8px 0; }
        .btn-lux { background: var(--lux-gold); color: var(--lux-black); font-weight: 600; text-transform: uppercase; padding: 12px 20px; border-radius: 4px; border: none; letter-spacing: 1px; font-size: 12px; text-decoration: none; display: inline-block; transition: 0.3s; }
        .btn-lux:hover { background: #e8d3a2; color: var(--lux-black); text-decoration: none; box-shadow: 0 0 15px rgba(197,160,89,0.4); }
        @media print {
            body { background: #fff !important; color: #000 !important; }
            .no-print { display: none !important; }
            .ticket-card { border: 2px solid #000; box-shadow: none; background: #fff !important; color: #000 !important; }
            .ticket-card td { color: #000 !important; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="ticket-card">
                    <div class="text-center mb-4">
                        <span style="color: var(--lux-gold); font-size: 10px; letter-spacing: 3px; text-transform: uppercase;">Official E-Ticket</span>
                        <h2>GHD Barbershop</h2>
                        <p class="text-muted" style="font-size: 11px;">Simpan bukti booking ini atau tunjukkan kepada kasir/barber.</p>
                    </div>

                    <div class="text-center mb-4 p-3 rounded" style="background: rgba(197,160,89,0.05); border: 1px solid rgba(197,160,89,0.2);">
                        <span class="text-muted" style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Kode Booking</span>
                        <h3 style="color: var(--lux-gold); font-family: monospace; font-weight: 700; margin: 5px 0 0 0;"><?= htmlspecialchars($data['booking_code']); ?></h3>
                    </div>

                    <table class="table table-ticket mb-4">
                        <tr>
                            <td width="35%" class="text-muted">Nama Pelanggan</td>
                            <td>: <b class="text-white"><?= htmlspecialchars($data['customer_name']); ?></b></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nomor WhatsApp</td>
                            <td>: <?= htmlspecialchars($data['customer_phone']); ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Layanan Cukur</td>
                            <td>: <?= htmlspecialchars($data['service_name'] ?? 'Cukur Standar'); ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Barber Pilihan</td>
                            <td>: <?= htmlspecialchars($data['barber_name'] ?? 'Barber Profesional'); ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nomor Kursi</td>
                            <td>: <strong style="color: #28a745;"><?= htmlspecialchars($data['chair_number']); ?></strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Jadwal Kedatangan</td>
                            <td>: <?= date('d M Y', strtotime($data['booking_date'])); ?> (Pukul <?= htmlspecialchars($data['booking_time']); ?> WIB)</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Total Pembayaran</td>
                            <td>: <strong style="color: var(--lux-gold);">Rp <?= number_format($data['price'] ?? 0, 0, ',', '.'); ?> (Lunas / Disetujui)</strong></td>
                        </tr>
                    </table>

                    <div class="text-center no-print" style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
                        <button onclick="window.print()" class="btn-lux"><i class="fas fa-print mr-1"></i> Cetak / Simpan E-Ticket</button>
                        <a href="riwayat_booking.php" class="btn btn-outline-light" style="font-size: 12px; font-weight: 600; text-transform: uppercase; border-color: rgba(197,160,89,0.4); color: var(--lux-gold); padding: 10px 20px;">Lihat Riwayat Saya</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>