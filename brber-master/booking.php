<?php
session_start();
include "../backend/connection.php";

// Pastikan kustomer sudah login
if (!isset($_SESSION['customer_id'])) {
    echo "<script>alert('Silakan login terlebih dahulu untuk melakukan booking.'); window.location.href='login.php';</script>";
    exit;
}

$customer_id = $_SESSION['customer_id'];
$customer_name = isset($_SESSION['customer_name']) ? $_SESSION['customer_name'] : 'Pelanggan';

// Ambil tanggal yang dipilih (default hari ini)
$tanggal_pilih = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');

// Cek kursi terisi (Pending / Disetujui)
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

// Ambil profil barbershop
$query_profile = mysqli_query($koneksi, "SELECT * FROM profile LIMIT 1");
$p = $query_profile ? mysqli_fetch_assoc($query_profile) : ['name' => 'GHD BARBERSHOP', 'logo' => ''];
?>

<!doctype html>
<html class="no-js" lang="id">
<head>
    <meta charset="utf-8">
    <title>Form Booking & Antrian | <?= htmlspecialchars($p['name']); ?></title>
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
            --transition-smooth: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body { 
            background: var(--lux-black) !important; 
            color: var(--lux-text) !important; 
            font-family: 'Montserrat', sans-serif; 
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 { 
            font-family: 'Playfair Display', serif; 
            color: var(--lux-white); 
            letter-spacing: 0.5px;
        }

        /* HEADER */
        .lux-header {
            position: absolute; top: 0; left: 0; right: 0; z-index: 999;
            background: rgba(5, 5, 5, 0.85);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(197, 160, 89, 0.1);
        }

        /* CONTAINER CARD */
        .booking-wrapper {
            padding: 160px 0 100px 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .booking-card { 
            background: var(--lux-surface); 
            border: 1px solid rgba(197, 160, 89, 0.2); 
            padding: 50px; 
            border-radius: 6px; 
            box-shadow: 0 20px 50px rgba(0,0,0,0.8); 
            position: relative;
        }

        .booking-card::before {
            content: ""; position: absolute; top: 0; left: 0; width: 100%; height: 3px;
            background: linear-gradient(90deg, transparent, var(--lux-gold), transparent);
        }

        /* FORM ELEMENTS */
        label {
            font-size: 11px !important;
            letter-spacing: 2px !important;
            font-weight: 600 !important;
            color: var(--lux-gold) !important;
            margin-bottom: 10px !important;
        }

        .form-control { 
            background: #080808 !important; 
            color: var(--lux-white) !important; 
            border: 1px solid rgba(197, 160, 89, 0.25) !important; 
            border-radius: 4px !important; 
            height: 50px;
            padding: 10px 15px;
            font-size: 14px;
            transition: var(--transition-smooth);
        }

        .form-control:focus { 
            border-color: var(--lux-gold) !important; 
            box-shadow: 0 0 15px rgba(197,160,89,0.2) !important; 
            background: #0d0d0d !important;
        }

        /* CHAIR BOX SELECTION */
        .chair-box { 
            background: #0a0a0a; 
            border: 1px solid rgba(255, 255, 255, 0.08); 
            padding: 20px 10px; 
            text-align: center; 
            border-radius: 4px; 
            transition: var(--transition-smooth); 
            cursor: pointer; 
            margin-bottom: 15px; 
        }

        .chair-box:hover:not(.booked) {
            border-color: var(--lux-gold);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(197,160,89,0.15);
        }

        .chair-box.booked { 
            background: rgba(220, 53, 69, 0.08); 
            border-color: rgba(220, 53, 69, 0.3); 
            color: #ff6b6b; 
            cursor: not-allowed; 
            opacity: 0.7;
        }

        .chair-box.selected { 
            background: rgba(197, 160, 89, 0.15); 
            border-color: var(--lux-gold); 
            color: var(--lux-gold); 
            box-shadow: 0 0 20px rgba(197,160,89,0.25);
        }

        /* BUTTON */
        .btn-lux { 
            background: var(--lux-gold); 
            color: var(--lux-black); 
            font-weight: 600; 
            text-transform: uppercase; 
            padding: 16px; 
            border-radius: 4px; 
            border: none; 
            transition: var(--transition-smooth); 
            font-size: 12px; 
            letter-spacing: 2px; 
            width: 100%; 
            margin-top: 10px;
        }

        .btn-lux:hover { 
            background: var(--lux-gold-light); 
            color: #000; 
            box-shadow: 0 0 20px rgba(197,160,89,0.4); 
            transform: translateY(-2px);
        }

        /* WARNING BOX */
        .warning-box { 
            background: rgba(197, 160, 89, 0.04); 
            border: 1px solid rgba(197, 160, 89, 0.2);
            border-left: 4px solid var(--lux-gold); 
            padding: 20px; 
            border-radius: 4px;
            font-size: 13px; 
            color: var(--lux-text); 
            margin-bottom: 30px; 
        }

        /* SCROLLBAR */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--lux-black); }
        ::-webkit-scrollbar-thumb { background: var(--lux-gold); }
    </style>
</head>
<body>

    <!-- HEADER -->
    <header class="lux-header">
        <div style="min-height: 90px; padding: 15px 50px; display: flex; align-items: center; justify-content: space-between;">
            <a href="index.php" style="display: flex; align-items: center; gap: 12px; text-decoration: none;">
                <?php if (!empty($p['logo'])): ?>
                    <img src="../backend/foto/<?= htmlspecialchars($p['logo']); ?>" style="max-height: 48px; object-fit: contain;">
                <?php endif; ?>
                <h3 style="margin:0; font-size: 20px; font-weight: 600;"><?= htmlspecialchars($p['name']); ?></h3>
            </a>
            <div style="display: flex; align-items: center; gap: 20px;">
                <a href="riwayat_booking.php" style="color: var(--lux-gold); font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; text-decoration: none;">
                    <i class="fas fa-ticket-alt mr-1"></i> Bukti Booking
                </a>
                <a href="index.php" style="color: #fff; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; text-decoration: none;">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
        </div>
    </header>

    <div class="booking-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="booking-card">
                        
                        <div class="text-center mb-4">
                            <span style="color: var(--lux-gold); font-size: 11px; letter-spacing: 4px; text-transform: uppercase; display: block; margin-bottom: 8px;">Online Reservation</span>
                            <h2 style="font-size: 34px;">Pilih Jadwal & Kursi Cukur</h2>
                        </div>

                        <!-- HIMBAUAN & KEBIJAKAN TOKO -->
                        <div class="warning-box">
                            <p style="margin-bottom: 8px; color: var(--lux-white); font-weight: 600; font-size: 13px;">
                                <i class="fas fa-info-circle mr-2" style="color: var(--lux-gold);"></i> Kebijakan Toko & Ketentuan Booking:
                            </p>
                            <ul style="margin: 0; padding-left: 18px; line-height: 1.8;">
                                <li>Alur: <b>Booking online $\rightarrow$ Datang ke toko $\rightarrow$ Tunjukkan Bukti Booking ke Kasir</b>.</li>
                                <li>Harap hadir tepat waktu. Keterlambatan lebih dari <b>15 menit</b> akan membuat slot booking hangus dan dialihkan ke antrian lain.</li>
                                <li>Jadwal yang sudah dibooking tidak dapat di-reschedule secara mendadak.</li>
                            </ul>
                        </div>

                        <!-- FORM PILIH TANGGAL -->
                        <form method="GET" action="booking.php" class="mb-4">
                            <div class="form-group">
                                <label>1. Pilih Tanggal Kunjungan</label>
                                <div class="input-group">
                                    <input type="date" name="tanggal" class="form-control" value="<?= htmlspecialchars($tanggal_pilih); ?>" min="<?= date('Y-m-d'); ?>" required>
                                    <div class="input-group-append">
                                        <button type="submit" class="btn" style="background: var(--lux-gold); color: #000; font-weight: 600; border-radius: 0 4px 4px 0; padding: 0 20px; font-size: 12px; letter-spacing: 1px; text-transform: uppercase;">Cek Jadwal</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- FORM UTAMA BOOKING -->
                        <form action="proses_booking.php" method="POST">
                            <input type="hidden" name="booking_date" value="<?= htmlspecialchars($tanggal_pilih); ?>">

                            <!-- PILIH JAM -->
                            <div class="form-group">
                                <label>2. Pilih Jam Kedatangan</label>
                                <select name="booking_time" class="form-control" required>
                                    <option value="">-- Pilih Jam Operasional (09:00 - 21:00) --</option>
                                    <option value="09:00">09:00 WIB</option>
                                    <option value="10:30">10:30 WIB</option>
                                    <option value="13:00">13:00 WIB</option>
                                    <option value="15:00">15:00 WIB</option>
                                    <option value="16:30">16:30 WIB</option>
                                    <option value="19:00">19:00 WIB</option>
                                    <option value="20:30">20:30 WIB</option>
                                </select>
                            </div>

                            <!-- PILIH BARBER -->
                            <div class="form-group">
                                <label>3. Pilih Barber Pilihan (Opsional)</label>
                                <select name="barber_id" class="form-control">
                                    <option value="">-- Rekomendasi Toko (Barber yang Kosong) --</option>
                                    <?php 
                                    $q_barber = mysqli_query($koneksi, "SELECT * FROM barbers");
                                    while($b = mysqli_fetch_assoc($q_barber)): 
                                    ?>
                                        <option value="<?= $b['id_barber']; ?>"><?= htmlspecialchars($b['name']); ?> (Spesialis: <?= htmlspecialchars($b['specialty']); ?>)</option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <!-- PILIH KURSI -->
                            <div class="form-group mb-4">
                                <label style="display: block; margin-bottom: 12px;">4. Pilih Nomor Kursi Pangkas (Tanggal: <span style="color: var(--lux-white);"><?= $tanggal_pilih; ?></span>)</label>
                                <div class="row">
                                    <?php for($i = 1; $i <= 4; $i++): ?>
                                        <div class="col-6 col-md-3">
                                            <?php $is_booked = in_array($i, $kursi_terisi); ?>
                                            <div class="chair-box <?= $is_booked ? 'booked' : ''; ?>" onclick="<?= $is_booked ? 'alert(\'Kursi nomor ' . $i . ' sudah dipesan pada tanggal tersebut!\')' : 'selectChair(' . $i . ', this)'; ?>">
                                                <i class="fas fa-chair fa-2x mb-2" style="color: <?= $is_booked ? '#ff6b6b' : 'var(--lux-gold)'; ?>;"></i>
                                                <div style="font-size: 14px; font-weight: 600; color: var(--lux-white);">Kursi 0<?= $i; ?></div>
                                                <small style="font-size: 10px; font-weight: 500; letter-spacing: 1px;"><?= $is_booked ? 'TERISI / BOOKED' : 'TERSEDIA'; ?></small>
                                            </div>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                                <input type="hidden" name="chair_number" id="selected_chair" required>
                            </div>

                            <button type="submit" class="btn btn-lux">Konfirmasi & Dapatkan Bukti Booking</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/vendor/jquery-1.12.4.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script>
        function selectChair(num, el) {
            $('.chair-box').not('.booked').removeClass('selected');
            $(el).addClass('selected');
            $('#selected_chair').val(num);
        }
    </script>
</body>
</html>