<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login") {
    header("Location: login.php?pesan=belum_login");
    exit;
}

include "connection.php";

// Otomatis ubah ke completed jika tanggal sudah lewat dan status approved
$tanggal_hari_ini = date('Y-m-d');
mysqli_query($koneksi, "UPDATE bookings SET status = 'completed' WHERE booking_date < '$tanggal_hari_ini' AND status = 'approved'");

// Filter & Pencarian
$keyword = isset($_GET['cari']) ? mysqli_real_escape_string($koneksi, $_GET['cari']) : '';
$filter_status = isset($_GET['status']) ? mysqli_real_escape_string($koneksi, $_GET['status']) : '';

$where_clause = "WHERE 1=1";
if (!empty($keyword)) {
    $where_clause .= " AND (b.customer_name LIKE '%$keyword%' OR b.booking_code LIKE '%$keyword%' OR b.customer_phone LIKE '%$keyword%')";
}
if (!empty($filter_status)) {
    $where_clause .= " AND b.status = '$filter_status'";
}

$query = mysqli_query($koneksi, "
    SELECT b.*, s.name as service_name, s.price, bar.name as barber_name 
    FROM bookings b 
    LEFT JOIN services s ON b.service_id = s.id_service 
    LEFT JOIN barbers bar ON b.barber_id = bar.id_barber 
    $where_clause 
    ORDER BY b.booking_date DESC
");

include "header.php";
?>
<body id="page-top">
    <style>
        :root { --lux-gold: #c5a059; --lux-dark: #1a1a1a; }
        .card-header-luxury { background: linear-gradient(135deg, #050505 0%, #1a1a1a 100%) !important; border-bottom: 2px solid var(--lux-gold) !important; padding: 20px 25px; }
        .table-luxury th { background-color: #0f0f0f !important; color: #c5a059 !important; font-size: 11px; letter-spacing: 1.2px; text-transform: uppercase; border: none !important; vertical-align: middle !important; padding: 15px 12px !important; }
        .table-luxury td { vertical-align: middle !important; font-size: 13px; padding: 14px 12px !important; color: #333; }
        .table-hover tbody tr:hover { background-color: rgba(197, 160, 89, 0.05); }
    </style>

    <div id="wrapper">
        <?php include "sidebar.php"; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include "topbar.php"; ?>
                <div class="container-fluid px-4 py-4">

                    <?php if(isset($_GET['pesan'])): ?>
                        <?php if($_GET['pesan'] == 'sukses'): ?>
                            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                                <strong>Berhasil!</strong> Status pesanan berhasil diperbarui dan kursi disesuaikan.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                        <?php elseif($_GET['pesan'] == 'dihapus'): ?>
                            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                                <strong>Terhapus!</strong> Data booking dihapus, kursi otomatis kembali tersedia di frontend.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <div>
                            <span style="color: #c5a059; font-size: 11px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;">Manajemen Admin Backend</span>
                            <h1 class="h3 mb-0 text-gray-800 font-weight-bold" style="font-family: 'Playfair Display', serif;">Kelola Data Booking & Ketersediaan Kursi</h1>
                        </div>
                        <a href="aksi_booking.php?action=hapus_semua" class="btn btn-danger btn-sm shadow-sm" onclick="return confirm('PERINGATAN: Hapus SEMUA data booking?')">
                            <i class="fas fa-trash-alt mr-1"></i> Kosongkan Semua Data
                        </a>
                    </div>

                    <!-- Form Pencarian & Filter -->
                    <div class="card shadow-sm border-0 mb-3" style="border-radius: 8px;">
                        <div class="card-body">
                            <form method="GET" action="" class="form-row align-items-center">
                                <div class="col-md-5 mb-2 mb-md-0">
                                    <div class="input-group">
                                        <input type="text" name="cari" class="form-control" placeholder="Cari nama, kode booking, No. HP..." value="<?= htmlspecialchars($keyword); ?>">
                                        <div class="input-group-append">
                                            <button class="btn btn-dark" type="submit" style="background: #1a1a1a;"><i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2 mb-md-0">
                                    <select name="status" class="form-control" onchange="this.form.submit()">
                                        <option value="">-- Filter Semua Status --</option>
                                        <option value="pending" <?= ($filter_status == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                        <option value="approved" <?= ($filter_status == 'approved') ? 'selected' : ''; ?>>Approved</option>
                                        <option value="rejected" <?= ($filter_status == 'rejected') ? 'selected' : ''; ?>>Rejected</option>
                                        <option value="completed" <?= ($filter_status == 'completed') ? 'selected' : ''; ?>>Completed</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <a href="tabel_bookings.php" class="btn btn-outline-secondary btn-block"><i class="fas fa-sync-alt mr-1"></i> Reset Filter</a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Tabel -->
                    <div class="card shadow-sm border-0 mb-4" style="border-radius: 8px; overflow: hidden;">
                        <div class="card-header card-header-luxury d-flex align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-clipboard-list mr-2" style="color: #c5a059;"></i> Daftar Validasi Pesanan Masuk (Mengatur Ketersediaan Frontend)</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-luxury mb-0" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th>Kode Booking</th>
                                            <th>Pelanggan</th>
                                            <th>WhatsApp</th>
                                            <th>Layanan</th>
                                            <th>Barber</th>
                                            <th>Kursi</th>
                                            <th>Jadwal</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Aksi (Kontrol Backend)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $no = 1;
                                        if($query && mysqli_num_rows($query) > 0):
                                            while($row = mysqli_fetch_assoc($query)): 
                                                $booking_id = $row['id']; 
                                                $status = strtolower(trim($row['status'] ?? 'pending'));
                                        ?>
                                        <tr>
                                            <td class="text-center font-weight-bold text-muted"><?= $no++; ?></td>
                                            <td><strong style="color: #b8860b; font-family: monospace;"><?= htmlspecialchars($row['booking_code'] ?? '-'); ?></strong></td>
                                            <td><span class="font-weight-bold text-dark"><?= htmlspecialchars($row['customer_name'] ?? '-'); ?></span></td>
                                            <td><a href="https://wa.me/<?= preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $row['customer_phone'] ?? '')); ?>" target="_blank" class="text-success"><i class="fab fa-whatsapp mr-1"></i><?= htmlspecialchars($row['customer_phone'] ?? '-'); ?></a></td>
                                            <td>
                                                <span class="font-weight-bold text-dark"><?= htmlspecialchars($row['service_name'] ?? '-'); ?></span><br>
                                                <small class="text-muted">Rp <?= number_format($row['price'] ?? 0, 0, ',', '.'); ?></small>
                                            </td>
                                            <td><span class="badge badge-light border px-2 py-1"><?= htmlspecialchars($row['barber_name'] ?? '-'); ?></span></td>
                                            <td><strong style="color: #2e7d32; background: #e8f5e9; padding: 4px 8px; border-radius: 4px;"><?= htmlspecialchars($row['chair_number'] ?? '-'); ?></strong></td>
                                            <td>
                                                <div class="font-weight-bold text-dark"><?= isset($row['booking_date']) ? date('d M Y', strtotime($row['booking_date'])) : '-'; ?></div>
                                                <small class="text-muted"><i class="far fa-clock mr-1"></i>Pukul <?= htmlspecialchars($row['booking_time'] ?? '-'); ?></small>
                                            </td>
                                            <td class="text-center">
                                                <?php if($status == 'approved'): ?>
                                                    <span class="badge px-3 py-2" style="background: rgba(40, 167, 69, 0.15); color: #28a745; font-weight: 600; border-radius: 30px;"><i class="fas fa-check-circle mr-1"></i> Approved</span>
                                                <?php elseif($status == 'rejected'): ?>
                                                    <span class="badge px-3 py-2" style="background: rgba(220, 53, 69, 0.15); color: #dc3545; font-weight: 600; border-radius: 30px;"><i class="fas fa-times-circle mr-1"></i> Rejected</span>
                                                <?php elseif($status == 'completed'): ?>
                                                    <span class="badge px-3 py-2" style="background: rgba(23, 162, 184, 0.15); color: #117a8b; font-weight: 600; border-radius: 30px;"><i class="fas fa-flag-checkered mr-1"></i> Completed</span>
                                                <?php else: ?>
                                                    <span class="badge px-3 py-2" style="background: rgba(255, 193, 7, 0.15); color: #b38600; font-weight: 600; border-radius: 30px;"><i class="fas fa-hourglass-half mr-1"></i> Pending</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-dark dropdown-toggle" type="button" data-toggle="dropdown" style="background: #1a1a1a; border-color: #c5a059;">Kelola Aksi</button>
                                                    <div class="dropdown-menu shadow">
                                                        <h6 class="dropdown-header text-uppercase font-weight-bold" style="font-size: 10px; color: #c5a059;">Atur Status Backend:</h6>
                                                        <a class="dropdown-item text-success font-weight-bold" href="aksi_booking.php?action=approve&id=<?= $booking_id; ?>"><i class="fas fa-check mr-2"></i>Setujui (Approved)</a>
                                                        <a class="dropdown-item text-danger font-weight-bold" href="aksi_booking.php?action=reject&id=<?= $booking_id; ?>"><i class="fas fa-times mr-2"></i>Tolak (Rejected)</a>
                                                        <a class="dropdown-item text-info font-weight-bold" href="aksi_booking.php?action=finish&id=<?= $booking_id; ?>"><i class="fas fa-flag-checkered mr-2"></i>Selesai (Completed)</a>
                                                        <a class="dropdown-item text-warning font-weight-bold" href="aksi_booking.php?action=pending&id=<?= $booking_id; ?>"><i class="fas fa-hourglass-half mr-2"></i>Set Pending</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item text-danger font-weight-bold" href="aksi_booking.php?action=delete&id=<?= $booking_id; ?>" onclick="return confirm('Hapus data ini? Kursi akan kembali tersedia di frontend.')"><i class="fas fa-trash mr-2"></i>Hapus Data</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endwhile; else: ?>
                                        <tr><td colspan="10" class="text-center py-5">Belum ada data pesanan booking.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include "footer.php"; ?>
        </div>
    </div>
    <?php include "buttom.php"; ?>
</body>
</html>