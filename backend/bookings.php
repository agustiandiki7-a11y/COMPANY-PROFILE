<?php
session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login") {
    header("Location: login.php?pesan=belum_login");
    exit;
}

include "connection.php";

// Proses Aksi Ubah Status (Setujui / Tolak)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action == 'approve') {
        $new_status = 'Disetujui';
    } elseif ($action == 'reject') {
        $new_status = 'Ditolak';
    } else {
        $new_status = 'Pending';
    }

    // Update status di database
    mysqli_query($koneksi, "UPDATE bookings SET status = '$new_status' WHERE id_booking = $id OR id = $id");
    header("Location: bookings.php");
    exit;
}

// Ambil data booking dari database
$query = mysqli_query($koneksi, "
    SELECT b.*, s.name as service_name, s.price, bar.name as barber_name 
    FROM bookings b 
    LEFT JOIN services s ON b.service_id = s.id_service 
    LEFT JOIN barbers bar ON b.barber_id = bar.id_barber 
    ORDER BY b.booking_date DESC
");

include "header.php";
?>
<body id="page-top">
    <div id="wrapper">
        <?php include "sidebar.php"; ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include "topbar.php"; ?>

                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Kelola Data Booking & Kursi</h1>
                    </div>

                    <!-- Tabel Data Booking & Persetujuan -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Pelanggan</th>
                                            <th>WhatsApp</th>
                                            <th>Layanan & Harga</th>
                                            <th>Barber</th>
                                            <th>Kursi</th>
                                            <th>Jadwal</th>
                                            <th>Status</th>
                                            <th>Aksi Admin</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $no = 1;
                                        if(mysqli_num_rows($query) > 0):
                                            while($row = mysqli_fetch_assoc($query)): 
                                                $booking_id = $row['id_booking'] ?? $row['id'] ?? 0;
                                                $status = $row['status'] ?? 'Pending';
                                        ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><strong class="text-warning"><?= htmlspecialchars($row['booking_code']); ?></strong></td>
                                            <td><?= htmlspecialchars($row['customer_name']); ?></td>
                                            <td><?= htmlspecialchars($row['customer_phone']); ?></td>
                                            <td>
                                                <?= htmlspecialchars($row['service_name'] ?? '-'); ?><br>
                                                <small class="text-muted">Rp <?= number_format($row['price'] ?? 0, 0, ',', '.'); ?></small>
                                            </td>
                                            <td><?= htmlspecialchars($row['barber_name'] ?? '-'); ?></td>
                                            <td><strong class="text-success"><?= htmlspecialchars($row['chair_number'] ?? '-'); ?></strong></td>
                                            <td>
                                                <?= date('d M Y', strtotime($row['booking_date'])); ?><br>
                                                <small class="text-muted">Pukul <?= htmlspecialchars($row['booking_time']); ?></small>
                                            </td>
                                            <td>
                                                <?php if($status == 'Disetujui'): ?>
                                                    <span class="badge bg-success text-white" style="background: #28a745; padding: 5px 10px;">Disetujui</span>
                                                <?php elseif($status == 'Ditolak'): ?>
                                                    <span class="badge bg-danger text-white" style="background: #dc3545; padding: 5px 10px;">Ditolak</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning text-dark" style="background: #ffc107; padding: 5px 10px;">Pending</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="bookings.php?action=approve&id=<?= $booking_id; ?>" class="btn btn-success btn-sm" title="Setujui" onclick="return confirm('Setujui pesanan booking ini?')"><i class="fas fa-check"></i></a>
                                                    <a href="bookings.php?action=reject&id=<?= $booking_id; ?>" class="btn btn-danger btn-sm" title="Tolak" onclick="return confirm('Tolak pesanan booking ini?')"><i class="fas fa-times"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endwhile; else: ?>
                                        <tr>
                                            <td colspan="10" class="text-center py-4 text-muted">Belum ada data booking yang masuk.</td>
                                        </tr>
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
