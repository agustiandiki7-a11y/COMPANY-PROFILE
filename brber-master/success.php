<?php
include "../backend/connection.php";

$code = trim($_GET['code'] ?? '');
if ($code === '') {
    header('Location: index.php');
    exit;
}

/* =========================
   HELPER
========================= */
function e($value)
{
    return htmlspecialchars(isset($value) ? $value : '', ENT_QUOTES, 'UTF-8');
}

/* =========================
   PROFILE (Untuk Header & Footer)
========================= */
$query_profile = mysqli_query($koneksi, "SELECT * FROM profile LIMIT 1");
$p = $query_profile ? mysqli_fetch_assoc($query_profile) : false;

if (!$p) {
    $p = [
        'name' => 'GHB BARBERSHOP',
        'description' => 'Barbershop profesional dengan pelayanan terbaik.',
        'address' => 'Banjar, Jawa Barat',
        'phone' => '081234567890',
        'email' => '-',
        'instagram' => '#',
        'opening_hours' => '09:00 - 21:00',
        'logo' => ''
    ];
}

/* =========================
   AMBIL DATA BOOKING, SERVICE & BARBER
========================= */
$stmt = mysqli_prepare($koneksi, "
    SELECT b.*, s.name as service_name, s.price, bar.name as barber_name 
    FROM bookings b 
    LEFT JOIN services s ON b.service_id = s.id_service 
    LEFT JOIN barbers bar ON b.barber_id = bar.id_barber 
    WHERE b.booking_code = ? 
    LIMIT 1
");
mysqli_stmt_bind_param($stmt, "s", $code);
mysqli_stmt_execute($stmt);
$booking = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$booking) {
    exit('Data booking tidak ditemukan.');
}
?>

<!doctype html>
<html class="no-js" lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Booking Sukses | <?php echo e(isset($p['name']) ? $p['name'] : 'Barbershop'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../backend/foto/logo.ico">
    
    <!-- PREMIUM FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/slicknav.css">
    <link rel="stylesheet" href="assets/css/flaticon.css">
    <link rel="stylesheet" href="assets/css/gijgo.css">
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <link rel="stylesheet" href="assets/css/animated-headline.css">
    <link rel="stylesheet" href="assets/css/magnific-popup.css">
    <link rel="stylesheet" href="assets/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/css/themify-icons.css">
    <link rel="stylesheet" href="assets/css/slick.css">
    <link rel="stylesheet" href="assets/css/nice-select.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- CUSTOM LUXURY STYLING -->
    <style>
        :root {
            --lux-black: #050505;
            --lux-dark: #0a0a0a;
            --lux-surface: #121212;
            --lux-gold: #c5a059;
            --lux-gold-light: #e8d3a2;
            --lux-white: #f8f8f8;
            --lux-text: #a3a3a3;
            --font-head: 'Playfair Display', serif;
            --font-body: 'Montserrat', sans-serif;
            --transition-smooth: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        html { scroll-behavior: smooth; }
        
        body {
            background: var(--lux-black) !important;
            color: var(--lux-text) !important;
            font-family: var(--font-body) !important;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-head) !important;
            color: var(--lux-white) !important;
            letter-spacing: 0.5px;
        }

        /* HEADER */
        .header-area {
            position: absolute !important; top: 0; left: 0; right: 0; width: 100%; z-index: 999;
        }
        .header-area .main-header {
            min-height: 90px; padding: 15px 50px !important; background: rgba(5, 5, 5, 0.8) !important;
            backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05); transition: var(--transition-smooth);
        }
        .header-sticky.sticky-bar { background: rgba(5, 5, 5, 0.98) !important; box-shadow: 0 5px 20px rgba(0,0,0,0.5); padding: 10px 50px !important; }
        
        .header-area .logo a { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .header-area .logo a img { max-height: 60px; width: auto; object-fit: contain; }
        .header-area .logo a h3 { color: var(--lux-white) !important; font-family: var(--font-head); font-size: 22px !important; font-weight: 600; letter-spacing: 1px; margin: 0; white-space: nowrap; }

        .header-area .main-menu ul { display: flex; align-items: center; gap: 5px; }
        .header-area .main-menu ul li a {
            color: #d1d1d1 !important; font-size: 13px !important; font-weight: 400; letter-spacing: 1px;
            text-transform: uppercase; padding: 25px 15px !important; transition: var(--transition-smooth);
        }
        .header-area .main-menu ul li a:hover, .header-area .main-menu ul li.active>a { color: var(--lux-gold) !important; }

        /* BUTTONS */
        .header-right-actions { display: flex; gap: 12px; align-items: center; }
        .btn-lux-outline {
            background: transparent !important; color: var(--lux-gold) !important; border: 1px solid var(--lux-gold) !important;
            padding: 10px 20px !important; font-size: 11px !important; font-weight: 600 !important; letter-spacing: 1.5px; text-transform: uppercase; border-radius: 0; transition: var(--transition-smooth);
        }
        .btn-lux-outline:hover { background: var(--lux-gold) !important; color: var(--lux-black) !important; }
        .btn-lux-solid {
            background: var(--lux-gold) !important; color: var(--lux-black) !important; border: 1px solid var(--lux-gold) !important;
            padding: 10px 20px !important; font-size: 11px !important; font-weight: 600 !important; letter-spacing: 1.5px; text-transform: uppercase; border-radius: 0; transition: var(--transition-smooth);
        }

        /* INVOICE CARD */
        .success-hero { padding: 180px 0 50px 0; background: linear-gradient(180deg, var(--lux-black) 0%, var(--lux-dark) 100%); text-align: center; }
        
        .lux-receipt-card {
            background: var(--lux-surface);
            padding: 50px;
            border: 1px solid rgba(197, 160, 89, 0.2);
            border-radius: 8px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.8);
            position: relative;
        }

        .receipt-table td {
            padding: 12px 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            font-size: 14px;
        }

        .btn-wa-confirm {
            background: #25d366 !important;
            color: #fff !important;
            font-weight: 700;
            padding: 14px;
            border-radius: 4px;
            width: 100%;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: none;
            transition: var(--transition-smooth);
            display: block;
            text-align: center;
            text-decoration: none;
            margin-top: 25px;
        }
        .btn-wa-confirm:hover { background: #20ba5a !important; color: #fff; box-shadow: 0 5px 15px rgba(37, 211, 102, 0.3); }

        .btn-print {
            background: var(--lux-gold) !important;
            color: var(--lux-black) !important;
            font-weight: 700;
            padding: 14px;
            border-radius: 4px;
            width: 100%;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: none;
            transition: var(--transition-smooth);
            display: block;
            text-align: center;
            cursor: pointer;
            margin-top: 12px;
        }
        .btn-print:hover { background: transparent !important; color: var(--lux-gold) !important; border: 1px solid var(--lux-gold); }

        .btn-home-back {
            background: transparent !important;
            color: var(--lux-gold) !important;
            border: 1px solid var(--lux-gold) !important;
            font-weight: 600;
            padding: 14px;
            border-radius: 4px;
            width: 100%;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: var(--transition-smooth);
            display: block;
            text-align: center;
            text-decoration: none;
            margin-top: 12px;
        }
        .btn-home-back:hover { background: var(--lux-gold) !important; color: var(--lux-black) !important; }

        .lux-spinner {
            width: 80px; height: 80px; border: 2px solid rgba(197, 160, 89, 0.1);
            border-top-color: var(--lux-gold); border-radius: 50%; animation: spin 1s linear infinite;
        }

        /* CSS KHUSUS CETAK/PRINT (HANYA STRUK YANG TERCETAK) */
        @media print {
            body { background: #fff !important; color: #000 !important; }
            header, footer, .header-area, .success-hero, .btn-wa-confirm, .btn-print, .btn-home-back {
                display: none !important;
            }
            .lux-receipt-card {
                background: #fff !important;
                border: 2px solid #000 !important;
                box-shadow: none !important;
                color: #000 !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 20px !important;
            }
            .lux-receipt-card h2, .lux-receipt-card span, .lux-receipt-card td, .lux-receipt-card strong {
                color: #000 !important;
            }
            .receipt-table td {
                border-bottom: 1px solid #ccc !important;
            }
        }
    </style>
</head>

<body>

    <!-- PRELOADER -->
    <div id="preloader-active" style="position: fixed; inset: 0; background: #050505; z-index: 999999; display: flex; align-items: center; justify-content: center; transition: opacity 0.8s ease; opacity: 1;">
        <div style="position: relative; width: 90px; height: 90px; display: flex; align-items: center; justify-content: center;">
            <div class="lux-spinner" style="position: absolute; width: 100%; height: 100%;"></div>
            <?php if (!empty($p['logo'])): ?>
                <img src="../backend/foto/<?php echo e($p['logo']); ?>" alt="<?php echo e($p['name']); ?>" style="width: 45%; height: auto; object-fit: contain; opacity: 0.8;">
            <?php endif; ?>
        </div>
    </div>

    <!-- HEADER -->
    <header>
        <div class="header-area header-transparent">
            <div class="main-header header-sticky">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5 col-5">
                            <div class="logo">
                                <a href="index.php">
                                    <?php if (!empty($p['logo'])): ?>
                                        <img src="../backend/foto/<?php echo e($p['logo']); ?>" alt="<?php echo e(isset($p['name']) ? $p['name'] : ''); ?>">
                                    <?php else: ?>
                                        <img src="assets/img/logo/logo.png" alt="<?php echo e(isset($p['name']) ? $p['name'] : ''); ?>">
                                    <?php endif; ?>
                                    <h3 class="d-none d-sm-block"><?php echo e(isset($p['name']) ? $p['name'] : ''); ?></h3>
                                </a>
                            </div>
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-8 col-sm-7 col-7">
                            <div class="menu-main d-flex align-items-center justify-content-end w-100">
                                <div class="main-menu f-right d-none d-lg-block">
                                    <nav>
                                        <ul id="navigation">
                                            <li><a href="index.php">Home</a></li>
                                            <li><a href="index.php#about">About</a></li>
                                            <li><a href="index.php#services">Services</a></li>
                                            <li><a href="index.php#barbers">Barbers</a></li>
                                            <li><a href="index.php#pricing">Pricing</a></li>
                                            <li><a href="index.php#portfolio">Portfolio</a></li>
                                            <li><a href="index.php#contact">Contact</a></li>
                                        </ul>
                                    </nav>
                                </div>
                                <div class="header-right-actions f-right d-none d-lg-flex ml-30">
                                    <a href="langganan.php" class="btn btn-lux-outline">Langganan</a>
                                    <a href="booking.php" class="btn btn-lux-solid">Booking</a>
                                </div>
                                <div class="mobile_menu d-block d-lg-none text-right"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main>
        <div class="success-hero"></div>

        <div class="container pb-130">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-8">
                    
                    <div class="lux-receipt-card text-center">
                        <div style="margin-bottom: 20px;">
                            <i class="fas fa-check-circle" style="font-size: 65px; color: #28a745;"></i>
                        </div>
                        <span style="color: var(--lux-gold); font-weight: 600; letter-spacing: 3px; font-size: 11px; text-transform: uppercase; display: block; margin-bottom: 5px;">Reservasi Berhasil</span>
                        <h2 style="font-size: 36px; margin-bottom: 30px;">Bukti Pemesanan</h2>

                        <!-- Detail Struk / Invoice -->
                        <div style="background: var(--lux-black); border: 1px solid rgba(255,255,255,0.08); border-radius: 6px; padding: 25px; text-align: left; margin-bottom: 30px;" class="invoice-box-inner">
                            <table class="table table-borderless text-white mb-0 receipt-table">
                                <tr>
                                    <td style="color: var(--lux-text); width: 45%;">Kode Booking</td>
                                    <td>: <strong style="color: var(--lux-gold); font-family: monospace; font-size: 16px;"><?= e($booking['booking_code']); ?></strong></td>
                                </tr>
                                <tr>
                                    <td style="color: var(--lux-text);">Nama Pelanggan</td>
                                    <td>: <strong><?= e($booking['customer_name']); ?></strong></td>
                                </tr>
                                <tr>
                                    <td style="color: var(--lux-text);">No. WhatsApp</td>
                                    <td>: <?= e($booking['customer_phone']); ?></td>
                                </tr>
                                <tr>
                                    <td style="color: var(--lux-text);">Layanan Pilihan</td>
                                    <td>: <?= e($booking['service_name'] ?? 'Layanan Barbershop'); ?></td>
                                </tr>
                                <tr>
                                    <td style="color: var(--lux-text);">Barber</td>
                                    <td>: <?= e($booking['barber_name'] ?? 'Pilih Barber Professional'); ?></td>
                                </tr>
                                <tr>
                                    <td style="color: var(--lux-text);">Nomor Kursi</td>
                                    <td>: <strong style="color: var(--lux-gold); font-size: 15px;"><?= e($booking['chair_number'] ?? 'Kursi 01'); ?></strong></td>
                                </tr>
                                <tr>
                                    <td style="color: var(--lux-text);">Jadwal Kunjungan</td>
                                    <td>: <span style="color: var(--lux-gold-light);"><?= date('d M Y', strtotime($booking['booking_date'])); ?> pukul <?= e($booking['booking_time']); ?></span></td>
                                </tr>
                                <tr>
                                    <td style="color: var(--lux-text); font-weight: bold;">Status Pembayaran</td>
                                    <td>: <span class="badge bg-success" style="padding: 6px 12px; font-size: 12px; letter-spacing: 1px;">LUNAS (QRIS)</span></td>
                                </tr>
                            </table>
                        </div>

                        <!-- Tombol Aksi -->
                        <?php 
                            $wa_msg = "Halo Admin " . $p['name'] . ", saya ingin mengkonfirmasi jadwal booking dengan Kode: " . $booking['booking_code'] . " atas nama " . $booking['customer_name'] . " di " . ($booking['chair_number'] ?? 'Kursi 01') . " untuk tanggal " . $booking['booking_date'] . " pukul " . $booking['booking_time'] . ". Terima kasih!";
                            $wa_link = "https://wa.me/" . preg_replace('/[^0-9]/', '', $p['phone']) . "?text=" . urlencode($wa_msg);
                        ?>
                        
                        <a href="<?= $wa_link; ?>" target="_blank" class="btn-wa-confirm">
                            <i class="fab fa-whatsapp" style="font-size: 18px; margin-right: 8px;"></i> Kirim Konfirmasi ke WhatsApp
                        </a>

                        <!-- Tombol Cetak / Print -->
                        <button onclick="window.print()" class="btn-print">
                            <i class="fas fa-print" style="margin-right: 8px;"></i> Cetak Bukti Pembayaran
                        </button>

                        <a href="index.php" class="btn-home-back">
                            <i class="fas fa-home" style="margin-right: 6px;"></i> Kembali ke Beranda
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer style="background: #080808; border-top: 1px solid rgba(255,255,255,0.05); padding: 25px 0;">
        <div class="container text-center">
            <p class="m-0" style="color: #666; font-size: 13px; letter-spacing: 1px;">
                &copy; <?php echo date('Y'); ?> <?php echo e(isset($p['name']) ? $p['name'] : ''); ?>. All rights reserved.
            </p>
        </div>
    </footer>

    <!-- JS Files -->
    <script src="assets/js/vendor/modernizr-3.5.0.min.js"></script>
    <script src="assets/js/vendor/jquery-1.12.4.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.slicknav.min.js"></script>
    <script src="assets/js/main.js"></script>
    
    <script>
        window.addEventListener('load', function() {
            const preloader = document.getElementById('preloader-active');
            if (preloader) {
                preloader.style.opacity = '0';
                setTimeout(() => preloader.style.display = 'none', 800);
            }
        });
    </script>
</body>
</html>