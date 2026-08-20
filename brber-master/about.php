<?php
include "../backend/connection.php";

// Data Profile
$query_profile = mysqli_query($koneksi, "SELECT * FROM profile LIMIT 1");

if (!$query_profile) {
    die("Query profile gagal: " . mysqli_error($koneksi));
}

$p = mysqli_fetch_assoc($query_profile);

if (!$p) {
    $p = [
        'name' => 'GHB BARBERSHOP',
        'description' => 'Barbershop profesional dengan pelayanan terbaik.',
        'address' => '-',
        'phone' => '-',
        'email' => '-',
        'instagram' => '#',
        'opening_hours' => '09:00 - 21:00',
        'logo' => ''
    ];
}

// Data Services
$query_services = mysqli_query(
    $koneksi,
    "SELECT * FROM services ORDER BY id_service DESC"
);

if (!$query_services) {
    die("Query services gagal: " . mysqli_error($koneksi));
}

// Data Barbers
$query_barbers = mysqli_query(
    $koneksi,
    "SELECT * FROM barbers ORDER BY id_barber DESC"
);

if (!$query_barbers) {
    die("Query barbers gagal: " . mysqli_error($koneksi));
}

// Data Pricing
$query_pricing = mysqli_query(
    $koneksi,
    "SELECT * FROM pricing ORDER BY id_pricing DESC"
);

if (!$query_pricing) {
    die("Query pricing gagal: " . mysqli_error($koneksi));
}

// Data Portfolio
$query_portfolio = mysqli_query(
    $koneksi,
    "SELECT * FROM portfolio ORDER BY id_portfolio DESC"
);

if (!$query_portfolio) {
    die("Query portfolio gagal: " . mysqli_error($koneksi));
}
?>
<!doctype html>
<html class="no-js" lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>About - <?php echo htmlspecialchars($p['name']); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($p['description']); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="manifest" href="site.webmanifest">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">

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
</head>

<body>

<!-- Preloader -->
<div id="preloader-active">
    <div class="preloader d-flex align-items-center justify-content-center">
        <div class="preloader-inner position-relative">
            <div class="preloader-circle"></div>
            <div class="preloader-img pere-text">
                <?php if (!empty($p['logo']) && file_exists("../backend/foto/" . $p['logo'])): ?>
                    <img src="../backend/foto/<?php echo htmlspecialchars($p['logo']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
                <?php else: ?>
                    <img src="assets/img/logo/loder.png" alt="Loading">
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Header -->
<header>
    <div class="header-area header-transparent">
        <div class="main-header header-sticky">
            <div class="container-fluid">
                <div class="row align-items-center">

                    <!-- Logo -->
                    <div class="col-xl-2 col-lg-2 col-md-1">
                        <div class="logo">
                            <a href="index.php">
                                <?php if (!empty($p['logo']) && file_exists("../backend/foto/" . $p['logo'])): ?>
                                    <img src="../backend/foto/<?php echo htmlspecialchars($p['logo']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
                                <?php else: ?>
                                    <img src="assets/img/logo/logo.png" alt="<?php echo htmlspecialchars($p['name']); ?>">
                                <?php endif; ?>
                            </a>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div class="col-xl-10 col-lg-10 col-md-10">
                        <div class="menu-main d-flex align-items-center justify-content-end">
                            <div class="main-menu f-right d-none d-lg-block">
                                <nav>
                                    <ul id="navigation">
                                        <li><a href="index.php">Home</a></li>
                                        <li class="active"><a href="about.php">About</a></li>
                                        <li><a href="index.php#services">Services</a></li>
                                        <li><a href="index.php#barbers">Barbers</a></li>
                                        <li><a href="index.php#pricing">Pricing</a></li>
                                        <li><a href="index.php#portfolio">Portfolio</a></li>
                                        <li><a href="index.php#testimonials">Testimonials</a></li>
                                        <li><a href="index.php#contact">Contact</a></li>
                                    </ul>
                                </nav>
                            </div>

                            <div class="header-right-btn f-right d-none d-lg-block ml-30">
                                <a href="index.php#contact" class="btn header-btn">Make an Appointment</a>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Menu -->
                    <div class="col-12">
                        <div class="mobile_menu d-block d-lg-none"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</header>

<main>

<!-- Hero -->
<div class="slider-area2">
    <div class="slider-height2 d-flex align-items-center">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="hero-cap hero-cap2 pt-70 text-center">
                        <h2>About Us</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- About -->
<section class="about-area section-padding30 position-relative">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-lg-6 col-md-11">
                <div class="about-img">
                    <img src="assets/img/gallery/about.png" alt="<?php echo htmlspecialchars($p['name']); ?>">
                </div>
            </div>

            <div class="col-lg-6 col-md-12">
                <div class="about-caption">

                    <div class="section-tittle section-tittle3 mb-35">
                        <span>About Our Company</span>
                        <h2><?php echo htmlspecialchars($p['name']); ?></h2>
                    </div>

                    <p class="mb-30 pera-bottom">
                        <?php echo nl2br(htmlspecialchars($p['description'])); ?>
                    </p>

                    <p class="pera-top mb-50">
                        Kami memberikan pelayanan barber profesional dengan kualitas terbaik untuk membantu Anda tampil lebih percaya diri.
                    </p>

                    <p>
                        <strong>Opening Hours:</strong><br>
                        <?php echo htmlspecialchars($p['opening_hours']); ?>
                    </p>

                    <p>
                        <strong>Address:</strong><br>
                        <?php echo nl2br(htmlspecialchars($p['address'])); ?>
                    </p>

                </div>
            </div>

        </div>
    </div>

    <div class="about-shape">
        <img src="assets/img/gallery/about-shape.png" alt="">
    </div>
</section>

<!-- Services -->
<section class="service-area pb-170">
    <div class="container">

        <div class="row d-flex justify-content-center">
            <div class="col-xl-7 col-lg-8 col-md-11 col-sm-11">
                <div class="section-tittle text-center mb-90">
                    <span>Professional Services</span>
                    <h2>Our Best Services</h2>
                </div>
            </div>
        </div>

        <div class="row">
            <?php while ($service = mysqli_fetch_assoc($query_services)): ?>
                <div class="col-xl-4 col-lg-4 col-md-6">
                    <div class="services-caption text-center mb-30">

                        <div class="service-icon">
                            <i class="flaticon-healthcare-and-medical"></i>
                        </div>

                        <div class="service-cap">
                            <h4>
                                <a href="index.php#services">
                                    <?php echo htmlspecialchars($service['name']); ?>
                                </a>
                            </h4>

                            <p>
                                <?php echo nl2br(htmlspecialchars($service['description'])); ?>
                            </p>

                            <?php if (isset($service['price'])): ?>
                                <strong>
                                    Rp <?php echo number_format($service['price'], 0, ',', '.'); ?>
                                </strong>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            <?php endwhile; ?>
        </div>

    </div>
</section>

<!-- Barbers -->
<div class="team-area pb-180">
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-8 col-md-11 col-sm-11">
                <div class="section-tittle text-center mb-100">
                    <span>Professional Teams</span>
                    <h2>Our Expert Barbers</h2>
                </div>
            </div>
        </div>

        <div class="row team-active dot-style">
            <?php while ($barber = mysqli_fetch_assoc($query_barbers)): ?>
                <div class="col-xl-4 col-lg-4 col-md-6">
                    <div class="single-team mb-80 text-center">

                        <div class="team-img">
                            <?php if (!empty($barber['image']) && file_exists("../backend/foto/" . $barber['image'])): ?>
                                <img src="../backend/foto/<?php echo htmlspecialchars($barber['image']); ?>" alt="<?php echo htmlspecialchars($barber['name']); ?>">
                            <?php else: ?>
                                <img src="assets/img/gallery/team1.png" alt="<?php echo htmlspecialchars($barber['name']); ?>">
                            <?php endif; ?>
                        </div>

                        <div class="team-caption">
                            <span>
                                <?php echo htmlspecialchars($barber['specialty']); ?>
                            </span>

                            <h3>
                                <a href="index.php#barbers">
                                    <?php echo htmlspecialchars($barber['name']); ?>
                                </a>
                            </h3>
                        </div>

                    </div>
                </div>
            <?php endwhile; ?>
        </div>

    </div>
</div>

<!-- Pricing -->
<div class="best-pricing section-padding2 position-relative">
    <div class="container">
        <div class="row justify-content-end">

            <div class="col-xl-7 col-lg-7">

                <div class="section-tittle mb-50">
                    <span>Our Best Pricing</span>
                    <h2>We provide best price<br>in the city!</h2>
                </div>

                <div class="row">
                    <?php while ($pricing = mysqli_fetch_assoc($query_pricing)): ?>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="pricing-list">
                                <ul>
                                    <li>
                                        <?php echo htmlspecialchars($pricing['name']); ?>
                                        <span>
                                            Rp <?php echo number_format($pricing['price'], 0, ',', '.'); ?>
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

            </div>

        </div>
    </div>

    <div class="pricing-img">
        <img class="pricing-img1" src="assets/img/gallery/pricing1.png" alt="">
        <img class="pricing-img2" src="assets/img/gallery/pricing2.png" alt="">
    </div>
</div>

<!-- Portfolio -->
<div class="gallery-area section-padding30">
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-7 col-md-9 col-sm-10">
                <div class="section-tittle text-center mb-100">
                    <span>Our Image Gallery</span>
                    <h2>Some Images From Our Barber Shop</h2>
                </div>
            </div>
        </div>

        <div class="row">
            <?php while ($portfolio = mysqli_fetch_assoc($query_portfolio)): ?>
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="box snake mb-30">

                        <?php if (!empty($portfolio['image']) && file_exists("../backend/foto/" . $portfolio['image'])): ?>
                            <div class="gallery-img" style="background-image: url('../backend/foto/<?php echo htmlspecialchars($portfolio['image']); ?>');"></div>
                        <?php else: ?>
                            <div class="gallery-img" style="background-image: url('assets/img/gallery/gallery1.png');"></div>
                        <?php endif; ?>

                        <div class="overlay"></div>

                    </div>
                </div>
            <?php endwhile; ?>
        </div>

    </div>
</div>

</main>

<!-- Footer -->
<footer>
    <div class="footer-area section-bg" data-background="assets/img/gallery/footer_bg.png">
        <div class="container">

            <div class="footer-top footer-padding">
                <div class="row d-flex justify-content-between">

                    <!-- Logo & Info -->
                    <div class="col-xl-3 col-lg-4 col-md-5 col-sm-8">
                        <div class="single-footer-caption mb-50">

                            <div class="footer-logo">
                                <a href="index.php">
                                    <?php if (!empty($p['logo']) && file_exists("../backend/foto/" . $p['logo'])): ?>
                                        <img src="../backend/foto/<?php echo htmlspecialchars($p['logo']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
                                    <?php else: ?>
                                        <img src="assets/img/logo/logo2_footer.png" alt="<?php echo htmlspecialchars($p['name']); ?>">
                                    <?php endif; ?>
                                </a>
                            </div>

                            <div class="footer-tittle">
                                <div class="footer-pera">
                                    <p class="info1">
                                        <?php echo htmlspecialchars($p['description']); ?>
                                    </p>
                                </div>
                            </div>

                            <div class="footer-number">
                                <h4><?php echo htmlspecialchars($p['phone']); ?></h4>
                                <p><?php echo htmlspecialchars($p['email']); ?></p>
                            </div>

                        </div>
                    </div>

                    <!-- Location -->
                    <div class="col-xl-2 col-lg-2 col-md-3 col-sm-5">
                        <div class="single-footer-caption mb-50">
                            <div class="footer-tittle">
                                <h4>Location</h4>
                                <ul>
                                    <li>
                                        <?php echo htmlspecialchars($p['address']); ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Opening Hours -->
                    <div class="col-xl-2 col-lg-2 col-md-3 col-sm-5">
                        <div class="single-footer-caption mb-50">
                            <div class="footer-tittle">
                                <h4>Opening Hours</h4>
                                <ul>
                                    <li>
                                        <?php echo htmlspecialchars($p['opening_hours']); ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-8">
                        <div class="single-footer-caption mb-50">
                            <div class="footer-tittle">
                                <h4>Follow Us</h4>
                            </div>

                            <div class="footer-social f-right">
                                <a href="<?php echo htmlspecialchars($p['instagram']); ?>" target="_blank">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="footer-bottom">
                <div class="row d-flex justify-content-between align-items-center">
                    <div class="col-xl-9 col-lg-8">
                        <div class="footer-copy-right">
                            <p>
                                Copyright &copy; <?php echo date('Y'); ?>
                                <?php echo htmlspecialchars($p['name']); ?>.
                                All rights reserved.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</footer>

<!-- Scroll Up -->
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