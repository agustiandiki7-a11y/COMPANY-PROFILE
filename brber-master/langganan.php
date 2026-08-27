<?php
session_start();
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit;
}

include "../backend/connection.php";

function e($value) {
    return htmlspecialchars(isset($value) ? $value : '', ENT_QUOTES, 'UTF-8');
}

// Ambil profil
$query_profile = mysqli_query($koneksi, "SELECT * FROM profile LIMIT 1");
$p = $query_profile ? mysqli_fetch_assoc($query_profile) : ['name' => 'GHB BARBERSHOP', 'logo' => ''];
?>

<!doctype html>
<html class="no-js" lang="id">
<head>
    <meta charset="utf-8">
    <title>Langganan Member | <?php echo e($p['name']); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="icon" type="image/x-icon" href="../backend/foto/logo.ico">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        :root {
            --lux-black: #050505;
            --lux-dark: #0a0a0a;
            --lux-surface: #121212;
            --lux-gold: #c5a059;
            --lux-gold-light: #e8d3a2;
            --lux-white: #f8f8f8;
            --lux-text: #a3a3a3;
        }
        body { background: var(--lux-black) !important; color: var(--lux-text) !important; font-family: 'Montserrat', sans-serif !important; }
        h1, h2, h3, h4 { font-family: 'Playfair Display', serif !important; color: var(--lux-white) !important; }
        
        .hero-section { padding: 180px 0 60px 0; text-align: center; background: linear-gradient(180deg, var(--lux-black) 0%, var(--lux-dark) 100%); }
        .hero-section span { color: var(--lux-gold); font-size: 12px; letter-spacing: 3px; text-transform: uppercase; display: block; margin-bottom: 10px; }

        .price-card {
            background: var(--lux-surface);
            border: 1px solid rgba(197, 160, 89, 0.2);
            border-radius: 6px;
            padding: 40px 30px;
            text-align: center;
            transition: 0.4s;
            position: relative;
            margin-bottom: 30px;
        }
        .price-card:hover {
            border-color: var(--lux-gold);
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.8);
        }
        .price-card.featured {
            border-color: var(--lux-gold);
            background: linear-gradient(180deg, #16140f 0%, var(--lux-surface) 100%);
        }
        .price-card.featured::after {
            content: 'POPULAR';
            position: absolute; top: -12px; left: 50%; transform: translateX(-50%);
            background: var(--lux-gold); color: #000; font-size: 10px; font-weight: bold; padding: 4px 12px; letter-spacing: 1px;
        }
        .price-amount { font-size: 32px; font-weight: bold; color: var(--lux-gold); margin: 20px 0; font-family: 'Playfair Display', serif; }
        .feature-list { list-style: none; padding: 0; margin: 25px 0; text-align: left; }
        .feature-list li { padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.05); font-size: 13px; }
        .feature-list li i { color: var(--lux-gold); margin-right: 10px; }

        .btn-sub {
            background: var(--lux-gold); color: #000; font-weight: bold; width: 100%; padding: 14px;
            text-transform: uppercase; border-radius: 4px; border: none; letter-spacing: 1px; transition: 0.3s;
        }
        .btn-sub:hover { background: var(--lux-gold-light); box-shadow: 0 0 15px rgba(197, 160, 89, 0.4); }
    </style>
</head>
<body>

    <!-- Header Sederhana / Kembali -->
    <div style="position: absolute; top: 20px; left: 30px; z-index: 10;">
        <a href="index.php" style="color: var(--lux-gold); text-decoration: none; font-size: 13px; font-weight: 600;">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
        </a>
    </div>

    <div class="hero-section">
        <div class="container">
            <span>Eksklusif Member</span>
            <h2>Paket Langganan Barbershop</h2>
            <p class="text-muted" style="max-width: 600px; margin: 10px auto 0 auto; font-size: 14px;">
                Nikmati potongan harga spesial, prioritas antrian utama, dan layanan premium setiap bulan tanpa antri lama.
            </p>
        </div>
    </div>

    <div class="container pb-100">
        <div class="row justify-content-center">
            
            <!-- Paket 1 -->
            <div class="col-lg-4 col-md-6">
                <div class="price-card">
                    <h4>Silver Gentleman</h4>
                    <p class="text-muted" style="font-size: 12px;">Cocok untuk perawatan rutin bulanan</p>
                    <div class="price-amount">Rp 150.000</div>
                    <ul class="feature-list">
                        <li><i class="fas fa-check"></i> 2x Haircut & Styling</li>
                        <li><i class="fas fa-check"></i> 1x Wash & Massage</li>
                        <li><i class="fas fa-check"></i> Berlaku selama 30 Hari</li>
                        <li class="text-muted"><i class="fas fa-times text-danger"></i> Free Pomade Premium</li>
                    </ul>
                    <form action="langganan_process.php" method="POST">
                        <input type="hidden" name="package_name" value="Silver Gentleman">
                        <input type="hidden" name="price" value="150000">
                        <input type="hidden" name="duration" value="30 Hari">
                        <button type="submit" name="subscribe" class="btn-sub">Pilih Paket Silver</button>
                    </form>
                </div>
            </div>

            <!-- Paket 2 (Featured) -->
            <div class="col-lg-4 col-md-6">
                <div class="price-card featured">
                    <h4>Gold VIP Unlimited</h4>
                    <p class="text-muted" style="font-size: 12px;">Pilihan terbaik bebas potong kapan saja</p>
                    <div class="price-amount">Rp 350.000</div>
                    <ul class="feature-list">
                        <li><i class="fas fa-check"></i> Bebas Cukur Sebulan Penuh</li>
                        <li><i class="fas fa-check"></i> Free Hair Wash & Tonic</li>
                        <li><i class="fas fa-check"></i> 1x Free Pomade Original</li>
                        <li><i class="fas fa-check"></i> Prioritas Booking Kursi VIP</li>
                    </ul>
                    <form action="langganan_process.php" method="POST">
                        <input type="hidden" name="package_name" value="Gold VIP Unlimited">
                        <input type="hidden" name="price" value="350000">
                        <input type="hidden" name="duration" value="30 Hari">
                        <button type="submit" name="subscribe" class="btn-sub">Pilih Paket Gold</button>
                    </form>
                </div>
            </div>

            <!-- Paket 3 -->
            <div class="col-lg-4 col-md-6">
                <div class="price-card">
                    <h4>Platinum Executive</h4>
                    <p class="text-muted" style="font-size: 12px;">Paket lengkap kelas atas</p>
                    <div class="price-amount">Rp 500.000</div>
                    <ul class="feature-list">
                        <li><i class="fas fa-check"></i> Unlimited Haircut & Shaving</li>
                        <li><i class="fas fa-check"></i> Free Hair Treatment & Spa</li>
                        <li><i class="fas fa-check"></i> 2x Free Pomade Premium</li>
                        <li><i class="fas fa-check"></i> Akses VIP Lounge & Barber Pribadi</li>
                    </ul>
                    <form action="langganan_process.php" method="POST">
                        <input type="hidden" name="package_name" value="Platinum Executive">
                        <input type="hidden" name="price" value="500000">
                        <input type="hidden" name="duration" value="30 Hari">
                        <button type="submit" name="subscribe" class="btn-sub">Pilih Paket Platinum</button>
                    </form>
                </div>
            </div>

        </div>
    </div>

</body>
</html>