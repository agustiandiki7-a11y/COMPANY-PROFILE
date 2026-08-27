<?php
session_start();
include "../backend/connection.php";

if (!isset($_GET['id'])) {
    header("Location: langganan.php");
    exit;
}

$id_sub = intval($_GET['id']);
$query = mysqli_query($koneksi, "SELECT * FROM subscriptions WHERE id_sub = $id_sub");
$row = mysqli_fetch_assoc($query);

if (!$row) {
    echo "Data langganan tidak ditemukan!";
    exit;
}

// Ambil profil barbershop untuk nama & logo
$query_profile = mysqli_query($koneksi, "SELECT * FROM profile LIMIT 1");
$p = $query_profile ? mysqli_fetch_assoc($query_profile) : ['name' => 'GHD BARBERSHOP', 'logo' => ''];

// Jika tombol batalkan diklik, hapus data langganan dari database lalu kembalikan ke halaman langganan
if (isset($_POST['cancel_payment'])) {
    mysqli_query($koneksi, "DELETE FROM subscriptions WHERE id_sub = $id_sub");
    header("Location: langganan.php");
    exit;
}
?>

<!doctype html>
<html class="no-js" lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Pembayaran QRIS | <?= htmlspecialchars($p['name']); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon / Icon Tab Sesuai GHD Barbershop -->
    <link rel="icon" type="image/x-icon" href="../backend/foto/logo.ico">
    
    <!-- PREMIUM FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&display=swap" rel="stylesheet">

    <!-- CSS STYLESHEETS GHD -->
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

    <!-- CUSTOM GHD LUXURY THEME OVERRIDE -->
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
            margin: 0;
            padding: 0;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-head) !important;
            color: var(--lux-white) !important;
            letter-spacing: 0.5px;
        }

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--lux-black); }
        ::-webkit-scrollbar-thumb { background: var(--lux-gold); }

        /* HEADER & NAVBAR GHD BARBERSHOP */
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

        /* PAYMENT CONTAINER */
        .payment-wrapper {
            padding: 180px 0 100px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .lux-payment-card {
            background: var(--lux-surface);
            border: 1px solid rgba(197, 160, 89, 0.15);
            padding: 50px;
            border-radius: 6px;
            max-width: 520px;
            width: 100%;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0,0,0,0.8);
            position: relative;
            z-index: 2;
        }

        .qris-box {
            width: 220px;
            height: 220px;
            background: #fff;
            padding: 10px;
            margin: 20px auto;
            border-radius: 4px;
            box-shadow: 0 0 20px rgba(255,255,255,0.05);
        }

        .timer-badge {
            background: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.4);
            color: #ff6b6b;
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 25px;
            font-weight: 600;
            font-size: 13px;
        }

        .btn-lux-cancel {
            background: #dc3545 !important;
            color: #fff !important;
            font-weight: 600;
            width: 100%;
            padding: 16px;
            border-radius: 4px;
            text-transform: uppercase;
            border: none;
            letter-spacing: 1.5px;
            font-size: 12px;
            transition: var(--transition-smooth);
        }
        .btn-lux-cancel:hover {
            background: #bd2130 !important;
            box-shadow: 0 0 15px rgba(220, 53, 69, 0.4);
        }

        .btn-lux-back {
            background: transparent !important;
            border: 1px solid var(--lux-gold) !important;
            color: var(--lux-gold) !important;
            font-weight: 600;
            width: 100%;
            padding: 16px;
            border-radius: 4px;
            text-transform: uppercase;
            margin-top: 12px;
            letter-spacing: 1.5px;
            font-size: 12px;
            transition: var(--transition-smooth);
            display: block;
            text-decoration: none;
        }
        .btn-lux-back:hover {
            background: var(--lux-gold) !important;
            color: var(--lux-black) !important;
            text-decoration: none;
            box-shadow: 0 0 15px rgba(197, 160, 89, 0.4);
        }

        #expired-view { display: none; }

        .lux-spinner {
            width: 80px; height: 80px; border: 2px solid rgba(197, 160, 89, 0.1);
            border-top-color: var(--lux-gold); border-radius: 50%; animation: spin 1s linear infinite;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>

<body>

    <!-- LUXURY PRELOADER -->
    <div id="preloader-active" style="position: fixed; inset: 0; background: #050505; z-index: 999999; display: flex; align-items: center; justify-content: center; transition: opacity 0.8s ease; opacity: 1;">
        <div style="position: relative; width: 90px; height: 90px; display: flex; align-items: center; justify-content: center;">
            <div class="lux-spinner" style="position: absolute; width: 100%; height: 100%;"></div>
            <?php if (!empty($p['logo'])): ?>
                <img src="../backend/foto/<?php echo htmlspecialchars($p['logo']); ?>" alt="<?= htmlspecialchars($p['name']); ?>" style="width: 45%; height: auto; object-fit: contain; opacity: 0.8;">
            <?php endif; ?>
        </div>
    </div>

    <!-- HEADER GHD BARBERSHOP -->
    <header>
        <div class="header-area header-transparent">
            <div class="main-header header-sticky">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                            <div class="logo">
                                <a href="index.php">
                                    <?php if (!empty($p['logo'])): ?>
                                        <img src="../backend/foto/<?php echo htmlspecialchars($p['logo']); ?>" alt="<?= htmlspecialchars($p['name']); ?>">
                                    <?php else: ?>
                                        <img src="assets/img/logo/logo.png" alt="<?= htmlspecialchars($p['name']); ?>">
                                    <?php endif; ?>
                                    <h3 class="d-none d-sm-block"><?= htmlspecialchars($p['name']); ?></h3>
                                </a>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 text-right">
                            <a href="langganan.php" style="color: var(--lux-gold); font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; text-decoration: none;">
                                <i class="fas fa-arrow-left mr-2"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="payment-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-7 col-md-9">
                    
                    <div class="lux-payment-card shadow-lg">
                        
                        <!-- TAMPILAN UTAMA PEMBAYARAN -->
                        <div id="payment-view">
                            <span style="color: var(--lux-gold); font-size: 11px; letter-spacing: 3px; text-transform: uppercase; display: block; margin-bottom: 8px;">Scan QRIS Pembayaran</span>
                            <h3 style="font-size: 28px; margin-bottom: 10px;"><?= htmlspecialchars($row['package_name']); ?></h3>

                            <!-- TIMER 30 DETIK -->
                            <div class="timer-badge mt-3">
                                <i class="fas fa-clock mr-1"></i> Batas Waktu Pembayaran: <span id="countdown">30</span> Detik
                            </div>

                            <div class="qris-box">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=GHD-SUBSCRIPTION-<?= $row['id_sub']; ?>" alt="QRIS Code" style="width: 100%;">
                            </div>

                            <h4 style="color: var(--lux-gold); font-size: 26px; margin: 15px 0 5px 0;">Rp <?= number_format($row['price'], 0, ',', '.'); ?></h4>
                            <p class="text-muted" style="font-size: 12px; margin-bottom: 25px;">Durasi Paket: <b><?= htmlspecialchars($row['duration']); ?></b></p>

                            <form action="" method="POST">
                                <button type="submit" name="cancel_payment" class="btn-lux-cancel" onclick="return confirm('Yakin ingin membatalkan transaksi langganan ini?')">Batalkan</button>
                            </form>
                            <a href="langganan.php" class="btn-lux-back">Kembali</a>
                        </div>

                        <!-- TAMPILAN KETIKA WAKTU HABIS (30 DETIK) -->
                        <div id="expired-view">
                            <div style="font-size: 50px; color: #dc3545; margin-bottom: 15px;"><i class="fas fa-hourglass-end"></i></div>
                            <h3>Waktu Pembayaran Habis!</h3>
                            <p class="text-muted mt-2" style="font-size: 13px;">
                                Batas waktu 30 detik telah berakhir dan transaksi ini dibatalkan secara otomatis oleh sistem GHD Barbershop.
                            </p>
                            <hr style="border-color: rgba(255,255,255,0.08); margin: 25px 0;">
                            
                            <a href="langganan.php" class="btn-lux-back" style="background: var(--lux-gold) !important; color: var(--lux-black) !important; border: none;">Pilih Paket Ulang</a>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- JS Files GHD Standard -->
    <script src="assets/js/vendor/modernizr-3.5.0.min.js"></script>
    <script src="assets/js/vendor/jquery-1.12.4.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.slicknav.min.js"></script>
    <script src="assets/js/main.js"></script>
    
    <!-- SCRIPT TIMER 30 DETIK & PRELOADER -->
    <script>
        window.addEventListener('load', function() {
            const preloader = document.getElementById('preloader-active');
            if (preloader) {
                preloader.style.opacity = '0';
                setTimeout(() => preloader.style.display = 'none', 800);
            }
        });

        let timeLeft = 30;
        const countdownEl = document.getElementById('countdown');
        const paymentView = document.getElementById('payment-view');
        const expiredView = document.getElementById('expired-view');

        const timer = setInterval(function() {
            if (timeLeft <= 0) {
                clearInterval(timer);
                paymentView.style.display = 'none';
                expiredView.style.display = 'block';
            } else {
                countdownEl.textContent = timeLeft;
                timeLeft -= 1;
            }
        }, 1000);
    </script>
</body>
</html>