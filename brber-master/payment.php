<?php
include "../backend/connection.php";

$code = trim($_GET['code'] ?? '');
if ($code === '') {
    header('Location: booking.php');
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
        'phone' => '-',
        'email' => '-',
        'instagram' => '#',
        'opening_hours' => '09:00 - 21:00',
        'logo' => ''
    ];
}

/* =========================
   AMBIL DATA BOOKING, LAYANAN, & BARBER
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
    <title>Pembayaran QRIS | <?php echo e(isset($p['name']) ? $p['name'] : 'Barbershop'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon / Logo Tab -->
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

    <!-- CUSTOM PREMIUM LUXURY STYLING -->
    <style>
        :root {
            --lux-black: #050505;
            --lux-dark: #0a0a0a;
            --lux-surface: #121212;
            --lux-gold: #c5a059;
            --lux-gold-light: #e8d3a2;
            --lux-gold-dim: rgba(197, 160, 89, 0.2);
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

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--lux-black); }
        ::-webkit-scrollbar-thumb { background: var(--lux-gold); }

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
        .btn-lux-outline:hover { background: var(--lux-gold) !important; color: var(--lux-black) !important; box-shadow: 0 0 15px rgba(197, 160, 89, 0.4); }
        .btn-lux-solid {
            background: var(--lux-gold) !important; color: var(--lux-black) !important; border: 1px solid var(--lux-gold) !important;
            padding: 10px 20px !important; font-size: 11px !important; font-weight: 600 !important; letter-spacing: 1.5px; text-transform: uppercase; border-radius: 0; transition: var(--transition-smooth);
        }
        .btn-lux-solid:hover { background: transparent !important; color: var(--lux-gold) !important; box-shadow: 0 0 15px rgba(197, 160, 89, 0.4); }

        /* MOBILE MENU */
        .mobile_menu { width: 100%; }
        .slicknav_menu { background: transparent !important; padding: 0 !important; margin: 0 !important; }
        .slicknav_btn { background-color: transparent !important; margin: 0 !important; padding: 5px 0 !important; cursor: pointer; }
        .slicknav_icon-bar { background-color: var(--lux-gold) !important; box-shadow: none !important; width: 28px !important; height: 3px !important; margin: 5px 0 !important; display: block; border-radius: 2px; }
        .slicknav_nav { background: var(--lux-dark) !important; border: 1px solid rgba(197, 160, 89, 0.2) !important; border-radius: 4px; margin-top: 15px !important; text-align: left; position: absolute; width: 100%; right: 0; z-index: 9999; box-shadow: 0 10px 30px rgba(0,0,0,0.8); }
        .slicknav_nav a { color: var(--lux-white) !important; font-family: var(--font-body) !important; font-size: 13px !important; text-transform: uppercase; letter-spacing: 1px; padding: 15px 20px !important; margin: 0 !important; border-bottom: 1px solid rgba(255,255,255,0.05); transition: var(--transition-smooth); }
        .slicknav_nav a:hover { background: rgba(197, 160, 89, 0.1) !important; color: var(--lux-gold) !important; }

        @media (max-width: 991px) {
            .header-area .main-header { padding: 15px 20px !important; }
            .header-sticky.sticky-bar { padding: 10px 20px !important; }
            .header-area .logo a h3 { font-size: 18px !important; }
        }

        /* PAYMENT QRIS CARD STYLING */
        .payment-hero {
            padding: 180px 0 60px 0;
            background: linear-gradient(180deg, var(--lux-black) 0%, var(--lux-dark) 100%);
            text-align: center;
        }

        .lux-payment-card {
            background: var(--lux-surface);
            padding: 45px;
            border: 1px solid rgba(197, 160, 89, 0.2);
            border-radius: 6px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.7);
            text-align: center;
        }

        .lux-payment-detail {
            background: var(--lux-black);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 6px;
            padding: 22px;
            margin: 25px 0;
            text-align: left;
        }

        .lux-spinner {
            width: 80px; height: 80px; border: 2px solid rgba(197, 160, 89, 0.1);
            border-top-color: var(--lux-gold); border-radius: 50%; animation: spin 1s linear infinite;
        }
    </style>
</head>

<body>

    <!-- LUXURY PRELOADER -->
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

                        <!-- LOGO -->
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

                        <!-- NAVIGATION & BUTTONS -->
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
        <div class="payment-hero"></div>

        <div class="container pb-130">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    
                    <div class="lux-payment-card" id="cardContainer">
                        <span style="color: var(--lux-gold); font-weight: 600; letter-spacing: 3px; font-size: 11px; text-transform: uppercase; display: block; margin-bottom: 8px;">Payment QRIS</span>
                        <h2 style="font-size: 32px; margin-bottom: 25px;" id="mainTitle">Selesaikan Pembayaran</h2>
                        
                        <!-- Detail Tagihan (Otomatis Menyesuaikan Harga Layanan & Nomor Kursi) -->
                        <div class="lux-payment-detail" id="detailBox">
                            <p style="margin: 8px 0; color: var(--lux-text); font-size: 14px;">Kode Booking: <strong style="color: var(--lux-white);"><?= htmlspecialchars($booking['booking_code']); ?></strong></p>
                            <p style="margin: 8px 0; color: var(--lux-text); font-size: 14px;">Nama Pelanggan: <strong style="color: var(--lux-white);"><?= htmlspecialchars($booking['customer_name']); ?></strong></p>
                            <p style="margin: 8px 0; color: var(--lux-text); font-size: 14px;">Layanan: <strong style="color: var(--lux-white);"><?= htmlspecialchars($booking['service_name'] ?? 'Layanan Barbershop'); ?></strong></p>
                            <p style="margin: 8px 0; color: var(--lux-text); font-size: 14px;">Nomor Kursi: <strong style="color: var(--lux-gold);"><?= htmlspecialchars($booking['chair_number'] ?? 'Kursi 01'); ?></strong></p>
                            <p style="margin: 8px 0; color: var(--lux-text); font-size: 14px;">Total Tagihan: <strong style="color: var(--lux-gold); font-size: 18px; font-family: var(--font-head);">Rp <?= number_format($booking['price'] ?? 0, 0, ',', '.'); ?></strong></p>
                        </div>

                        <!-- Area QRIS & Hitung Mundur Waktu Scan -->
                        <div id="qris-wrapper">
                            <div style="background: #fff; padding: 15px; border-radius: 8px; display: inline-block; margin-bottom: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.4);">
                                <img src="../backend/foto/qriss1.jpeg" alt="QRIS Code" style="width: 200px; height: 200px; object-fit: contain; display: block;">
                            </div>
                            <p style="color: var(--lux-gold); font-size: 13px; margin-bottom: 0; font-weight: 500;">
                                <i class="fas fa-spinner fa-spin"></i> Menunggu scan QRIS (<span id="timer">01:00</span>)
                            </p>
                        </div>

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

        // Pengaturan Waktu Mundur 30 Detik
        let timeLeft = 30;
        const timerElement = document.getElementById('timer');

        const countdown = setInterval(function() {
            let minutes = Math.floor(timeLeft / 30);
            let seconds = timeLeft % 30;

            minutes = minutes < 10 ? '0' + minutes : minutes;
            seconds = seconds < 10 ? '0' + seconds : seconds;

            timerElement.textContent = minutes + ':' + seconds;

            if (timeLeft <= 0) {
                clearInterval(countdown);
                
                // Setelah 30 detik habis, pindah otomatis ke success.php membawa kode booking
                const card = document.getElementById('cardContainer');
                card.innerHTML = `
                    <span style="color: #28a745; font-weight: 600; letter-spacing: 3px; font-size: 11px; text-transform: uppercase; display: block; margin-bottom: 8px;"><i class="fas fa-check-circle"></i> Berhasil</span>
                    <h2 style="font-size: 32px; margin-bottom: 25px;">Pembayaran Selesai</h2>
                    <div style="padding: 30px 0;">
                        <i class="fas fa-check-circle" style="font-size: 70px; color: #28a745; margin-bottom: 20px;"></i>
                        <p style="color: #fff; font-size: 15px; margin-bottom: 10px;">Transaksi QRIS Anda telah dikonfirmasi.</p>
                        <p style="color: var(--lux-text); font-size: 13px;">Mengarahkan ke bukti pemesanan...</p>
                    </div>
                `;

                setTimeout(function() {
                    window.location.href = "success.php?code=<?= htmlspecialchars($code); ?>";
                }, 2000);
            }

            timeLeft--;
        }, 1000);
    </script>
</body>
</html>