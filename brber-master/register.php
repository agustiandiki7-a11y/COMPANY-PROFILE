<?php
include "../backend/connection.php";

$error = '';
$success = '';
if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($koneksi, trim($_POST['name']));
    $phone = mysqli_real_escape_string($koneksi, trim($_POST['phone']));
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

    $cek = mysqli_query($koneksi, "SELECT * FROM customers WHERE phone = '$phone'");
    if (mysqli_num_rows($cek) > 0) {
        $error = "Nomor HP sudah terdaftar! Silakan langsung login.";
    } else {
        $query = "INSERT INTO customers (name, phone, password) VALUES ('$name', '$phone', '$password')";
        if (mysqli_query($koneksi, $query)) {
            $success = "Registrasi berhasil! Silakan login.";
        } else {
            $error = "Gagal mendaftar, coba lagi.";
        }
    }
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

function e($value) {
    return htmlspecialchars(isset($value) ? $value : '', ENT_QUOTES, 'UTF-8');
}
?>

<!doctype html>
<html class="no-js" lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Daftar Member | <?php echo e(isset($p['name']) ? $p['name'] : 'Barbershop'); ?></title>
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

        /* AUTH CARD STYLE */
        .auth-hero {
            padding: 180px 0 60px 0;
            background: linear-gradient(180deg, var(--lux-black) 0%, var(--lux-dark) 100%);
            text-align: center;
        }
        .lux-auth-card {
            background: var(--lux-surface);
            padding: 45px;
            border: 1px solid rgba(197, 160, 89, 0.2);
            border-radius: 6px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.7);
            text-align: left;
        }
        .lux-form-label {
            font-family: var(--font-body);
            color: var(--lux-gold-light);
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 12px;
            display: block;
        }
        .lux-form-control {
            background: var(--lux-black);
            border: 1px solid rgba(255,255,255,0.08);
            color: var(--lux-white);
            font-family: var(--font-body);
            font-size: 14px;
            padding: 16px 20px;
            border-radius: 2px;
            width: 100%;
            transition: var(--transition-smooth);
        }
        .lux-form-control:focus {
            background: #080808;
            border-color: var(--lux-gold);
            outline: none;
            box-shadow: 0 0 15px rgba(197, 160, 89, 0.15);
        }
        .btn-auth-submit {
            background: var(--lux-gold) !important;
            color: var(--lux-black) !important;
            border: 1px solid var(--lux-gold) !important;
            padding: 16px !important;
            font-size: 13px !important;
            font-weight: 700 !important;
            letter-spacing: 2px;
            text-transform: uppercase;
            border-radius: 2px;
            width: 100%;
            margin-top: 15px;
            transition: var(--transition-smooth);
        }
        .btn-auth-submit:hover {
            background: transparent !important;
            color: var(--lux-gold) !important;
            box-shadow: 0 0 20px rgba(197, 160, 89, 0.3);
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
        <div class="auth-hero"></div>

        <div class="container pb-130">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="lux-auth-card">
                        <span style="color: var(--lux-gold); font-weight: 600; letter-spacing: 3px; font-size: 11px; text-transform: uppercase; display: block; margin-bottom: 8px;">New Account</span>
                        <h2 style="font-size: 32px; margin-bottom: 25px;">Daftar Member</h2>

                        <?php if($error): ?>
                            <div class="alert alert-danger py-2 mb-3" style="font-size: 13px; background: rgba(220, 53, 69, 0.2); border: 1px solid #dc3545; color: #ff6b6b;">
                                <i class="fas fa-exclamation-circle"></i> <?= $error; ?>
                            </div>
                        <?php endif; ?>
                        <?php if($success): ?>
                            <div class="alert alert-success py-2 mb-3" style="font-size: 13px; background: rgba(40, 167, 69, 0.2); border: 1px solid #28a745; color: #28a745;">
                                <i class="fas fa-check-circle"></i> <?= $success; ?> <a href="login.php" style="color: #fff; text-decoration: underline;">Login sekarang</a>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-4">
                                <label class="lux-form-label">Nama Lengkap</label>
                                <input type="text" name="name" class="lux-form-control" required placeholder="Masukkan nama lengkap Anda">
                            </div>
                            <div class="mb-4">
                                <label class="lux-form-label">No. HP / WhatsApp</label>
                                <input type="text" name="phone" class="lux-form-control" required placeholder="Contoh: 081234567890">
                            </div>
                            <div class="mb-4">
                                <label class="lux-form-label">Password</label>
                                <input type="password" name="password" class="lux-form-control" required placeholder="Buat password akun Anda">
                            </div>
                            <button type="submit" name="register" class="btn btn-auth-submit">Daftar Sekarang</button>
                        </form>

                        <p class="text-center mt-4 mb-0" style="font-size: 13px; color: var(--lux-text);">
                            Sudah punya akun? <a href="login.php" style="color: var(--lux-gold); font-weight: 600;">Login di sini</a>
                        </p>
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