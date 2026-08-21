<?php
include "../backend/connection.php";

/* =========================
   HELPER
========================= */
function e($value)
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/* =========================
   PROFILE
========================= */
$query_profile = mysqli_query(
    $koneksi,
    "SELECT * FROM profile LIMIT 1"
);

$p = mysqli_fetch_assoc($query_profile);

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

    <title><?php echo e($p['name']); ?></title>

    <meta
        name="description"
        content="<?php echo e($p['description']); ?>">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1">

    <link
        rel="icon"
        type="image/x-icon"
        href="../backend/foto/logo.ico">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Oswald:wght@400;500;600;700&display=swap" rel="stylesheet">

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

    <!-- CUSTOM LUXURY ESTHETIC STYLING -->
    <style>
        :root {
            --primary-gold: #d19f68;
            --dark-bg: #0d0d0d;
            --card-bg: #16161a;
        }

        body {
            font-family: 'Montserrat', sans-serif !important;
            background-color: var(--dark-bg);
            color: #d1d1d1;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Oswald', sans-serif !important;
            text-transform: uppercase;
        }

        /* HEADER & NAVBAR MODERN */
        .header-area {
            background: rgba(13, 13, 13, 0.9);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(209, 159, 104, 0.15);
        }

        .header-area .logo a {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .header-area .logo img {
            max-height: 50px;
            width: auto;
        }

        .header-area .logo span {
            color: #fff;
            font-weight: 700;
            font-size: 18px;
            letter-spacing: 1px;
            font-family: 'Oswald', sans-serif;
        }

        .header-area .main-menu ul li a {
            font-family: 'Montserrat', sans-serif !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            color: #fff !important;
            padding: 30px 14px !important;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .header-area .main-menu ul li.active>a,
        .header-area .main-menu ul li:hover>a {
            color: var(--primary-gold) !important;
        }

        .header-area .main-menu ul li a::before,
        .header-area .main-menu ul li a::after {
            display: none !important;
        }

        .header-btn {
            background: var(--primary-gold) !important;
            color: #000 !important;
            font-weight: 700 !important;
            border-radius: 4px !important;
            padding: 14px 28px !important;
            transition: all 0.3s ease;
        }

        .header-btn:hover {
            background: #fff !important;
            box-shadow: 0 0 20px rgba(209, 159, 104, 0.4);
        }

        /* HERO SECTION */
        .slider-area .hero__caption span {
            font-family: 'Montserrat', sans-serif !important;
            font-size: 12px !important;
            font-weight: 700 !important;
            letter-spacing: 3px !important;
            color: var(--primary-gold) !important;
            background: rgba(209, 159, 104, 0.1);
            border: 1px solid rgba(209, 159, 104, 0.3);
            padding: 8px 20px !important;
            display: inline-block;
            margin-bottom: 20px !important;
            border-radius: 30px;
        }

        .slider-area .hero__caption h1 {
            font-size: 48px !important;
            font-weight: 700 !important;
            color: #fff !important;
            line-height: 1.2 !important;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.8);
        }

        .stock-text h2 {
            font-size: 100px !important;
            color: transparent !important;
            -webkit-text-stroke: 1px rgba(209, 159, 104, 0.1);
        }

        /* SECTION HEADINGS */
        .section-tittle span {
            color: var(--primary-gold) !important;
            letter-spacing: 2px;
        }

        .section-tittle h2 {
            color: #fff !important;
            font-weight: 700 !important;
        }

        /* SERVICES CARDS */
        .services-caption {
            background: var(--card-bg) !important;
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 50px 30px !important;
            transition: all 0.3s ease;
        }

        .services-caption:hover {
            transform: translateY(-8px);
            border-color: var(--primary-gold);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        .service-icon i {
            background: var(--primary-gold) !important;
            color: #000 !important;
            border-radius: 50%;
        }

        .services-caption .service-cap h4 a {
            color: #fff !important;
        }

        .services-caption strong {
            color: var(--primary-gold);
            font-size: 18px;
            display: block;
            margin-top: 15px;
        }

        /* TEAM / BARBERS CARDS */
        .single-team {
            background: var(--card-bg);
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .team-caption {
            background: #111 !important;
            border-top: 1px solid rgba(209, 159, 104, 0.2);
        }

        /* PRICING */
        .best-pricing {
            background: #111 !important;
        }

        .pricing-list ul li {
            background: var(--card-bg);
            padding: 15px 20px;
            margin-bottom: 12px;
            border-radius: 6px;
            border-left: 3px solid var(--primary-gold);
            color: #fff !important;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* CONTACT BOXES */
        .contact-box {
            background: var(--card-bg);
            padding: 40px 30px;
            border-radius: 10px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
            height: 100%;
        }

        .contact-box:hover {
            border-color: var(--primary-gold);
            transform: translateY(-5px);
        }

        .contact-box i {
            font-size: 35px;
            color: var(--primary-gold);
            margin-bottom: 20px;
        }

        .contact-box h4 {
            color: #fff;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

    <!-- PRELOADER -->
    <div id="preloader-active">
        <div class="preloader d-flex align-items-center justify-content-center">
            <div class="preloader-inner position-relative">
                <div class="preloader-circle"></div>
                <div class="preloader-img pere-text">
                    <?php if (!empty($p['logo'])): ?>
                        <img
                            src="../backend/foto/<?php echo e($p['logo']); ?>"
                            alt="<?php echo e($p['name']); ?>">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- HEADER -->
    <header>
        <div class="header-area header-transparent">
            <div class="main-header header-sticky">
                <div class="container-fluid">
                    <div class="row align-items-center">

                        <!-- LOGO -->
                        <div class="col-xl-3 col-lg-3 col-md-4">
                            <div class="logo">
                                <a href="index.php">
                                    <?php if (!empty($p['logo'])): ?>
                                        <img
                                            src="../backend/foto/<?php echo e($p['logo']); ?>"
                                            alt="<?php echo e($p['name']); ?>">
                                    <?php else: ?>
                                        <img
                                            src="assets/img/logo/logo.png"
                                            alt="<?php echo e($p['name']); ?>">
                                    <?php endif; ?>
                                    <span><?php echo e($p['name']); ?></span>
                                </a>
                            </div>
                        </div>

                        <!-- NAVIGATION -->
                        <div class="col-xl-9 col-lg-9 col-md-8">
                            <div class="menu-main d-flex align-items-center justify-content-end">
                                <div class="main-menu f-right d-none d-lg-block">
                                    <nav>
                                        <ul id="navigation">
                                            <li class="active"><a href="index.php">Home</a></li>
                                            <li><a href="#about">About</a></li>
                                            <li><a href="#services">Services</a></li>
                                            <li><a href="#barbers">Barbers</a></li>
                                            <li><a href="#pricing">Pricing</a></li>
                                            <li><a href="#portfolio">Portfolio</a></li>
                                            <li><a href="#testimonials">Testimonials</a></li>
                                            <li><a href="#contact">Contact</a></li>
                                        </ul>
                                    </nav>
                                </div>
                                <div class="header-right-btn f-right d-none d-lg-block ml-30">
                                    <a href="payment.php" class="btn header-btn">
                                        Langganan & Booking
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- MOBILE MENU -->
                        <div class="col-12">
                            <div class="mobile_menu d-block d-lg-none"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </header>

    <main>

        <!-- HERO -->
        <div class="slider-area position-relative fix">
            <div class="slider-active">

                <!-- SLIDE 1 -->
                <div class="single-slider slider-height d-flex align-items-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-8 col-lg-9 col-md-11 col-sm-10">
                                <div class="hero__caption">
                                    <span data-animation="fadeInUp" data-delay="0.2s">
                                        Welcome to <?php echo e($p['name']); ?>
                                    </span>
                                    <h1 data-animation="fadeInUp" data-delay="0.5s">
                                        <?php echo e($p['description']); ?>
                                    </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SLIDE 2 -->
                <div class="single-slider slider-height d-flex align-items-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-8 col-lg-9 col-md-11 col-sm-10">
                                <div class="hero__caption">
                                    <span data-animation="fadeInUp" data-delay="0.2s">
                                        Professional Barber
                                    </span>
                                    <h1 data-animation="fadeInUp" data-delay="0.5s">
                                        Your Style, Your Confidence
                                    </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- STROKE TEXT -->
            <div class="stock-text">
                <h2>Get More Confident</h2>
                <h2>Get More Confident</h2>
            </div>
        </div>

        <!-- ABOUT -->
        <section id="about" class="about-area section-padding30 position-relative">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-11">
                        <div class="about-img">
                            <img src="assets/img/gallery/about.png" alt="<?php echo e($p['name']); ?>">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="about-caption">
                            <div class="section-tittle section-tittle3 mb-35">
                                <span>About Our Company</span>
                                <h2 style="color: #fff;"><?php echo e($p['name']); ?></h2>
                            </div>
                            <p class="mb-30 pera-bottom" style="color: #ccc;">
                                <?php echo nl2br(e($p['description'])); ?>
                            </p>
                            <p class="pera-top mb-50" style="color: #aaa;">
                                Kami memberikan pelayanan barber profesional dengan kualitas terbaik untuk membantu Anda tampil lebih percaya diri.
                            </p>
                            <p style="color: var(--primary-gold);">
                                <strong>Opening Hours:</strong><br>
                                <span style="color: #fff;"><?php echo e($p['opening_hours']); ?></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- =========================
             SERVICES
        ========================= -->
        <section
            id="services"
            class="service-area pb-170">

            <div class="container">

                <div class="row d-flex justify-content-center">

                    <div class="col-xl-7 col-lg-8 col-md-11 col-sm-11">

                        <div class="section-tittle text-center mb-90">

                            <span>
                                Professional Services
                            </span>

                            <h2>
                                Our Best Services
                            </h2>

                        </div>

                    </div>

                </div>

                <div class="row">

                    <?php if ($query_services && mysqli_num_rows($query_services) > 0): ?>

                        <?php while ($service = mysqli_fetch_assoc($query_services)): ?>

                            <div class="col-xl-4 col-lg-4 col-md-6 mb-30">

                                <div class="services-caption text-center h-100" style="padding: 0 !important; overflow: hidden; background: var(--card-bg);">

                                    <!-- Foto Ditampilkan Utuh Tanpa Terpotong & Rapi -->
                                    <?php
                                    $service_image = !empty($service['image'])
                                        ? "../backend/foto/" . $service['image']
                                        : "assets/img/gallery/service1.png";
                                    ?>
                                    <div style="height: 220px; width: 100%; background: #111; display: flex; align-items: center; justify-content: center; padding: 12px; overflow: hidden;">
                                        <img
                                            src="<?php echo e($service_image); ?>"
                                            alt="<?php echo e($service['name']); ?>"
                                            style="max-width: 100%; max-height: 100%; object-fit: contain; transition: transform 0.4s ease;"
                                            onmouseover="this.style.transform='scale(1.05)'"
                                            onmouseout="this.style.transform='scale(1)'">
                                    </div>

                                    <div style="padding: 30px;">
                                        <div class="service-cap">

                                            <h4 class="mt-10">
                                                <a href="#" style="color: #fff; font-size: 20px;">
                                                    <?php echo e($service['name']); ?>
                                                </a>
                                            </h4>

                                            <p style="color: #aaa; font-size: 14px; min-height: 50px;">
                                                <?php echo nl2br(e($service['description'])); ?>
                                            </p>

                                            <strong style="color: var(--primary-gold); font-size: 18px; display: block; margin-top: 15px;">
                                                Rp <?php echo number_format((float)$service['price'], 0, ',', '.'); ?>
                                            </strong>

                                            <?php if (!empty($service['duration'])): ?>
                                                <p style="color: #888; font-size: 13px; margin-top: 8px;">
                                                    <i class="far fa-clock" style="color: var(--primary-gold);"></i> <?php echo e($service['duration']); ?>
                                                </p>
                                            <?php endif; ?>

                                        </div>
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
        <section id="barbers" class="team-area pb-180">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-8 col-lg-8 col-md-11 col-sm-11">
                        <div class="section-tittle text-center mb-100">
                            <span>Professional Teams</span>
                            <h2>Our Expert Barbers</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <?php if ($query_barbers && mysqli_num_rows($query_barbers) > 0): ?>
                        <?php while ($barber = mysqli_fetch_assoc($query_barbers)): ?>
                            <div class="col-xl-4 col-lg-4 col-md-6 mb-4">
                                <div class="single-team text-center h-100">
                                    <div class="team-img" style="height: 320px; overflow: hidden;">
                                        <?php
                                        $barber_image = !empty($barber['image'])
                                            ? "../backend/foto/" . $barber['image']
                                            : "assets/img/gallery/team1.png";
                                        ?>
                                        <img src="<?php echo e($barber_image); ?>" alt="<?php echo e($barber['name']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                    <div class="team-caption p-4">
                                        <?php if (!empty($barber['specialty'])): ?>
                                            <span style="color: var(--primary-gold); font-size: 12px; letter-spacing: 1px;"><?php echo e($barber['specialty']); ?></span>
                                        <?php endif; ?>
                                        <h3 class="mt-2 mb-0">
                                            <a href="#" style="color: #fff; font-size: 18px;"><?php echo e($barber['name']); ?></a>
                                        </h3>
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
            <!-- =========================
             PRICING (Full Width Grid 2 Kolom)
        ========================= -->
            <section
                id="pricing"
                class="best-pricing section-padding2 position-relative"
                style="background: #111;">

                <div class="container">

                    <div class="row justify-content-center">
                        <div class="col-xl-7 col-lg-8">
                            <div class="section-tittle text-center mb-70">
                                <span>Our Best Pricing</span>
                                <h2>We provide best price<br>in the city!</h2>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            <div class="row">
                                <?php if ($query_pricing && mysqli_num_rows($query_pricing) > 0): ?>
                                    <?php while ($pricing = mysqli_fetch_assoc($query_pricing)): ?>
                                        <div class="col-lg-6 mb-4">
                                            <div class="pricing-list" style="background: var(--card-bg); padding: 22px 25px; border-radius: 8px; border-left: 4px solid var(--primary-gold); border-top: 1px solid rgba(255,255,255,0.05); border-right: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
                                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                                    <div>
                                                        <span style="font-weight: 600; font-size: 16px; color: #fff; font-family: 'Montserrat', sans-serif; display: block; margin-bottom: 4px;"><?php echo e($pricing['name']); ?></span>
                                                        <?php if (!empty($pricing['description'])): ?>
                                                            <span style="color: #888; font-size: 13px;"><?php echo e($pricing['description']); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <span style="color: var(--primary-gold); font-weight: 700; font-size: 18px; font-family: 'Oswald', sans-serif; white-space: nowrap; margin-left: 15px;">
                                                        Rp <?php echo number_format((float)$pricing['price'], 0, ',', '.'); ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <div class="col-12 text-center">
                                        <p style="color: #888;">Belum ada data pricing.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div>

            </section>

            <!-- PORTFOLIO -->
            <section id="portfolio" class="gallery-area section-padding30">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-xl-6 col-lg-7 col-md-9 col-sm-10">
                            <div class="section-tittle text-center mb-100">
                                <span>Our Portfolio</span>
                                <h2>Our Latest Work</h2>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <?php if ($query_portfolio && mysqli_num_rows($query_portfolio) > 0): ?>
                            <?php while ($portfolio = mysqli_fetch_assoc($query_portfolio)): ?>
                                <div class="col-lg-4 col-md-6 col-sm-6 mb-30">
                                    <div class="box snake h-100" style="border-radius: 8px; overflow: hidden; position: relative;">
                                        <?php
                                        $portfolio_image = !empty($portfolio['image'])
                                            ? "../backend/foto/" . $portfolio['image']
                                            : "assets/img/gallery/gallery1.png";
                                        ?>
                                        <div class="gallery-img" style="height: 350px; background-image:url('<?php echo e($portfolio_image); ?>'); background-size: cover; background-position: center;"></div>
                                        <div class="overlay" style="background: rgba(0,0,0,0.6); display: flex; align-items: flex-end; padding: 25px;">
                                            <div class="portfolio-title">
                                                <h4 style="color: #fff; margin-bottom: 5px;"><?php echo e($portfolio['title']); ?></h4>
                                                <?php if (!empty($portfolio['category'])): ?>
                                                    <p style="color: var(--primary-gold); font-size: 13px; margin: 0;"><?php echo e($portfolio['category']); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="col-12 text-center">
                                <p>Belum ada data portfolio.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

            <!-- TESTIMONIALS -->
            <section id="testimonials" class="section-padding2" style="background: #111;">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-xl-7 col-lg-8">
                            <div class="section-tittle text-center mb-70">
                                <span>Customer Testimonials</span>
                                <h2>What Our Customers Say</h2>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <?php if ($query_testimonials && mysqli_num_rows($query_testimonials) > 0): ?>
                            <?php while ($testimonial = mysqli_fetch_assoc($query_testimonials)): ?>
                                <div class="col-lg-4 col-md-6 mb-30">
                                    <div class="single-cut h-100" style="background: var(--card-bg); padding: 35px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.05);">
                                        <div class="cut-icon mb-20" style="color: var(--primary-gold); font-size: 24px;">
                                            <i class="fas fa-quote-left"></i>
                                        </div>
                                        <div class="cut-descriptions">
                                            <p style="color: #bbb; font-style: italic; font-size: 15px; min-height: 80px;">
                                                "<?php echo nl2br(e($testimonial['message'])); ?>"
                                            </p>
                                            <span style="color: #fff; font-weight: 700; display: block; margin-top: 15px; font-family: 'Oswald', sans-serif; font-size: 16px;">
                                                - <?php echo e($testimonial['name']); ?>
                                            </span>
                                            <div class="testimonial-rating mt-2" style="color: var(--primary-gold); font-size: 13px;">
                                                <?php
                                                $rating = (int)$testimonial['rating'];
                                                for ($i = 1; $i <= 5; $i++) {
                                                    if ($i <= $rating) {
                                                        echo '<i class="fas fa-star"></i>';
                                                    } else {
                                                        echo '<i class="far fa-star text-muted"></i>';
                                                    }
                                                }
                                                ?>
                                            </div>
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

            <!-- =========================
             CONTACT & MAPS
        ========================= -->
            <section
                id="contact"
                class="section-padding30"
                style="background: var(--dark-bg);">

                <div class="container">

                    <div class="row justify-content-center">
                        <div class="col-xl-7 col-lg-8">
                            <div class="section-tittle text-center mb-50">
                                <span>Contact Us</span>
                                <h2>Visit <?php echo e($p['name']); ?></h2>
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-center">

                        <!-- KOLOM KIRI: INFO KONTAK -->
                        <div class="col-lg-5 mb-30">
                            <div class="row">
                                <div class="col-12 mb-20">
                                    <div class="contact-box text-left d-flex align-items-center" style="padding: 25px 30px;">
                                        <i class="fas fa-map-marker-alt" style="font-size: 28px; margin-right: 20px; margin-bottom: 0;"></i>
                                        <div>
                                            <h4 style="margin-bottom: 5px; font-size: 18px;">Address</h4>
                                            <p style="color: #aaa; font-size: 14px; margin: 0;"><?php echo nl2br(e($p['address'])); ?></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mb-20">
                                    <div class="contact-box text-left d-flex align-items-center" style="padding: 25px 30px;">
                                        <i class="fas fa-phone" style="font-size: 28px; margin-right: 20px; margin-bottom: 0;"></i>
                                        <div>
                                            <h4 style="margin-bottom: 5px; font-size: 18px;">Phone</h4>
                                            <p style="color: #aaa; font-size: 14px; margin: 0;"><?php echo e($p['phone']); ?></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="contact-box text-left d-flex align-items-center" style="padding: 25px 30px;">
                                        <i class="fas fa-envelope" style="font-size: 28px; margin-right: 20px; margin-bottom: 0;"></i>
                                        <div>
                                            <h4 style="margin-bottom: 5px; font-size: 18px;">Email</h4>
                                            <p style="color: #aaa; font-size: 14px; margin: 0;"><?php echo e($p['email']); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- KOLOM KANAN: GOOGLE MAPS -->
                        <div class="col-lg-7 mb-30">
                            <div style="background: var(--card-bg); padding: 10px; border-radius: 12px; border: 1px solid rgba(209,159,104,0.2); box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                                <div style="width: 100%; height: 380px; border-radius: 8px; overflow: hidden;">
                                    <iframe
                                        src="https://maps.google.com/maps?q=<?php echo urlencode($p['address']); ?>&t=&z=14&ie=UTF8&iwloc=&output=embed"
                                        width="100%"
                                        height="100%"
                                        style="border:0; filter: grayscale(20%) contrast(1.2);"
                                        allowfullscreen=""
                                        loading="lazy">
                                    </iframe>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </section>

    </main>

    <!-- FOOTER -->
    <footer>
        <div class="footer-area section-bg" style="background: #090909; border-top: 1px solid rgba(209,159,104,0.1);">
            <div class="container">
                <div class="footer-top footer-padding">
                    <div class="row d-flex justify-content-between">
                        <!-- INFO -->
                        <div class="col-xl-3 col-lg-4 col-md-5 col-sm-8 mb-30">
                            <div class="single-footer-caption">
                                <div class="footer-logo mb-20">
                                    <a href="index.php">
                                        <?php if (!empty($p['logo'])): ?>
                                            <img src="../backend/foto/<?php echo e($p['logo']); ?>" alt="<?php echo e($p['name']); ?>" style="max-height: 45px;">
                                        <?php else: ?>
                                            <img src="assets/img/logo/logo2_footer.png" alt="<?php echo e($p['name']); ?>">
                                        <?php endif; ?>
                                    </a>
                                </div>
                                <div class="footer-tittle">
                                    <div class="footer-pera">
                                        <p class="info1" style="color: #888; font-size: 14px;"><?php echo e($p['description']); ?></p>
                                    </div>
                                </div>
                                <div class="footer-number">
                                    <h4 style="color: var(--primary-gold); font-size: 20px;"><?php echo e($p['phone']); ?></h4>
                                    <p style="color: #888; font-size: 14px;"><?php echo e($p['email']); ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- LOCATION -->
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-5 mb-30">
                            <div class="single-footer-caption">
                                <div class="footer-tittle">
                                    <h4 style="color: #fff; font-size: 18px; margin-bottom: 20px;">Location</h4>
                                    <p style="color: #888; font-size: 14px;"><?php echo nl2br(e($p['address'])); ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- OPENING -->
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-5 mb-30">
                            <div class="single-footer-caption">
                                <div class="footer-tittle">
                                    <h4 style="color: #fff; font-size: 18px; margin-bottom: 20px;">Opening Hours</h4>
                                    <p style="color: #888; font-size: 14px;"><?php echo e($p['opening_hours']); ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- SOCIAL -->
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-5 mb-30">
                            <div class="single-footer-caption">
                                <div class="footer-tittle">
                                    <h4 style="color: #fff; font-size: 18px; margin-bottom: 20px;">Follow Us</h4>
                                </div>
                                <div class="footer-social">
                                    <?php if (!empty($p['instagram'])): ?>
                                        <a href="<?php echo e($p['instagram']); ?>" target="_blank" style="background: rgba(209,159,104,0.1); width: 45px; height: 45px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; color: var(--primary-gold); font-size: 18px; transition: all 0.3s ease;">
                                            <i class="fab fa-instagram"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="footer-bottom" style="border-top: 1px solid rgba(255,255,255,0.05); padding: 25px 0;">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="footer-copy-right text-center">
                                <p style="color: #777; font-size: 14px; margin: 0;">
                                    Copyright &copy; <?php echo date('Y'); ?> <?php echo e($p['name']); ?>. All rights reserved.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- BACK TO TOP -->
    <div id="back-top">
        <a title="Go to Top" href="#">
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

</body>

</html>