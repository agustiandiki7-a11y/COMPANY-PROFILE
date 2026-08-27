<?php
include "../backend/connection.php";


// Ambil tanggal yang dipilih user (jika belum memilih, pakai tanggal hari ini)
$tanggal_pilih = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');

// Cek kursi yang sudah dipesan dan statusnya masih Pending atau Disetujui
$query_kursi = mysqli_query($koneksi, "
    SELECT chair_number 
    FROM bookings 
    WHERE booking_date = '$tanggal_pilih' 
    AND status IN ('Disetujui', 'Pending')
");

$kursi_terisi = [];
while ($row = mysqli_fetch_assoc($query_kursi)) {
    $kursi_terisi[] = $row['chair_number'];
}


/* =========================
   HELPER (Biar aman di semua versi PHP)
========================= */
function e($value)
{
    return htmlspecialchars(isset($value) ? $value : '', ENT_QUOTES, 'UTF-8');
}

/* =========================
   PROFILE
========================= */
$query_profile = mysqli_query(
    $koneksi,
    "SELECT * FROM profile LIMIT 1"
);

// Pengecekan aman jika tabel kosong
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
   SERVICES
========================= */
$query_services = mysqli_query(
    $koneksi,
    "SELECT * FROM services ORDER BY id_service DESC"
);

/* =========================
   BARBERS
========================= */
$query_barbers = mysqli_query(
    $koneksi,
    "SELECT * FROM barbers ORDER BY id_barber DESC"
);

/* =========================
   PRICING
========================= */
$query_pricing = mysqli_query(
    $koneksi,
    "SELECT * FROM pricing ORDER BY id_pricing DESC"
);

/* =========================
   PORTFOLIO
========================= */
$query_portfolio = mysqli_query(
    $koneksi,
    "SELECT * FROM portfolio ORDER BY id_portfolio DESC"
);

/* =========================
   TESTIMONIALS
========================= */
$query_testimonials = mysqli_query(
    $koneksi,
    "SELECT * FROM testimonials ORDER BY id_testimonial DESC"
);
?>

<!doctype html>
<html class="no-js" lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title><?php echo e(isset($p['name']) ? $p['name'] : 'Barbershop'); ?> | Premium Grooming</title>

    <meta name="description" content="<?php echo e(isset($p['description']) ? $p['description'] : ''); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- DIKEMBALIKAN KE KODE LOGO AWAL ASLUNYA -->
    <link rel="icon" type="image/x-icon" href="../backend/foto/logo.ico">

    <!-- PREMIUM FONTS: Playfair Display & Montserrat -->
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
            --primary-gold: #c5a059;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background: var(--lux-black) !important;
            color: var(--lux-text) !important;
            font-family: var(--font-body) !important;
            overflow-x: hidden;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: var(--font-head) !important;
            color: var(--lux-white) !important;
            letter-spacing: 0.5px;
        }

        main,
        section,
        footer {
            overflow: hidden;
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--lux-black);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--lux-gold);
        }

        /* =========================================================
            HEADER & NAVIGATION FIXES
        ========================================================= */
        .header-area {
            position: absolute !important;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            z-index: 999;
        }

        .header-area .main-header {
            min-height: 90px;
            padding: 15px 50px !important;
            background: rgba(5, 5, 5, 0.5) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            transition: var(--transition-smooth);
        }

        .header-sticky.sticky-bar {
            background: rgba(5, 5, 5, 0.95) !important;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.5);
            padding: 10px 50px !important;
        }

        .header-area .logo a {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .header-area .logo a img {
            max-height: 60px;
            width: auto;
            object-fit: contain;
        }

        .header-area .logo a h3 {
            color: var(--lux-white) !important;
            font-family: var(--font-head);
            font-size: 22px !important;
            font-weight: 600;
            letter-spacing: 1px;
            margin: 0;
            white-space: nowrap;
        }

        .header-area .main-menu ul {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .header-area .main-menu ul li a {
            color: #d1d1d1 !important;
            font-size: 13px !important;
            font-weight: 400;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 25px 15px !important;
            transition: var(--transition-smooth);
        }

        .header-area .main-menu ul li a:hover,
        .header-area .main-menu ul li.active>a {
            color: var(--lux-gold) !important;
        }

        /* =========================================================
            TOMBOL HEADER (Booking & Langganan)
        ========================================================= */
        .header-right-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .btn-lux-outline {
            background: transparent !important;
            color: var(--lux-gold) !important;
            border: 1px solid var(--lux-gold) !important;
            padding: 10px 20px !important;
            font-size: 11px !important;
            font-weight: 600 !important;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            border-radius: 0;
            transition: var(--transition-smooth);
        }

        .btn-lux-outline:hover {
            background: var(--lux-gold) !important;
            color: var(--lux-black) !important;
            box-shadow: 0 0 15px rgba(197, 160, 89, 0.4);
        }

        .btn-lux-solid {
            background: var(--lux-gold) !important;
            color: var(--lux-black) !important;
            border: 1px solid var(--lux-gold) !important;
            padding: 10px 20px !important;
            font-size: 11px !important;
            font-weight: 600 !important;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            border-radius: 0;
            transition: var(--transition-smooth);
        }

        .btn-lux-solid:hover {
            background: transparent !important;
            color: var(--lux-gold) !important;
            box-shadow: 0 0 15px rgba(197, 160, 89, 0.4);
        }

        /* =========================================================
            MOBILE MENU (SLICKNAV)
        ========================================================= */
        .mobile_menu {
            width: 100%;
        }

        .slicknav_menu {
            background: transparent !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .slicknav_btn {
            background-color: transparent !important;
            margin: 0 !important;
            padding: 5px 0 !important;
            cursor: pointer;
        }

        .slicknav_icon-bar {
            background-color: var(--lux-gold) !important;
            box-shadow: none !important;
            width: 28px !important;
            height: 3px !important;
            margin: 5px 0 !important;
            display: block;
            border-radius: 2px;
        }

        .slicknav_nav {
            background: var(--lux-dark) !important;
            border: 1px solid rgba(197, 160, 89, 0.2) !important;
            border-radius: 4px;
            margin-top: 15px !important;
            text-align: left;
            position: absolute;
            width: 100%;
            right: 0;
            z-index: 9999;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.8);
        }

        .slicknav_nav a {
            color: var(--lux-white) !important;
            font-family: var(--font-body) !important;
            font-size: 13px !important;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 15px 20px !important;
            margin: 0 !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            transition: var(--transition-smooth);
        }

        .slicknav_nav a:hover {
            background: rgba(197, 160, 89, 0.1) !important;
            color: var(--lux-gold) !important;
        }

        .slicknav_nav .slicknav_row:hover {
            background: transparent !important;
        }

        @media (max-width: 991px) {
            .header-area .main-header {
                padding: 15px 20px !important;
            }

            .header-sticky.sticky-bar {
                padding: 10px 20px !important;
            }

            .header-area .logo a h3 {
                font-size: 18px !important;
            }
        }

        /* =========================================================
            HERO & OTHERS
        ========================================================= */
        .slider-area {
            background-color: var(--lux-black) !important;
        }

        .slider-area::after {
            background: linear-gradient(90deg, rgba(5, 5, 5, 0.95) 0%, rgba(5, 5, 5, 0.7) 40%, rgba(5, 5, 5, 0.2) 100%),
                linear-gradient(0deg, rgba(5, 5, 5, 0.8) 0%, rgba(5, 5, 5, 0) 30%) !important;
        }

        .hero__caption span {
            color: var(--lux-gold) !important;
            font-size: 12px !important;
            font-weight: 500 !important;
            letter-spacing: 4px !important;
            text-transform: uppercase;
            font-family: var(--font-body);
        }

        .hero__caption h1 {
            margin-top: 25px;
            color: var(--lux-white) !important;
            font-size: 72px !important;
            line-height: 1.1 !important;
            font-weight: 600 !important;
            text-transform: none;
            text-shadow: 0 10px 30px rgba(0, 0, 0, 0.8);
        }

        .section-tittle {
            margin-bottom: 70px;
        }

        .section-tittle span {
            color: var(--lux-gold) !important;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 4px;
            text-transform: uppercase;
            font-family: var(--font-body);
            display: block;
            margin-bottom: 15px;
        }

        .section-tittle h2 {
            font-size: 46px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .section-tittle h2::after {
            background: var(--lux-gold) !important;
            height: 1px !important;
            width: 60px !important;
            margin-top: 25px;
        }

        .about-area {
            background: var(--lux-black) !important;
            border-top: 1px solid rgba(255, 255, 255, 0.02);
            padding: 130px 0 !important;
        }

        .about-img img {
            border-radius: 4px;
            filter: grayscale(80%) contrast(1.1);
            transition: var(--transition-smooth);
        }

        .about-img:hover img {
            filter: grayscale(0%) contrast(1);
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
        }

        .about-caption p {
            color: var(--lux-text) !important;
            font-size: 15px;
            font-weight: 300;
            line-height: 1.9;
        }

        .about-caption strong {
            color: var(--lux-white);
            font-weight: 500;
        }

        .opening-box {
            border-left: 2px solid var(--lux-gold);
            padding-left: 20px;
            margin-top: 30px;
        }

        .service-area {
            background: var(--lux-dark) !important;
            padding: 130px 0 !important;
        }

        .lux-service-card {
            background: var(--lux-surface);
            padding: 40px 30px;
            border: 1px solid rgba(255, 255, 255, 0.03);
            transition: var(--transition-smooth);
            height: 100%;
            position: relative;
        }

        .lux-service-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: var(--lux-gold);
            transform: scaleX(0);
            transform-origin: left;
            transition: var(--transition-smooth);
        }

        .lux-service-card:hover {
            transform: translateY(-10px);
            background: #151515;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
            border-color: var(--lux-gold-dim);
        }

        .lux-service-card:hover::before {
            transform: scaleX(1);
        }

        .lux-service-img {
            height: 200px;
            width: 100%;
            margin-bottom: 25px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #000;
        }

        .lux-service-img img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
            filter: grayscale(100%);
            transition: var(--transition-smooth);
        }

        .lux-service-card:hover .lux-service-img img {
            filter: grayscale(0%);
            transform: scale(1.05);
        }

        .lux-service-title {
            font-size: 22px;
            margin-bottom: 15px;
        }

        .lux-service-desc {
            font-size: 14px;
            color: var(--lux-text);
            font-weight: 300;
            line-height: 1.8;
            margin-bottom: 20px;
        }

        .lux-service-price {
            color: var(--lux-gold);
            font-size: 20px;
            font-weight: 600;
            font-family: var(--font-head);
            display: block;
        }

        .team-area {
            background: var(--lux-black) !important;
            padding: 130px 0 !important;
        }

        .single-team {
            text-align: center;
        }

        .team-img {
            overflow: hidden;
            position: relative;
            background: #000;
        }

        .team-img img {
            width: 100%;
            height: 420px !important;
            object-fit: cover;
            filter: grayscale(100%) brightness(0.8);
            transition: var(--transition-smooth);
        }

        .single-team:hover .team-img img {
            filter: grayscale(0%) brightness(1);
            transform: scale(1.03);
        }

        .team-caption {
            padding-top: 25px;
        }

        .team-caption h3 {
            font-size: 24px;
            margin-bottom: 5px;
            text-transform: capitalize;
        }

        .team-caption span {
            font-size: 11px;
            color: var(--lux-gold);
            letter-spacing: 2px;
            text-transform: uppercase;
            font-family: var(--font-body);
        }

        .best-pricing {
            background: var(--lux-dark) !important;
            padding: 130px 0 !important;
        }

        .lux-pricing-item {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            padding: 20px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            transition: var(--transition-smooth);
        }

        .lux-pricing-item:hover {
            border-bottom-color: var(--lux-gold);
            padding-left: 10px;
        }

        .lux-pricing-left {
            max-width: 70%;
        }

        .lux-pricing-title {
            font-family: var(--font-head);
            font-size: 20px;
            color: var(--lux-white);
            margin-bottom: 5px;
            display: block;
        }

        .lux-pricing-desc {
            font-size: 13px;
            color: var(--lux-text);
            font-weight: 300;
        }

        .lux-pricing-price {
            font-family: var(--font-head);
            font-size: 22px;
            color: var(--lux-gold);
            font-weight: 600;
            white-space: nowrap;
        }

        .ghb-portfolio-section {
            background: var(--lux-black) !important;
            padding: 130px 0 !important;
        }

        .lux-port-card {
            position: relative;
            overflow: hidden;
            height: 400px;
            cursor: pointer;
            background: #000;
        }

        .lux-port-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: grayscale(100%) brightness(0.7);
            transition: var(--transition-smooth);
        }

        .lux-port-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.9), transparent);
            opacity: 0;
            transition: var(--transition-smooth);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 30px;
        }

        .lux-port-card:hover img {
            filter: grayscale(0%) brightness(1);
            transform: scale(1.05);
        }

        .lux-port-card:hover .lux-port-overlay {
            opacity: 1;
        }

        .lux-port-overlay h3 {
            font-size: 22px;
            margin-bottom: 5px;
            transform: translateY(15px);
            transition: var(--transition-smooth);
            color: #fff;
        }

        .lux-port-overlay p {
            color: var(--lux-gold);
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin: 0;
            transform: translateY(15px);
            transition: var(--transition-smooth);
            transition-delay: 0.1s;
        }

        .lux-port-card:hover .lux-port-overlay h3,
        .lux-port-card:hover .lux-port-overlay p {
            transform: translateY(0);
        }

        #testimonials {
            background: var(--lux-dark) !important;
            padding: 130px 0 !important;
        }

        .lux-testimonial {
            text-align: center;
            padding: 40px;
            background: var(--lux-surface);
            border: 1px solid rgba(255, 255, 255, 0.02);
            margin: 15px;
            transition: var(--transition-smooth);
        }

        .lux-testimonial:hover {
            border-color: var(--lux-gold-dim);
            transform: translateY(-5px);
        }

        .lux-quote-icon {
            font-size: 30px;
            color: var(--lux-gold);
            margin-bottom: 25px;
            opacity: 0.5;
        }

        .lux-testimonial p {
            font-family: var(--font-head);
            font-style: italic;
            font-size: 18px;
            color: #ccc;
            line-height: 1.8;
            margin-bottom: 25px;
            min-height: 100px;
        }

        .lux-testimonial-name {
            font-size: 14px;
            color: var(--lux-white);
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 500;
            display: block;
        }

        .lux-testimonial-stars {
            color: var(--lux-gold);
            font-size: 12px;
            margin-top: 10px;
        }

        #contact {
            background: var(--lux-black) !important;
            padding: 100px 0 !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .contact-box {
            background: transparent !important;
            padding: 0 !important;
            margin-bottom: 30px;
            border: none !important;
            display: flex;
            gap: 20px;
        }

        .contact-icon i {
            color: var(--lux-gold) !important;
            font-size: 24px;
            margin-top: 5px;
        }

        .contact-content h4 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .contact-content p {
            color: var(--lux-text);
            font-size: 14px;
        }

        .contact-link {
            color: var(--lux-gold);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid var(--lux-gold);
            padding-bottom: 2px;
        }

        .map-wrapper iframe {
            filter: grayscale(100%) invert(90%) contrast(1.2);
            border-radius: 4px;
        }

        .lux-spinner {
            width: 80px;
            height: 80px;
            border: 2px solid rgba(197, 160, 89, 0.1);
            border-top-color: var(--lux-gold);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @media (max-width: 575px) {
            .hero__caption h1 {
                font-size: 36px !important;
            }

            .section-tittle h2 {
                font-size: 28px;
            }

            .about-area,
            .service-area,
            .team-area,
            .best-pricing,
            .ghb-portfolio-section,
            #testimonials {
                padding: 80px 0 !important;
            }
        }

        /* =========================================================
            FOOTER SOCIAL MEDIA
        ========================================================= */
        .footer-social {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }

        .footer-social a {
            color: var(--primary-gold) !important;
            background: transparent !important;
            border: 1px solid rgba(209, 159, 104, 0.4) !important;
            width: 45px !important;
            height: 45px !important;
            display: flex !important;
            align-items: center;
            justify-content: center;
            border-radius: 50% !important;
            font-size: 18px;
            transition: all 0.3s ease !important;
            text-decoration: none;
        }

        .footer-social a:hover {
            background: var(--primary-gold) !important;
            color: #111 !important;
            border-color: var(--primary-gold) !important;
            transform: translateY(-4px);
            box-shadow: 0 5px 15px rgba(209, 159, 104, 0.3);
        }

        /* Menandai menu yang sedang aktif agar warnanya berubah jadi emas */
        .header-area .main-menu ul li.active>a {
            color: #c5a059 !important;
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

    <script>
        window.addEventListener('load', function() {
            const preloader = document.getElementById('preloader-active');
            if (preloader) {
                preloader.style.opacity = '0';
                setTimeout(() => preloader.style.display = 'none', 800);
            }
        });
        setTimeout(function() {
            const preloader = document.getElementById('preloader-active');
            if (preloader && preloader.style.display !== 'none') {
                preloader.style.opacity = '0';
                setTimeout(() => preloader.style.display = 'none', 800);
            }
        }, 3000);
    </script>

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

                                <!-- Menu Desktop dengan Target Hash (#) agar Scroll Spy Akurat -->
                                <div class="main-menu f-right d-none d-lg-block">
                                    <nav>
                                        <ul id="navigation">
                                            <li class="active"><a href="#home">Home</a></li>
                                            <li><a href="#about">About</a></li>
                                            <li><a href="#services">Services</a></li>
                                            <li><a href="#barbers">Barbers</a></li>
                                            <li><a href="#pricing">Pricing</a></li>
                                            <li><a href="#portfolio">Portfolio</a></li>
                                            <li><a href="#contact">Contact</a></li>
                                        </ul>
                                    </nav>
                                </div>

                                <!-- TOMBOL LANGGANAN & BOOKING BERDAMPINGAN -->
                                <div class="header-right-actions f-right d-none d-lg-flex ml-30">
                                    <a href="langganan.php" class="btn btn-lux-outline">Langganan</a>
                                    <a href="payment.php" class="btn btn-lux-solid">Booking</a>
                                </div>

                                <!-- WADAH MENU MOBILE -->
                                <div class="mobile_menu d-block d-lg-none text-right"></div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </header>

    <main id="home">
        
        <!-- HERO -->
        <div class="slider-area position-relative fix">
            <div class="slider-active">
                <!-- SLIDE 1 -->
                <div class="single-slider slider-height d-flex align-items-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-9 col-lg-10 col-md-11">
                                <div class="hero__caption">
                                    <span data-animation="fadeInUp" data-delay="0.2s">Welcome to <?php echo e(isset($p['name']) ? $p['name'] : ''); ?></span>
                                    <h1 data-animation="fadeInUp" data-delay="0.5s">Experience the Art of Classic Grooming.</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- SLIDE 2 -->
                <div class="single-slider slider-height d-flex align-items-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-9 col-lg-10 col-md-11">
                                <div class="hero__caption">
                                    <span data-animation="fadeInUp" data-delay="0.2s">Premium Barbershop</span>
                                    <h1 data-animation="fadeInUp" data-delay="0.5s">Your Style,<br>Your Confidence.</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ABOUT -->
        <section id="about" class="about-area">
            <div class="container">
                <div class="row align-items-center justify-content-between">
                    <div class="col-lg-5 col-md-12 mb-5">
                        <div class="about-img">
                            <img src="foto/1787415543_6a89cbf7711d9.jpg" alt="About <?php echo e(isset($p['name']) ? $p['name'] : ''); ?>" class="w-100">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="about-caption">
                            <div class="section-tittle mb-4">
                                <span>The Story</span>
                                <h2>About <?php echo e(isset($p['name']) ? $p['name'] : ''); ?></h2>
                            </div>
                            <p class="mb-4">
                                <?php echo nl2br(e(isset($p['description']) ? $p['description'] : '')); ?>
                            </p>
                            <p>
                                Kami memberikan pelayanan barber profesional dengan standar tinggi, suasana eksklusif, dan dedikasi penuh pada detail untuk memastikan Anda selalu tampil sempurna.
                            </p>

                            <div class="opening-box">
                                <strong style="display: block; font-size: 12px; letter-spacing: 2px; text-transform: uppercase; color: var(--lux-gold); margin-bottom: 5px;">Opening Hours</strong>
                                <span style="font-size: 18px; color: var(--lux-white);"><?php echo e(isset($p['opening_hours']) ? $p['opening_hours'] : ''); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SERVICES -->
        <section id="services" class="service-area">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-7 col-lg-8">
                        <div class="section-tittle text-center">
                            <span>Our Expertise</span>
                            <h2>Premium Services</h2>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <?php if ($query_services && mysqli_num_rows($query_services) > 0): ?>
                        <?php while ($service = mysqli_fetch_assoc($query_services)): ?>
                            <div class="col-xl-4 col-lg-4 col-md-6 mb-4">
                                <div class="lux-service-card">
                                    <?php
                                    $service_image = !empty($service['image']) ? "../backend/foto/" . $service['image'] : "assets/img/gallery/service1.png";
                                    ?>
                                    <div class="lux-service-img">
                                        <img src="<?php echo e($service_image); ?>" alt="<?php echo e(isset($service['name']) ? $service['name'] : ''); ?>">
                                    </div>
                                    <h3 class="lux-service-title"><?php echo e(isset($service['name']) ? $service['name'] : ''); ?></h3>
                                    <p class="lux-service-desc"><?php echo nl2br(e(isset($service['description']) ? $service['description'] : '')); ?></p>

                                    <div class="d-flex justify-content-between align-items-center mt-4">
                                        <span class="lux-service-price">Rp <?php echo number_format((float)$service['price'], 0, ',', '.'); ?></span>
                                        <?php if (!empty($service['duration'])): ?>
                                            <span style="font-size: 12px; color: var(--lux-text);"><i class="far fa-clock" style="color: var(--lux-gold);"></i> <?php echo e($service['duration']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12 text-center">
                            <p>Belum ada data services.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- BARBERS -->
        <section id="barbers" class="team-area">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-7 col-lg-8">
                        <div class="section-tittle text-center">
                            <span>The Masters</span>
                            <h2>Our Expert Barbers</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <?php if ($query_barbers && mysqli_num_rows($query_barbers) > 0): ?>
                        <?php while ($barber = mysqli_fetch_assoc($query_barbers)): ?>
                            <div class="col-xl-4 col-lg-4 col-md-6 mb-4">
                                <div class="single-team">
                                    <?php
                                    $barber_image = !empty($barber['image']) ? "../backend/foto/" . $barber['image'] : "assets/img/gallery/team1.png";
                                    ?>
                                    <div class="team-img">
                                        <img src="<?php echo e($barber_image); ?>" alt="<?php echo e(isset($barber['name']) ? $barber['name'] : ''); ?>">
                                    </div>
                                    <div class="team-caption">
                                        <h3><?php echo e(isset($barber['name']) ? $barber['name'] : ''); ?></h3>
                                        <?php if (!empty($barber['specialty'])): ?>
                                            <span><?php echo e($barber['specialty']); ?></span>
                                        <?php else: ?>
                                            <span>Master Barber</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12 text-center">
                            <p>Belum ada data barber.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- PRICING -->
        <section id="pricing" class="best-pricing">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-7 col-lg-8">
                        <div class="section-tittle text-center">
                            <span>Investment in Yourself</span>
                            <h2>Pricing Menu</h2>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="row">
                            <?php if ($query_pricing && mysqli_num_rows($query_pricing) > 0): ?>
                                <?php while ($pricing = mysqli_fetch_assoc($query_pricing)): ?>
                                    <div class="col-lg-6 px-4">
                                        <div class="lux-pricing-item">
                                            <div class="lux-pricing-left">
                                                <span class="lux-pricing-title"><?php echo e(isset($pricing['name']) ? $pricing['name'] : ''); ?></span>
                                                <?php if (!empty($pricing['description'])): ?>
                                                    <span class="lux-pricing-desc"><?php echo e($pricing['description']); ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="lux-pricing-price">
                                                Rp <?php echo number_format((float)$pricing['price'], 0, ',', '.'); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="col-12 text-center">
                                    <p>Belum ada data pricing.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- PORTFOLIO -->
        <section id="portfolio" class="ghb-portfolio-section">
            <div class="container-fluid px-0">
                <div class="row justify-content-center mb-5">
                    <div class="col-xl-7 col-lg-8 text-center">
                        <div class="section-tittle text-center mb-0">
                            <span>Showcase</span>
                            <h2>Our Latest Work</h2>
                        </div>
                    </div>
                </div>

                <div class="row no-gutters">
                    <?php if ($query_portfolio && mysqli_num_rows($query_portfolio) > 0): ?>
                        <?php while ($portfolio = mysqli_fetch_assoc($query_portfolio)): ?>
                            <?php
                            $portfolio_image = !empty($portfolio['image']) ? "../backend/foto/" . $portfolio['image'] : "assets/img/gallery/gallery1.png";
                            ?>
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                                <a href="<?php echo e($portfolio_image); ?>" class="lux-port-card d-block" target="_blank">
                                    <img src="<?php echo e($portfolio_image); ?>" alt="<?php echo e(isset($portfolio['title']) ? $portfolio['title'] : ''); ?>">
                                    <div class="lux-port-overlay">
                                        <h3><?php echo e(isset($portfolio['title']) ? $portfolio['title'] : ''); ?></h3>
                                        <?php if (!empty($portfolio['category'])): ?>
                                            <p><?php echo e($portfolio['category']); ?></p>
                                        <?php else: ?>
                                            <p>GHB Style</p>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12 text-center">
                            <p>Belum ada portfolio.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- TESTIMONIALS -->
        <section id="testimonials">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-7 col-lg-8">
                        <div class="section-tittle text-center">
                            <span>Testimonials</span>
                            <h2>What Gentlemen Say</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <?php if ($query_testimonials && mysqli_num_rows($query_testimonials) > 0): ?>
                        <?php while ($testimonial = mysqli_fetch_assoc($query_testimonials)): ?>
                            <div class="col-lg-4 col-md-6">
                                <div class="lux-testimonial">
                                    <div class="lux-quote-icon"><i class="fas fa-quote-right"></i></div>
                                    <p>"<?php echo nl2br(e(isset($testimonial['message']) ? $testimonial['message'] : '')); ?>"</p>
                                    <span class="lux-testimonial-name"><?php echo e(isset($testimonial['name']) ? $testimonial['name'] : ''); ?></span>
                                    <div class="lux-testimonial-stars">
                                        <?php
                                        $rating = isset($testimonial['rating']) ? (int)$testimonial['rating'] : 5;
                                        for ($i = 1; $i <= 5; $i++) {
                                            echo $i <= $rating ? '<i class="fas fa-star"></i> ' : '<i class="far fa-star"></i> ';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12 text-center">
                            <p>Belum ada testimonial.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- CONTACT -->
        <section id="contact">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-5 mb-5 mb-lg-0">
                        <div class="section-tittle mb-5">
                            <span>Get in Touch</span>
                            <h2 style="font-size: 36px;">Visit Our Lounge</h2>
                        </div>

                        <!-- ADDRESS -->
                        <div class="contact-box">
                            <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                            <div class="contact-content">
                                <h4>Address</h4>
                                <p><?php echo nl2br(e(isset($p['address']) ? $p['address'] : '')); ?></p>
                            </div>
                        </div>

                        <!-- PHONE -->
                        <div class="contact-box">
                            <div class="contact-icon"><i class="fas fa-phone"></i></div>
                            <div class="contact-content">
                                <h4>Reservation</h4>
                                <p><?php echo e(isset($p['phone']) ? $p['phone'] : ''); ?></p>
                                <?php if (!empty($p['phone'])): ?>
                                    <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $p['phone']); ?>" target="_blank" class="contact-link">Book via WhatsApp</a>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- EMAIL -->
                        <div class="contact-box">
                            <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                            <div class="contact-content">
                                <h4>Email</h4>
                                <p><?php echo e(isset($p['email']) ? $p['email'] : ''); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- GOOGLE MAPS -->
                    <div class="col-lg-7">
                        <div class="map-wrapper shadow-lg">
                            <iframe
                                src="https://www.google.com/maps?q=<?php echo urlencode((isset($p['address']) ? $p['address'] : '') . ', Banjar, Jawa Barat, Indonesia'); ?>&z=15&output=embed"
                                width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- FOOTER -->
    <footer style="background: #080808; border-top: 1px solid rgba(209,159,104,0.15); padding-top: 60px; padding-bottom: 30px;">
        <div class="footer-area">
            <div class="container">
                <div class="row justify-content-between">

                    <!-- BRAND / LOGO -->
                    <div class="col-xl-4 col-lg-5 col-md-6 mb-4">
                        <div class="footer-logo mb-3">
                            <a href="index.php">
                                <?php if (!empty($p['logo'])): ?>
                                    <img src="../backend/foto/<?php echo e($p['logo']); ?>" alt="<?php echo e(isset($p['name']) ? $p['name'] : ''); ?>" style="max-height: 50px; object-fit: contain;">
                                <?php else: ?>
                                    <h3 style="font-family: 'Oswald', sans-serif; color: #d19f68; font-size: 24px; margin: 0; text-transform: uppercase;"><?php echo e(isset($p['name']) ? $p['name'] : ''); ?></h3>
                                <?php endif; ?>
                            </a>
                        </div>
                        <div class="footer-pera">
                            <p style="color: #999; font-size: 14px; line-height: 1.8; margin: 0;"><?php echo e(isset($p['description']) ? $p['description'] : ''); ?></p>
                        </div>
                    </div>

                    <!-- LOKASI & MAPS (DIKLIK OTOMATIS KE TUJUAN) -->
                    <div class="col-xl-4 col-lg-4 col-md-5 mb-4">
                        <div class="footer-tittle">
                            <h4 style="color: #fff; font-family: 'Oswald', sans-serif; font-size: 18px; text-transform: uppercase; margin-bottom: 15px;">Location</h4>

                            <!-- Link Google Maps Rute Otomatis -->
                            <a href="https://www.google.com/maps/dir/?api=1&destination=<?php echo urlencode(isset($p['address']) ? $p['address'] : ''); ?>"
                                target="_blank"
                                title="Klik untuk petunjuk arah ke Google Maps"
                                style="display: flex; gap: 10px; align-items: flex-start; text-decoration: none; color: #bbb; transition: color 0.3s ease;"
                                onmouseover="this.style.color='#d19f68'"
                                onmouseout="this.style.color='#bbb'">
                                <i class="fas fa-map-marker-alt" style="color: #d19f68; font-size: 18px; margin-top: 3px;"></i>
                                <span style="font-size: 14px; line-height: 1.6;">
                                    <?php echo nl2br(e(isset($p['address']) ? $p['address'] : '')); ?>
                                    <br>
                                    <small style="color: #d19f68; text-decoration: underline; font-size: 12px; margin-top: 5px; display: inline-block;">
                                        <i class="fas fa-directions"></i> Petunjuk Arah (Google Maps)
                                    </small>
                                </span>
                            </a>
                        </div>
                    </div>

                    <!-- CONNECT WITH US (IG & WA RAPI & PRESISI) -->
                    <div class="col-xl-3 col-lg-3 col-md-12 mb-4">
                        <div class="footer-tittle">
                            <h4 style="color: #fff; font-family: 'Oswald', sans-serif; font-size: 18px; text-transform: uppercase; margin-bottom: 15px;">Connect With Us</h4>

                            <div style="display: flex; gap: 12px; align-items: center; margin-top: 10px;">
                                <!-- Instagram Button -->
                                <?php if (!empty($p['instagram'])): ?>
                                    <a href="<?php echo e($p['instagram']); ?>"
                                        target="_blank"
                                        aria-label="Instagram"
                                        style="width: 44px; height: 44px; border-radius: 50%; border: 1px solid #d19f68; color: #d19f68; background: transparent; display: flex; align-items: center; justify-content: center; font-size: 18px; text-decoration: none; transition: all 0.3s ease;"
                                        onmouseover="this.style.background='#d19f68'; this.style.color='#111';"
                                        onmouseout="this.style.background='transparent'; this.style.color='#d19f68';">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                <?php endif; ?>

                                <!-- WhatsApp Button -->
                                <?php if (!empty($p['phone'])): ?>
                                    <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $p['phone']); ?>"
                                        target="_blank"
                                        aria-label="WhatsApp"
                                        style="width: 44px; height: 44px; border-radius: 50%; border: 1px solid #d19f68; color: #d19f68; background: transparent; display: flex; align-items: center; justify-content: center; font-size: 18px; text-decoration: none; transition: all 0.3s ease;"
                                        onmouseover="this.style.background='#d19f68'; this.style.color='#111';"
                                        onmouseout="this.style.background='transparent'; this.style.color='#d19f68';">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- COPYRIGHT -->
                <div class="footer-bottom text-center" style="border-top: 1px solid rgba(255,255,255,0.05); padding-top: 25px; margin-top: 20px;">
                    <p class="m-0" style="color: #666; font-size: 13px; letter-spacing: 1px;">
                        &copy; <?php echo date('Y'); ?> <?php echo e(isset($p['name']) ? $p['name'] : ''); ?>. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- BACK TO TOP -->
    <div id="back-top" style="display: none; position: fixed; right: 30px; bottom: 30px; z-index: 999;">
        <a title="Go to Top" href="#" style="background: var(--lux-gold); color: var(--lux-black); width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; font-size: 20px; transition: 0.3s; box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
            <i class="fas fa-level-up-alt"></i>
        </a>
    </div>

    <!-- JS -->
    <script src="./assets/js/vendor/modernizr-3.5.0.min.js"></script>
    <script src="./assets/js/vendor/jquery-1.12.4.min.js"></script>
    <script src="./assets/js/popper.min.js"></script>
    <script src="./assets/js/bootstrap.min.js"></script>
    <script src="./assets/js/jquery.slicknav.min.js"></script>
    <script src="./assets/js/owl.carousel.min.js"></script>
    <script src="./assets/js/slick.min.js"></script>
    <script src="./assets/js/wow.min.js"></script>
    <script src="./assets/js/animated.headline.js"></script>
    <script src="./assets/js/jquery.magnific-popup.css"></script>
    <script src="./assets/js/jquery.magnific-popup.js"></script>
    <script src="./assets/js/gijgo.min.js"></script>
    <script src="./assets/js/jquery.nice-select.min.js"></script>
    <script src="./assets/js/jquery.sticky.js"></script>
    <script src="./assets/js/jquery.counterup.min.js"></script>
    <script src="./assets/js/waypoints.min.js"></script>
    <script src="./assets/js/jquery.countdown.min.js"></script>
    <script src="./assets/js/hover-direction-snake.min.js"></script>
    <script src="./assets/js/contact.js"></script>
    <script src="./assets/js/jquery.form.js"></script>
    <script src="./assets/js/jquery.validate.min.js"></script>
    <script src="./assets/js/mail-script.js"></script>
    <script src="./assets/js/jquery.ajaxchimp.min.js"></script>
    <script src="./assets/js/plugins.js"></script>
    <script src="./assets/js/main.js"></script>

    <!-- Custom Smooth Scroll & ScrollSpy Nav Active Color -->
    <script>
        $(window).scroll(function() {
            // Back to top button visibility
            if ($(this).scrollTop() > 300) {
                $('#back-top').fadeIn();
            } else {
                $('#back-top').fadeOut();
            }

            // ScrollSpy active menu color switch
            var scrollPos = $(window).scrollTop() + 150;
            $('#navigation li a').each(function() {
                var currLink = $(this);
                var refElement = $(currLink.attr("href"));
                if (refElement.length && refElement.position().top <= scrollPos && refElement.position().top + refElement.height() > scrollPos) {
                    $('#navigation li').removeClass("active");
                    currLink.parent().addClass("active");
                }
            });
        });

        $('#back-top a').on("click", function() {
            $('body,html').animate({
                scrollTop: 0
            }, 800);
            return false;
        });

        // Click event untuk memindahkan class active secara manual
        $('#navigation li a').on('click', function() {
            $('#navigation li').removeClass('active');
            $(this).parent().addClass('active');
        });
    </script>
</body>

</html>