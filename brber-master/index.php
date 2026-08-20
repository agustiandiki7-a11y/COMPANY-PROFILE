<?php

include "../backend/connection.php";

// Data Profile
$tampil_profile = mysqli_query(
    $koneksi,
    "SELECT * FROM profile LIMIT 1"
);

$p = mysqli_fetch_assoc($tampil_profile);

// Data default jika profile belum ada
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
$tampil_services = mysqli_query(
    $koneksi,
    "SELECT * FROM services ORDER BY id_service DESC"
);

// Data Barbers
$tampil_barbers = mysqli_query(
    $koneksi,
    "SELECT * FROM barbers ORDER BY id_barber DESC"
);

// Data Pricing
$tampil_pricing = mysqli_query(
    $koneksi,
    "SELECT * FROM pricing ORDER BY id_pricing DESC"
);

// Data Portfolio
$tampil_portfolio = mysqli_query(
    $koneksi,
    "SELECT * FROM portfolio ORDER BY id_portfolio DESC"
);

// Data Testimonials
$tampil_testimonials = mysqli_query(
    $koneksi,
    "SELECT * FROM testimonials ORDER BY id_testimonial DESC"
);

?>

<!doctype html>
<html class="no-js" lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>GHB barbershop</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/jpeg" href="../backend/foto/logo.ico">

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
    <!-- ? Preloader Start -->
    <div id="preloader-active">
        <div class="preloader d-flex align-items-center justify-content-center">
            <div class="preloader-inner position-relative">
                <div class="preloader-circle"></div>
                <div class="preloader-img pere-text">
                    <img src="assets/img/logo/loder.png" alt="">
                </div>
            </div>
        </div>
    </div>
    <!-- Preloader Start -->
   <header>
    <!-- Header Start -->
    <div class="header-area header-transparent pt-20">
        <div class="main-header header-sticky">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <!-- Logo -->
                    <div class="col-xl-2 col-lg-2 col-md-1">
                        <div class="logo">
                            <a href="index.php"><img src="assets/img/logo/logo.png" alt="Logo"></a>
                        </div>
                    </div>
                    <div class="col-xl-10 col-lg-10 col-md-10">
                        <div class="menu-main d-flex align-items-center justify-content-end">
                            <!-- Main-menu -->
                            <div class="main-menu f-right d-none d-lg-block">
                                <nav>
                                    <ul id="navigation">
                                        <li class="active"><a href="index.php">Home</a></li>
                                        <li><a href="about.php">About</a></li>
                                        <li><a href="services.php">Services</a></li>
                                        <li><a href="portfolio.php">Portfolio</a></li>
                                        <li><a href="blog.php">Blog</a>
                                            <ul class="submenu">
                                                <li><a href="blog.php">Blog</a></li>
                                                <li><a href="blog_details.php">Blog Details</a></li>
                                                <li><a href="elements.php">Element</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="contact.php">Contact</a></li>
                                    </ul>
                                </nav>
                            </div>
                            <div class="header-right-btn f-right d-none d-lg-block ml-30">
                                <a href="booking.php" class="btn header-btn">langganan dan booking</a>
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
    <!-- Header End -->
</header>
    <main>
        <!--? slider Area Start-->
        <div class="slider-area position-relative fix">
            <div class="slider-active">
                <!-- Single Slider -->
                <div class="single-slider slider-height d-flex align-items-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-8 col-lg-9 col-md-11 col-sm-10">
                                <div class="hero__caption">
                                    <span data-animation="fadeInUp" data-delay="0.2s">with patrick potter</span>
                                    <h1 data-animation="fadeInUp" data-delay="0.5s">Our Hair Style make your look elegance</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Single Slider -->
                <div class="single-slider slider-height d-flex align-items-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-8 col-lg-9 col-md-11 col-sm-10">
                                <div class="hero__caption">
                                    <span data-animation="fadeInUp" data-delay="0.2s">with patrick potter</span>
                                    <h1 data-animation="fadeInUp" data-delay="0.5s">Our Hair Style make your look elegance</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- stroke Text -->
            <div class="stock-text">
                <h2>Get More confident</h2>
                <h2>Get More confident</h2>
            </div>
            <!-- Arrow -->
            <div class="thumb-content-box">
                <div class="thumb-content">
                    <h3>boking and maebersip</h3>
                    <a href="#"> <i class="fas fa-long-arrow-alt-right"></i></a>
                </div>
            </div>
        </div>
        <!-- slider Area End-->
        <!--? About Area Start -->
        <section class="about-area section-padding30 position-relative">
            <div class="container">
                <div class="row align-items-center">

                    <div class="col-lg-6 col-md-11">
                        <div class="about-img">
                            <img src="assets/img/gallery/about.png"
                                alt="<?php echo htmlspecialchars($p['name']); ?>">
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
                                Kami memberikan pelayanan barber profesional
                                dengan kualitas terbaik untuk membantu Anda
                                tampil lebih percaya diri.
                            </p>

                            <p>
                                <strong>Opening Hours:</strong><br>
                                <?php echo htmlspecialchars($p['opening_hours']); ?>
                            </p>

                        </div>
                    </div>

                </div>
            </div>

            <div class="about-shape">
                <img src="assets/img/gallery/about-shape.png" alt="">
            </div>
        </section>
        <!-- About-2 Area End -->
        <!--? Services Area Start -->
        <section id="services" class="service-area pb-170">
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
                    <?php while ($service = mysqli_fetch_assoc($tampil_services)): ?>
                        <div class="col-xl-4 col-lg-4 col-md-6">
                            <div class="services-caption text-center mb-30">
                                <div class="service-icon">
                                    <i class="flaticon-healthcare-and-medical"></i>
                                </div>

                                <div class="service-cap">
                                    <h4>
                                        <a href="#">
                                            <?php echo htmlspecialchars($service['name']); ?>
                                        </a>
                                    </h4>

                                    <p>
                                        <?php echo nl2br(htmlspecialchars($service['description'])); ?>
                                    </p>

                                    <strong>
                                        Rp <?php echo number_format($service['price'], 0, ',', '.'); ?>
                                    </strong>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
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
                        <?php
                        $query_barbers = mysqli_query(
                            $koneksi,
                            "SELECT * FROM barbers ORDER BY id_barber DESC"
                        );
                        ?>

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
                                        <span><?php echo htmlspecialchars($barber['specialty']); ?></span>
                                        <h3>
                                            <a href="#">
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
            <div class="best-pricing section-padding2 position-relative">
                <div class="container">
                    <div class="row justify-content-end">
                        <div class="col-xl-7 col-lg-7">
                            <div class="section-tittle mb-50">
                                <span>Our Best Pricing</span>
                                <h2>We provide best price<br>in the city!</h2>
                            </div>

                            <div class="row">
                                <?php
                                $query_pricing = mysqli_query(
                                    $koneksi,
                                    "SELECT * FROM pricing ORDER BY id_pricing DESC"
                                );
                                ?>

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
                        <?php
                        $query_portfolio = mysqli_query(
                            $koneksi,
                            "SELECT * FROM portfolio ORDER BY id_portfolio DESC"
                        );
                        ?>

                        <?php while ($portfolio = mysqli_fetch_assoc($query_portfolio)): ?>
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <div class="box snake mb-30">
                                    <?php if (!empty($portfolio['image']) && file_exists("../backend/foto/" . $portfolio['image'])): ?>
                                        <div
                                            class="gallery-img"
                                            style="background-image: url('../backend/foto/<?php echo htmlspecialchars($portfolio['image']); ?>');">
                                        </div>
                                    <?php else: ?>
                                        <div
                                            class="gallery-img"
                                            style="background-image: url('assets/img/gallery/gallery1.png');">
                                        </div>
                                    <?php endif; ?>

                                    <div class="overlay"></div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
            <div class="cut-details section-bg section-padding2" data-background="assets/img/gallery/section_bg02.png">
                <div class="container">
                    <div class="cut-active dot-style">
                        <?php
                        $query_testimonials = mysqli_query(
                            $koneksi,
                            "SELECT * FROM testimonials ORDER BY id_testimonial DESC"
                        );
                        ?>

                        <?php while ($testimonial = mysqli_fetch_assoc($query_testimonials)): ?>
                            <div class="single-cut">
                                <div class="cut-icon mb-20">
                                    <i class="fas fa-quote-left"></i>
                                </div>

                                <div class="cut-descriptions">
                                    <p>
                                        <?php echo nl2br(htmlspecialchars($testimonial['message'])); ?>
                                    </p>

                                    <span>
                                        <?php echo htmlspecialchars($testimonial['name']); ?>
                                    </span>

                                    <div class="mt-2">
                                        <?php
                                        $rating = (int) $testimonial['rating'];

                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $rating) {
                                                echo '<i class="fas fa-star"></i>';
                                            } else {
                                                echo '<i class="far fa-star"></i>';
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
            <div class="container">
                <!-- Section Tittle -->
                <div class="row justify-content-center">
                    <div class="col-xl-6 col-lg-7 col-md-10 col-sm-10">
                        <div class="section-tittle text-center mb-90">
                            <span>our recent news</span>
                            <h2>Hipos and tricks from recent blog</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <div class="home-blog-single mb-30">
                            <div class="blog-img-cap">
                                <div class="blog-img">
                                    <img src="assets/img/gallery/home-blog1.png" alt="">
                                    <!-- Blog date -->
                                    <div class="blog-date text-center">
                                        <span>24</span>
                                        <p>Now</p>
                                    </div>
                                </div>
                                <div class="blog-cap">
                                    <p>| Physics</p>
                                    <h3><a href="blog_details.html">Footprints in Time is perfect House in Kurashiki</a></h3>
                                    <a href="blog_details.html" class="more-btn">became a member »</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <div class="home-blog-single mb-30">
                            <div class="blog-img-cap">
                                <div class="blog-img">
                                    <img src="assets/img/gallery/home-blog2.png" alt="">
                                    <!-- Blog date -->
                                    <div class="blog-date text-center">
                                        <span>24</span>
                                        <p>Now</p>
                                    </div>
                                </div>
                                <div class="blog-cap">
                                    <p>| Physics</p>
                                    <h3><a href="blog_details.html">Footprints in Time is perfect House in Kurashiki</a></h3>
                                    <a href="blog_details.html" class="more-btn">became a member »</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Blog Area End -->
    </main>
    <footer>
        <!--? Footer Start-->
        <div class="footer-area section-bg" data-background="assets/img/gallery/footer_bg.png">
            <div class="container">
                <div class="footer-top footer-padding">
                    <div class="row d-flex justify-content-between">
                        <div class="col-xl-3 col-lg-4 col-md-5 col-sm-8">
                            <div class="single-footer-caption mb-50">
                                <!-- logo -->
                                <div class="footer-logo">
                                    <a href="index.html"><img src="assets/img/logo/logo2_footer.png" alt=""></a>
                                </div>
                                <div class="footer-tittle">
                                    <div class="footer-pera">
                                        <p class="info1">Receive updates and latest news direct from Simply enter.</p>
                                    </div>
                                </div>
                                <div class="footer-number">
                                    <h4><span>+564 </span>7885 3222</h4>
                                    <p>youremail@gmail.com</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-3 col-sm-5">
                            <div class="single-footer-caption mb-50">
                                <div class="footer-tittle">
                                    <h4>Location </h4>
                                    <ul>
                                        <li><a href="#">Advanced</a></li>
                                        <li><a href="#"> Management</a></li>
                                        <li><a href="#">Corporate</a></li>
                                        <li><a href="#">Customer</a></li>
                                        <li><a href="#">Information</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-3 col-sm-5">
                            <div class="single-footer-caption mb-50">
                                <div class="footer-tittle">
                                    <h4>Explore</h4>
                                    <ul>
                                        <li><a href="#">Cookies</a></li>
                                        <li><a href="#">About</a></li>
                                        <li><a href="#">Privacy Policy</a></li>
                                        <li><a href="#">Proparties</a></li>
                                        <li><a href="#">Licenses</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-8">
                            <div class="single-footer-caption mb-50">
                                <div class="footer-tittle">
                                    <h4>Location</h4>
                                    <div class="footer-pera">
                                        <p class="info1">Subscribe now to get daily updates</p>
                                    </div>
                                </div>
                                <!-- Form -->
                                <div class="footer-form">
                                    <div id="mc_embed_signup">
                                        <form target="_blank" action="https://spondonit.us12.list-manage.com/subscribe/post?u=1462626880ade1ac87bd9c93a&amp;id=92a4423d01" method="get" class="subscribe_form relative mail_part" novalidate="true">
                                            <input type="email" name="EMAIL" id="newsletter-form-email" placeholder=" Email Address " class="placeholder hide-on-focus" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Your email address'">
                                            <div class="form-icon">
                                                <button type="submit" name="submit" id="newsletter-submit" class="email_icon newsletter-submit button-contactForm">Send</button>
                                            </div>
                                            <div class="mt-10 info"></div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="footer-bottom">
                    <div class="row d-flex justify-content-between align-items-center">
                        <div class="col-xl-9 col-lg-8">
                            <div class="footer-copy-right">
                                <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                                    Copyright &copy;<script>
                                        document.write(new Date().getFullYear());
                                    </script> All rights reserved | This template is made with <i class="fa fa-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
                                    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4">
                            <!-- Footer Social -->
                            <div class="footer-social f-right">
                                <a href="#"><i class="fab fa-twitter"></i></a>
                                <a href="https://www.facebook.com/sai4ull"><i class="fab fa-facebook-f"></i></a>
                                <a href="#"><i class="fas fa-globe"></i></a>
                                <a href="#"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End-->
    </footer>
    <!-- Scroll Up -->
    <div id="back-top">
        <a title="Go to Top" href="#"> <i class="fas fa-level-up-alt"></i></a>
    </div>

    <!-- JS here -->

    <script src="./assets/js/vendor/modernizr-3.5.0.min.js"></script>
    <!-- Jquery, Popper, Bootstrap -->
    <script src="./assets/js/vendor/jquery-1.12.4.min.js"></script>
    <script src="./assets/js/popper.min.js"></script>
    <script src="./assets/js/bootstrap.min.js"></script>
    <!-- Jquery Mobile Menu -->
    <script src="./assets/js/jquery.slicknav.min.js"></script>

    <!-- Jquery Slick , Owl-Carousel Plugins -->
    <script src="./assets/js/owl.carousel.min.js"></script>
    <script src="./assets/js/slick.min.js"></script>
    <!-- One Page, Animated-HeadLin -->
    <script src="./assets/js/wow.min.js"></script>
    <script src="./assets/js/animated.headline.js"></script>
    <script src="./assets/js/jquery.magnific-popup.js"></script>

    <!-- Date Picker -->
    <script src="./assets/js/gijgo.min.js"></script>
    <!-- Nice-select, sticky -->
    <script src="./assets/js/jquery.nice-select.min.js"></script>
    <script src="./assets/js/jquery.sticky.js"></script>

    <!-- counter , waypoint,Hover Direction -->
    <script src="./assets/js/jquery.counterup.min.js"></script>
    <script src="./assets/js/waypoints.min.js"></script>
    <script src="./assets/js/jquery.countdown.min.js"></script>
    <script src="./assets/js/hover-direction-snake.min.js"></script>

    <!-- contact js -->
    <script src="./assets/js/contact.js"></script>
    <script src="./assets/js/jquery.form.js"></script>
    <script src="./assets/js/jquery.validate.min.js"></script>
    <script src="./assets/js/mail-script.js"></script>
    <script src="./assets/js/jquery.ajaxchimp.min.js"></script>

    <!-- Jquery Plugins, main Jquery -->
    <script src="./assets/js/plugins.js"></script>
    <script src="./assets/js/main.js"></script>

</body>

</html>