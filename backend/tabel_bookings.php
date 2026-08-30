<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login") {
    header("Location: login.php?pesan=belum_login");
    exit;
}

include "connection.php";

$tanggal_hari_ini = date('Y-m-d');
mysqli_query($koneksi, "UPDATE bookings SET status = 'completed' WHERE booking_date < '$tanggal_hari_ini' AND status = 'approved'");

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
        body { background: #f8f9fc; font-family: 'Montserrat', sans-serif; color: #333; }
        .simple-card { background: #fff; border: 1px solid #e3e6f0; border-radius: 8px; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.08); }
        .table-simple th { background: #f1f3f9; color: #4e73df; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; border-bottom: 2px solid #e3e6f0; padding: 12px; }
        .table-simple td { vertical-align: middle; font-size: 13px; padding: 12px; border-top: 1px solid #f8f9fc; }
        .badge-status { padding: 5px 10px; font-size: 11px; font-weight: 600; border-radius: 20px; display: inline-block; }
    </style>

    <div id="wrapper">
        <?php include "sidebar.php"; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include "topbar.php"; ?>
                <div class="container-fluid px-4 py-4">

                    <!-- Header Sederhana -->
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Kelola Booking</h1>
                        <a href="aksi_booking.php?action=hapus_semua" class="btn btn-outline-danger btn-sm" onclick="return confirm('Hapus SEMUA data booking?')">
                            <i class="fas fa-trash-alt mr-1"></i> Kosongkan Data
                        </a>
                    </div>

                    <!-- Filter & Pencarian Simpel -->
                    <div class="simple-card p-3 mb-4">
                        <form method="GET" action="" class="form-row">
                            <div class="col-md-6 mb-2 mb-md-0">
                                <input type="text" name="cari" class="form-control form-control-sm" placeholder="Cari nama, kode, No. HP..." value="<?= htmlspecialchars($keyword); ?>">
                            </div>
                            <div class="col-md-4 mb-2 mb-md-0">
                                <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                                    <option value="">-- Semua Status --</option>
                                    <option value="pending" <?= ($filter_status == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                    <option value="approved" <?= ($filter_status == 'approved') ? 'selected' : ''; ?>>Approved</option>
                                    <option value="rejected" <?= ($filter_status == 'rejected') ? 'selected' : ''; ?>>Rejected</option>
                                    <option value="completed" <?= ($filter_status == 'completed') ? 'selected' : ''; ?>>Completed</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <a href="tabel_bookings.php" class="btn btn-light btn-sm btn-block border">Reset</a>
                            </div>
                        </form>
                    </div>

                    <!-- Tabel Bersih & Minimalis -->
                    <div class="simple-card mb-4 overflow-hidden">
                        <div class="table-responsive">
                            <table class="table table-hover table-simple mb-0" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Kode</th>
                                        <th>Pelanggan</th>
                                        <th>Layanan & Kursi</th>
                                        <th>Jadwal</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Aksi</th>
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
                                        <td class="text-center text-muted"><?= $no++; ?></td>
                                        <td><code class="text-dark font-weight-bold"><?= htmlspecialchars($row['booking_code'] ?? '-'); ?></code></td>
                                        <td>
                                            <strong><?= htmlspecialchars($row['customer_name'] ?? '-'); ?></strong><br>
                                            <a href="https://wa.me/<?= preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $row['customer_phone'] ?? '')); ?>" target="_blank" class="text-success small"><i class="fab fa-whatsapp"></i> <?= htmlspecialchars($row['customer_phone'] ?? '-'); ?></a>
                                        </td>
                                        <td>
                                            <span><?= htmlspecialchars($row['service_name'] ?? '-'); ?></span><br>
                                            <small class="text-muted">Kursi #<?= htmlspecialchars($row['chair_number'] ?? '-'); ?> | Rp <?= number_format($row['price'] ?? 0, 0, ',', '.'); ?></small>
                                        </td>
                                        <td>
                                            <span><?= isset($row['booking_date']) ? date('d/m/Y', strtotime($row['booking_date'])) : '-'; ?></span><br>
                                            <small class="text-muted"><?= htmlspecialchars($row['booking_time'] ?? '-'); ?></small>
                                        </td>
                                        <td class="text-center">
                                            <?php if($status == 'approved'): ?>
                                                <span class="badge-status bg-soft-success text-success" style="background: #e8f5e9;">Approved</span>
                                            <?php elseif($status == 'rejected'): ?>
                                                <span class="badge-status bg-soft-danger text-danger" style="background: #ffebee;">Rejected</span>
                                            <?php elseif($status == 'completed'): ?>
                                                <span class="badge-status bg-soft-info text-info" style="background: #e0f7fa;">Completed</span>
                                            <?php else: ?>
                                                <span class="badge-status bg-soft-warning text-warning" style="background: #fff8e1;">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-toggle="dropdown">Atur</button>
                                                <div class="dropdown-menu dropdown-menu-right shadow-sm">
                                                    <a class="dropdown-item text-success small font-weight-bold" href="aksi_booking.php?action=approve&id=<?= $booking_id; ?>"><i class="fas fa-check mr-2"></i>Setujui</a>
                                                    <a class="dropdown-item text-info small font-weight-bold" href="aksi_booking.php?action=finish&id=<?= $booking_id; ?>"><i class="fas fa-flag-checkered mr-2"></i>Selesai</a>
                                                    <a class="dropdown-item text-danger small font-weight-bold" href="aksi_booking.php?action=reject&id=<?= $booking_id; ?>"><i class="fas fa-times mr-2"></i>Tolak</a>
                                                    <a class="dropdown-item text-warning small font-weight-bold" href="aksi_booking.php?action=pending&id=<?= $booking_id; ?>"><i class="fas fa-hourglass-half mr-2"></i>Pending</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item text-danger small font-weight-bold" href="aksi_booking.php?action=delete&id=<?= $booking_id; ?>" onclick="return confirm('Hapus data ini?')"><i class="fas fa-trash mr-2"></i>Hapus</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; else: ?>
                                    <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada data pesanan booking.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
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