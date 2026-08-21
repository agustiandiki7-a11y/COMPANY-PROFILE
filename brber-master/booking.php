<?php
// Sesuaikan jalur koneksi database Anda jika sebelumnya error (misal: "connection.php" atau "backend/connection.php")
include "../backend/connection.php";

$services = mysqli_query($koneksi, "SELECT * FROM services");
$barbers = mysqli_query($koneksi, "SELECT * FROM barbers");
?>
<!doctype html>
<html class="no-js" lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Booking - GHD Barbershop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- CSS Template bawaan Brber -->
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
<body style="background: #0d0d0d; color: #fff;">

    <!-- Header Start -->
    <header>
        <div class="header-area header-transparent pt-20">
            <div class="main-header header-sticky">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <!-- Logo -->
                        <div class="col-xl-2 col-lg-2 col-md-1">
                            <div class="logo">
                                <a href="index.php"><img src="assets/img/logo/ChatGPT Image 20 Agu 2026, 08.49.26.png   " alt="Logo"></a>
                            </div>
                        </div>
                        <div class="col-xl-10 col-lg-10 col-md-10">
                            <div class="menu-main d-flex align-items-center justify-content-end">
                                <div class="main-menu f-right d-none d-lg-block">
                                    <nav>
                                        <ul id="navigation">
                                            <li><a href="index.php">Home</a></li>
                                            <li><a href="about.php">About</a></li>
                                            <li><a href="services.php">Services</a></li>
                                            <li><a href="portfolio.php">Portfolio</a></li>
                                            <li><a href="contact.php">Contact</a></li>
                                        </ul>
                                    </nav>
                                </div>
                                <div class="header-right-btn f-right d-none d-lg-block ml-30">
                                    <a href="booking.php" class="btn header-btn">Booking</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Header End -->

    <main>
        <!-- Hero Title Section -->
        <div class="slider-area" style="padding: 140px 0 40px 0; background: #111; text-align: center;">
            <div class="container">
                <h2 style="color: #fff; font-weight: 800;">ONLINE BOOKING</h2>
                <p style="color: #a7a7a7;">Silakan isi data diri, pilih layanan, dan barber pilihan Anda.</p>
            </div>
        </div>

        <!-- Form Booking Section -->
        <div class="container" style="padding: 50px 0 80px 0;">
            <div class="row justify-content-center">
                <div class="col-lg-8" style="background: #171717; padding: 40px; border-radius: 12px; border: 1px solid #2b2b2b;">
                    
                    <form action="booking_process.php" method="POST">
                        <div class="form-group mb-3" style="margin-bottom: 20px;">
                            <label style="font-weight: 600; color: #fff;">Nama Lengkap</label>
                            <input type="text" name="customer_name" class="form-control" required placeholder="Masukkan nama Anda" style="background: #0f0f0f; border: 1px solid #3a3a3a; color: #fff; padding: 12px; width: 100%; border-radius: 8px;">
                        </div>

                        <div class="form-group mb-3" style="margin-bottom: 20px;">
                            <label style="font-weight: 600; color: #fff;">No. HP / WhatsApp</label>
                            <input type="text" name="customer_phone" class="form-control" required placeholder="Contoh: 081234567890" style="background: #0f0f0f; border: 1px solid #3a3a3a; color: #fff; padding: 12px; width: 100%; border-radius: 8px;">
                        </div>

                        <div class="row" style="display: flex; gap: 20px; margin-bottom: 20px;">
                            <div class="col-md-6" style="flex: 1;">
                                <label style="font-weight: 600; color: #fff;">Pilih Layanan</label>
                                <select name="service_id" class="form-control" required style="background: #0f0f0f; border: 1px solid #3a3a3a; color: #fff; padding: 12px; width: 100%; border-radius: 8px; height: 48px;">
                                    <option value="">-- Pilih Layanan --</option>
                                    <?php while ($s = mysqli_fetch_assoc($services)) : ?>
                                        <option value="<?= $s['id_service']; ?>">
                                            <?= htmlspecialchars($s['name']); ?> - Rp <?= number_format($s['price'], 0, ',', '.'); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="col-md-6" style="flex: 1;">
                                <label style="font-weight: 600; color: #fff;">Pilih Barber</label>
                                <select name="barber_id" class="form-control" required style="background: #0f0f0f; border: 1px solid #3a3a3a; color: #fff; padding: 12px; width: 100%; border-radius: 8px; height: 48px;">
                                    <option value="">-- Pilih Barber --</option>
                                    <?php while ($b = mysqli_fetch_assoc($barbers)) : ?>
                                        <option value="<?= $b['id_barber']; ?>">
                                            <?= htmlspecialchars($b['name']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row" style="display: flex; gap: 20px; margin-bottom: 20px;">
                            <div class="col-md-6" style="flex: 1;">
                                <label style="font-weight: 600; color: #fff;">Tanggal Booking</label>
                                <input type="date" name="booking_date" class="form-control" min="<?= date('Y-m-d'); ?>" required style="background: #0f0f0f; border: 1px solid #3a3a3a; color: #fff; padding: 12px; width: 100%; border-radius: 8px;">
                            </div>

                            <div class="col-md-6" style="flex: 1;">
                                <label style="font-weight: 600; color: #fff;">Jam Kunjungan</label>
                                <input type="time" name="booking_time" class="form-control" required style="background: #0f0f0f; border: 1px solid #3a3a3a; color: #fff; padding: 12px; width: 100%; border-radius: 8px;">
                            </div>
                        </div>

                        <button type="submit" name="submit_booking" class="btn btn-primary" style="width: 100%; padding: 14px; margin-top: 15px; background: #f0b90b; border: none; color: #111; font-weight: bold; border-radius: 8px; cursor: pointer;">Proses Booking</button>
                    </form>

                </div>
            </div>
        </div>
    </main>

    <footer style="text-align: center; padding: 30px; color: #a7a7a7; border-top: 1px solid #2b2b2b;">
        © <?= date('Y'); ?> GHD Barbershop
    </footer>

    <!-- JS Files -->
    <script src="assets/js/vendor/modernizr-3.5.0.min.js"></script>
    <script src="assets/js/vendor/jquery-1.12.4.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.slicknav.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>