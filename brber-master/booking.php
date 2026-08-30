<?php
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

include "../backend/connection.php"; 

if (!$koneksi) {
    die("Koneksi database gagal.");
}

// Ambil logo untuk favicon dari database
$q_favicon = mysqli_query($koneksi, "SELECT logo FROM profile LIMIT 1");
$row_fav = $q_favicon ? mysqli_fetch_assoc($q_favicon) : [];
$logo_fav = !empty($row_fav['logo']) ? '../backend/foto/' . $row_fav['logo'] : '';

$q_prof = mysqli_query($koneksi, "SELECT * FROM profile LIMIT 1");
$p = $q_prof ? mysqli_fetch_assoc($q_prof) : ['name' => 'GHD BARBERSHOP'];

$q_services = mysqli_query($koneksi, "SELECT * FROM services");
$q_barbers = mysqli_query($koneksi, "SELECT * FROM barbers");

$selected_date = isset($_POST['booking_date']) ? $_POST['booking_date'] : date('Y-m-d');
$selected_time = isset($_POST['booking_time']) ? $_POST['booking_time'] : '';

$kursi_booked = [];
if (!empty($selected_time)) {
    $q_kursi = mysqli_query($koneksi, "
        SELECT chair_number FROM bookings 
        WHERE booking_date = '$selected_date' 
        AND booking_time = '$selected_time' 
        AND status NOT IN ('rejected', 'cancelled', 'Ditolak')
    ");
    while ($rk = mysqli_fetch_assoc($q_kursi)) {
        $kursi_booked[] = trim($rk['chair_number']);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_booking'])) {
    $customer_name  = mysqli_real_escape_string($koneksi, $_POST['customer_name']);
    $customer_phone = mysqli_real_escape_string($koneksi, $_POST['customer_phone']);
    $service_id     = intval($_POST['service_id']);
    $barber_id      = intval($_POST['barber_id']);
    $chair_number   = mysqli_real_escape_string($koneksi, $_POST['chair_number']);
    $booking_date   = mysqli_real_escape_string($koneksi, $_POST['booking_date']);
    $booking_time   = mysqli_real_escape_string($koneksi, $_POST['booking_time']);
    
    $booking_code   = 'GHD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
    $status         = 'Pending';

    $cek_kolom = mysqli_query($koneksi, "SHOW COLUMNS FROM bookings LIKE 'id_booking'");
    $id_col = (mysqli_num_rows($cek_kolom) > 0) ? 'id_booking' : 'id';

    $query_insert = mysqli_query($koneksi, "
        INSERT INTO bookings (booking_code, customer_name, customer_phone, service_id, barber_id, chair_number, booking_date, booking_time, status) 
        VALUES ('$booking_code', '$customer_name', '$customer_phone', $service_id, $barber_id, '$chair_number', '$booking_date', '$booking_time', '$status')
    ");

    if ($query_insert) {
        $last_id = mysqli_insert_id($koneksi);
        echo "<script>window.location.href='payment.php?id=$last_id';</script>";
        exit;
    } else {
        $error = "Gagal memproses booking: " . mysqli_error($koneksi);
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Booking Online | <?= htmlspecialchars($p['name']); ?></title>
    <?php if (!empty($logo_fav)): ?>
    <link rel="icon" type="image/x-icon" href="<?= $logo_fav; ?>">
    <?php endif; ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/fontawesome-all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --lux-black: #050505; --lux-surface: #121212; --lux-gold: #c5a059; --lux-white: #f8f8f8; --lux-text: #a3a3a3; }
        body { background: var(--lux-black) !important; color: var(--lux-text) !important; font-family: 'Montserrat', sans-serif; padding: 40px 0; }
        .card-lux { background: var(--lux-surface); border: 1px solid rgba(197, 160, 89, 0.3); border-radius: 12px; box-shadow: 0 20px 40px rgba(0,0,0,0.9); padding: 30px; }
        h2 { font-family: 'Playfair Display', serif; color: var(--lux-white); }
        .form-control { background: #080808 !important; color: #fff !important; border: 1px solid rgba(197, 160, 89, 0.3) !important; }
        .form-control:focus { border-color: var(--lux-gold) !important; box-shadow: 0 0 10px rgba(197,160,89,0.3); }
        .btn-lux { background: var(--lux-gold); color: var(--lux-black); font-weight: 600; text-transform: uppercase; padding: 12px 20px; border-radius: 4px; border: none; letter-spacing: 1px; font-size: 12px; width: 100%; transition: 0.3s; }
        .btn-lux:hover { background: #e8d3a2; box-shadow: 0 0 15px rgba(197,160,89,0.4); }
        .chair-box { border-radius: 6px; padding: 12px; text-align: center; border: 1px solid rgba(197,160,89,0.3); }
        .chair-box.booked { background: rgba(220, 53, 69, 0.2) !important; border-color: #dc3545 !important; opacity: 0.7; }
        .chair-box.available { background: rgba(40, 167, 69, 0.1); border-color: #28a745; }
        .btn-outline-lux { background: transparent; color: var(--lux-gold); border: 1px solid rgba(197,160,89,0.5); font-weight: 600; text-transform: uppercase; font-size: 11px; padding: 8px 15px; border-radius: 4px; text-decoration: none; transition: 0.3s; display: inline-block; }
        .btn-outline-lux:hover { background: rgba(197,160,89,0.1); color: var(--lux-white); border-color: var(--lux-gold); text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card-lux">
                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                        <div>
                            <span style="color: var(--lux-gold); font-size: 10px; letter-spacing: 3px; text-transform: uppercase;">Reservation System</span>
                            <h2>Form Booking Kursi</h2>
                        </div>
                        <div>
                            <a href="index.php" class="btn-outline-lux"><i class="fas fa-arrow-left mr-1"></i> Kembali ke Beranda</a>
                        </div>
                    </div>

                    <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" style="font-size: 12px; color: var(--lux-gold);">Nama Lengkap</label>
                                <input type="text" name="customer_name" class="form-control" required value="<?= htmlspecialchars($_POST['customer_name'] ?? ''); ?>" placeholder="Nama Anda">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" style="font-size: 12px; color: var(--lux-gold);">Nomor WhatsApp</label>
                                <input type="text" name="customer_phone" class="form-control" required value="<?= htmlspecialchars($_POST['customer_phone'] ?? ''); ?>" placeholder="08xxxxxxxxxx">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" style="font-size: 12px; color: var(--lux-gold);">Pilih Layanan</label>
                                <select name="service_id" class="form-control" required>
                                    <option value="">-- Pilih Layanan Cukur --</option>
                                    <?php 
                                    mysqli_data_seek($q_services, 0);
                                    while($s = mysqli_fetch_assoc($q_services)): 
                                        $sel = (isset($_POST['service_id']) && $_POST['service_id'] == ($s['id_service'] ?? $s['id'])) ? 'selected' : '';
                                    ?>
                                        <option value="<?= $s['id_service'] ?? $s['id']; ?>" <?= $sel; ?>><?= htmlspecialchars($s['name']); ?> (Rp <?= number_format($s['price'], 0, ',', '.'); ?>)</option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" style="font-size: 12px; color: var(--lux-gold);">Pilih Barber</label>
                                <select name="barber_id" class="form-control" required>
                                    <option value="">-- Pilih Barber --</option>
                                    <?php 
                                    mysqli_data_seek($q_barbers, 0);
                                    while($b = mysqli_fetch_assoc($q_barbers)): 
                                        $sel_b = (isset($_POST['barber_id']) && $_POST['barber_id'] == ($b['id_barber'] ?? $b['id'])) ? 'selected' : '';
                                    ?>
                                        <option value="<?= $b['id_barber'] ?? $b['id']; ?>" <?= $sel_b; ?>><?= htmlspecialchars($b['name']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" style="font-size: 12px; color: var(--lux-gold);">Tanggal Kedatangan</label>
                                <input type="date" name="booking_date" class="form-control" value="<?= $selected_date; ?>" required onchange="this.form.submit()">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" style="font-size: 12px; color: var(--lux-gold);">Jam Kedatangan</label>
                                <select name="booking_time" class="form-control" required onchange="this.form.submit()">
                                    <option value="">-- Pilih Jam --</option>
                                    <?php 
                                    $jam_operasional = ['09:00', '10:30', '13:00', '15:00', '17:00', '19:30', '21:00'];
                                    foreach($jam_operasional as $jam):
                                    ?>
                                        <option value="<?= $jam; ?>" <?= ($selected_time == $jam) ? 'selected' : ''; ?>>Pukul <?= $jam; ?> WIB</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label d-block" style="font-size: 12px; color: var(--lux-gold);">Pilih Nomor Kursi</label>
                            <div class="row">
                                <?php 
                                $daftar_kursi = ['Kursi 01', 'Kursi 02', 'Kursi 03', 'Kursi 04'];
                                foreach($daftar_kursi as $kc):
                                    $is_booked = in_array($kc, $kursi_booked);
                                    $checked = (isset($_POST['chair_number']) && $_POST['chair_number'] == $kc) ? 'checked' : '';
                                ?>
                                <div class="col-md-3 col-6 mb-2">
                                    <div class="chair-box <?= $is_booked ? 'booked' : 'available'; ?>">
                                        <input type="radio" name="chair_number" value="<?= $kc; ?>" <?= $is_booked ? 'disabled' : ''; ?> <?= $checked; ?> required>
                                        <span class="d-block mt-1 font-weight-bold" style="color: #fff; font-size: 12px;"><?= $kc; ?></span>
                                        <small class="d-block" style="font-size: 10px; color: <?= $is_booked ? '#ff6b6b' : '#28a745'; ?>;">
                                            <?= $is_booked ? 'Terisi (Full)' : 'Tersedia'; ?>
                                        </small>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <small class="text-muted font-italic" style="font-size: 11px;">* Kursi merah menandakan sudah dipesan/divalidasi admin pada jam yang sama.</small>
                        </div>

                        <button type="submit" name="submit_booking" class="btn-lux">Konfirmasi & Lanjut ke Pembayaran</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>