<?php
include "../backend/connection.php";

/* =========================
   HELPER
========================= */
function e($value)
{
    return htmlspecialchars(isset($value) ? $value : '', ENT_QUOTES, 'UTF-8');
}

/* =========================
   PROFILE (Untuk Header & Copyright)
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
?>

<!doctype html>
<html class="no-js" lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Paket Langganan | <?php echo e(isset($p['name']) ? $p['name'] : 'Barbershop'); ?></title>
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

        /* HEADER SAMA */
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

        /* HERO SUBSCRIPTION */
        .sub-hero { padding: 200px 0 60px 0; background: linear-gradient(180deg, var(--lux-black) 0%, var(--lux-dark) 100%); text-align: center; }
        .sub-hero span { color: var(--lux-gold); font-size: 12px; font-weight: 500; letter-spacing: 4px; text-transform: uppercase; display: block; margin-bottom: 15px; }
        .sub-hero h2 { font-size: 48px; font-weight: 600; margin: 0; }

        /* PRICING CARDS */
        .lux-pricing-card {
            background: var(--lux-surface);
            border: 1px solid rgba(197, 160, 89, 0.2);
            border-radius: 6px;
            padding: 40px 30px;
            text-align: center;
            transition: var(--transition-smooth);
            height: 100%;
            position: relative;
        }
        .lux-pricing-card:hover {
            border-color: var(--lux-gold);
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(197, 160, 89, 0.15);
        }
        .lux-pricing-card.featured {
            background: linear-gradient(180deg, #16140f 0%, var(--lux-surface) 100%);
            border: 2px solid var(--lux-gold);
        }
        .badge-popular {
            position: absolute; top: -12px; left: 50%; transform: translateX(-50%);
            background: var(--lux-gold); color: var(--lux-black); font-size: 10px;
            font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
            padding: 4px 15px; border-radius: 20px;
        }
        .price-tag {
            font-family: var(--font-head);
            font-size: 38px;
            color: var(--lux-gold);
            margin: 20px 0;
        }
        .features-list {
            list-style: none; padding: 0; margin: 25px 0; text-align: left; font-size: 14px;
        }
        .features-list li {
            padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.05); color: #ccc;
        }
        .features-list li i { color: var(--lux-gold); margin-right: 8px; }

        .btn-sub {
            background: transparent; color: var(--lux-gold); border: 1px solid var(--lux-gold);
            padding: 12px 25px; font-weight: 700; font-size: 12px; letter-spacing: 1.5px;
            text-transform: uppercase; width: 100%; border-radius: 2px; transition: var(--transition-smooth);
            display: inline-block; text-decoration: none; text-align: center;
        }
        .btn-sub:hover, .lux-pricing-card.featured .btn-sub {
            background: var(--lux-gold); color: var(--lux-black);
        }

        .lux-spinner {
            width: 80px; height: 80px; border: 2px solid rgba(197, 160, 89, 0.1);
            border-top-color: var(--lux-gold); border-radius: 50%; animation: spin 1s linear infinite;
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
                                    <a href="langganan.php" class="btn btn-lux-outline active">Langganan</a>
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
        <div class="sub-hero">
            <div class="container">
                <span>Exclusive Membership</span>
                <h2>Paket Langganan VIP</h2>
                <p style="color: var(--lux-text); max-width: 600px; margin: 15px auto 0 auto; font-size: 14px;">
                    Nikmati potongan rambut sepuasnya dan layanan perawatan eksklusif setiap bulan tanpa antre dengan bergabung menjadi member VIP kami.
                </p>
            </div>
        </div>

        <!-- Pricing Packages Section -->
        <div class="container pb-130" style="padding-top: 40px;">
            <div class="row">
                
                <!-- Paket 1: Basic Gentleman -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="lux-pricing-card">
                        <h3 style="font-size: 24px; margin-bottom: 10px;">Gentleman Pass</h3>
                        <p style="font-size: 13px; color: var(--lux-text);">Cocok untuk perawatan rutin bulanan perorangan.</p>
                        <div class="price-tag">Rp 150.000 <span style="font-size: 13px; color: var(--lux-text); font-family: var(--font-body);">/ bulan</span></div>
                        <ul class="features-list">
                            <li><i class="fas fa-check"></i> 2x Potong Rambut / Bulan</li>
                            <li><i class="fas fa-check"></i> Free Hair Wash & Tonic</li>
                            <li><i class="fas fa-check"></i> Prioritas Booking Jadwal</li>
                            <li><i class="fas fa-times" style="color: #555;"></i> Free Pomade Premium</li>
                        </ul>
                        <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $p['phone']); ?>?text=Halo%20Admin,%20saya%20ingin%20berlangganan%20Paket%20Gentleman%20Pass." target="_blank" class="btn-sub">Pilih Paket</a>
                    </div>
                </div>

                <!-- Paket 2: VIP Executive (Featured) -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="lux-pricing-card featured">
                        <div class="badge-popular">Terlaris</div>
                        <h3 style="font-size: 24px; margin-bottom: 10px;">Executive VIP</h3>
                        <p style="font-size: 13px; color: var(--lux-text);">Solusi lengkap tampil rapi setiap pekan.</p>
                        <div class="price-tag">Rp 300.000 <span style="font-size: 13px; color: var(--lux-text); font-family: var(--font-body);">/ bulan</span></div>
                        <ul class="features-list">
                            <li><i class="fas fa-check"></i> Bebas Potong Rambut Sebulan Penuh</li>
                            <li><i class="fas fa-check"></i> Free Wash, Tonic & Hot Towel</li>
                            <li><i class="fas fa-check"></i> Free 1 Pcs Pomade Premium/bln</li>
                            <li><i class="fas fa-check"></i> Bebas Pilih Kursi & Barber Favorit</li>
                        </ul>
                        <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $p['phone']); ?>?text=Halo%20Admin,%20saya%20ingin%20berlangganan%20Paket%20Executive%20VIP." target="_blank" class="btn-sub">Pilih Paket</a>
                    </div>
                </div>

                <!-- Paket 3: Ultimate Grooming -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="lux-pricing-card">
                        <h3 style="font-size: 24px; margin-bottom: 10px;">Ultimate Grooming</h3>
                        <p style="font-size: 13px; color: var(--lux-text);">Paket Sultan untuk perawatan total tanpa batas.</p>
                        <div class="price-tag">Rp 500.000 <span style="font-size: 13px; color: var(--lux-text); font-family: var(--font-body);">/ bulan</span></div>
                        <ul class="features-list">
                            <li><i class="fas fa-check"></i> Unlimited Haircut & Shaving</li>
                            <li><i class="fas fa-check"></i> All Treatment (Hair Spa & Coloring)</li>
                            <li><i class="fas fa-check"></i> Free 2 Pcs Pomade Premium/bln</li>
                            <li><i class="fas fa-check"></i> VIP Lounge Access & Fast Track</li>
                        </ul>
                        <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $p['phone']); ?>?text=Halo%20Admin,%20saya%20ingin%20berlangganan%20Paket%20Ultimate%20Grooming." target="_blank" class="btn-sub">Pilih Paket</a>
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