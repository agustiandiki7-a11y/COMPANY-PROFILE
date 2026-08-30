<?php
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}
include "../backend/connection.php";

$booking_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Deteksi nama kolom primary key pada tabel bookings secara otomatis
$cek_kolom = mysqli_query($koneksi, "SHOW COLUMNS FROM bookings LIKE 'id_booking'");
$id_col = (mysqli_num_rows($cek_kolom) > 0) ? 'id_booking' : 'id';

// Ambil data booking beserta harga layanannya
$query = mysqli_query($koneksi, "
    SELECT b.*, s.name as service_name, s.price 
    FROM bookings b 
    LEFT JOIN services s ON b.service_id = s.id_service 
    WHERE b.$id_col = $booking_id
");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data booking tidak ditemukan!'); window.location.href='index.php';</script>";
    exit;
}

// Jika tombol dikirim atau timer 5 detik habis
if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_GET['process'])) {
    mysqli_query($koneksi, "UPDATE bookings SET status = 'Disetujui' WHERE $id_col = $booking_id");
    echo "<script>window.location.href='bukti_booking.php?id=$booking_id';</script>";
    exit;
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Pembayaran QRIS | GHD Barbershop</title>
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
        body { 
            background: var(--lux-black) !important; 
            color: var(--lux-text) !important; 
            font-family: 'Montserrat', sans-serif; 
            padding: 40px 0; 
        }
        .card-lux { 
            background: var(--lux-surface); 
            border: 1px solid rgba(197, 160, 89, 0.3); 
            border-radius: 12px; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.9); 
            padding: 30px; 
            text-align: center; 
        }
        h2 { 
            font-family: 'Playfair Display', serif; 
            color: var(--lux-white); 
        }
        .qris-box { 
            background: #ffffff; 
            padding: 20px; 
            border-radius: 8px; 
            display: inline-block; 
            margin: 20px 0; 
            border: 2px solid var(--lux-gold);
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
        }
        .btn-lux { 
            background: var(--lux-gold); 
            color: var(--lux-black); 
            font-weight: 600; 
            text-transform: uppercase; 
            padding: 12px 20px; 
            border-radius: 4px; 
            border: none; 
            letter-spacing: 1px; 
            font-size: 12px; 
            width: 100%; 
            transition: 0.3s; 
            cursor: pointer; 
        }
        .btn-lux:hover { 
            background: #e8d3a2; 
            box-shadow: 0 0 15px rgba(197,160,89,0.4); 
        }
        .countdown-text { 
            color: var(--lux-gold); 
            font-size: 13px; 
            font-weight: 600; 
            margin-top: 15px; 
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card-lux">
                    <span style="color: var(--lux-gold); font-size: 10px; letter-spacing: 3px; text-transform: uppercase;">GHD Secure Payment</span>
                    <h2>Scan & Bayar QRIS</h2>
                    <p class="text-muted" style="font-size: 12px;">Kode Booking: <strong style="color: var(--lux-gold); font-family: monospace;"><?= htmlspecialchars($data['booking_code']); ?></strong></p>
                    
                    <div class="my-2 p-3 rounded" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(197,160,89,0.15);">
                        <span style="font-size: 12px; color: var(--lux-text);">Total Tagihan Layanan:</span>
                        <h3 style="color: #28a745; font-weight: 700; margin: 5px 0 0 0;">Rp <?= number_format($data['price'] ?? 50000, 0, ',', '.'); ?></h3>
                    </div>

                    <!-- Gambar QRIS -->
                    <div class="qris-box">
                        <img src="assets/img/qr.png" alt="QRIS GHD Barbershop" style="width: 190px; height: 190px; object-fit: contain;">
                        <span class="d-block text-dark mt-2" style="font-size: 11px; font-weight: bold;">GoPay / OVO / Dana / BCA / Mobile Banking</span>
                    </div>

                    <!-- Indikator Jeda Waktu 5 Detik -->
                    <div class="countdown-text" id="status-text">
                        Menunggu konfirmasi pembayaran otomatis dalam <span id="seconds">5</span> detik...
                    </div>

                    <form id="payment-form" method="POST" class="mt-3">
                        <button type="submit" name="konfirmasi_bayar" class="btn-lux">Konfirmasi Pembayaran Manual</button>
                    </form>
                    
                    <a href="index.php" class="d-block mt-3 text-muted" style="font-size: 11px; text-decoration: none;">&larr; Batalkan Pesanan</a>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT JEDA 5 DETIK OTOMATIS -->
    <script>
        let timeLeft = 5;
        const elem = document.getElementById('seconds');
        const statusText = document.getElementById('status-text');

        const timer = setInterval(() => {
            timeLeft--;
            elem.textContent = timeLeft;
            if (timeLeft <= 0) {
                clearInterval(timer);
                statusText.innerHTML = "Pembayaran Berhasil! Mengalihkan ke E-Ticket...";
                setTimeout(() => {
                    document.getElementById('payment-form').submit();
                }, 1000);
            }
        }, 1000);
    </script>
</body>
</html>