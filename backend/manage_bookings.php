<?php
session_start();
include "connection.php";

// Proses aksi admin (Setuju atau Cancel)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action == 'approve') {
        mysqli_query($koneksi, "UPDATE bookings SET status = 'Disetujui' WHERE id = $id");
        echo "<script>alert('Booking berhasil disetujui dan kursi dikunci!'); window.location.href='manage_bookings.php';</script>";
    } elseif ($action == 'cancel') {
        mysqli_query($koneksi, "UPDATE bookings SET status = 'Cancelled' WHERE id = $id");
        echo "<script>alert('Booking telah di-cancel.'); window.location.href='manage_bookings.php';</script>";
    }
}

$query_bookings = mysqli_query($koneksi, "SELECT * FROM bookings ORDER BY id DESC");
$total_booking = mysqli_num_rows($query_bookings);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Booking | GHD Barbershop Admin</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { 
            background-color: #f4f6f9 !important; 
            font-family: 'Montserrat', sans-serif !important; 
            color: #333;
        }
        .main-content { padding: 30px; }
        .card-custom { 
            background: #ffffff; 
            border: none; 
            border-radius: 6px; 
            box-shadow: 0 0 15px rgba(0,0,0,0.05); 
            padding: 25px; 
        }
        .table thead th { 
            background: #343a40; 
            color: #ffffff; 
            font-size: 12px; 
            text-transform: uppercase; 
            border: none; 
            padding: 12px; 
        }
        .table td { 
            vertical-align: middle; 
            font-size: 13px; 
            padding: 12px; 
            border-color: #eee; 
        }
        .badge-pending { background: #ffc107; color: #000; padding: 5px 10px; border-radius: 4px; font-weight: 600; font-size: 11px; }
        .badge-approved { background: #28a745; color: #fff; padding: 5px 10px; border-radius: 4px; font-weight: 600; font-size: 11px; }
        .badge-cancelled { background: #dc3545; color: #fff; padding: 5px 10px; border-radius: 4px; font-weight: 600; font-size: 11px; }
        .btn-sm { font-size: 11px; font-weight: 600; padding: 5px 10px; border-radius: 4px; }
    </style>
</head>
<body>

    <div class="main-content">
        <div class="row mb-4">
            <div class="col-12">
                <h2 style="font-weight: 700; color: #333; font-size: 24px;">Kelola Booking & Konfirmasi</h2>
                <p class="text-muted" style="font-size: 13px;">Pusat validasi jadwal kunjungan kustomer GHD Barbershop.</p>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card card-custom">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Booking</th>
                                    <th>Nama Kustomer</th>
                                    <th>No Telepon</th>
                                    <th>Jadwal Kunjungan</th>
                                    <th>Kursi</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi (Setuju / Cancel)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($query_bookings && mysqli_num_rows($query_bookings) > 0): ?>
                                    <?php $no = 1; while ($row = mysqli_fetch_assoc($query_bookings)): ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><b><?= htmlspecialchars($row['booking_code']); ?></b></td>
                                            <td><?= htmlspecialchars($row['customer_name']); ?></td>
                                            <td><?= htmlspecialchars($row['customer_phone'] ?? '-'); ?></td>
                                            <td><?= htmlspecialchars($row['booking_date']); ?> <br><small class="text-muted"><?= htmlspecialchars($row['booking_time']); ?></small></td>
                                            <td><b><?= htmlspecialchars($row['chair_number']); ?></b></td>
                                            <td>
                                                <?php if ($row['status'] == 'Disetujui'): ?>
                                                    <span class="badge-approved">Disetujui</span>
                                                <?php elseif ($row['status'] == 'Cancelled'): ?>
                                                    <span class="badge-cancelled">Cancelled</span>
                                                <?php else: ?>
                                                    <span class="badge-pending">Pending</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center" style="gap: 5px;">
                                                    <?php if ($row['status'] != 'Disetujui'): ?>
                                                        <a href="manage_bookings.php?action=approve&id=<?= $row['id']; ?>" class="btn btn-success btn-sm" onclick="return confirm('Setujui pesanan ini dan kunci kursinya?')">
                                                            <i class="fas fa-check"></i> Setuju
                                                        </a>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($row['status'] != 'Cancelled'): ?>
                                                        <a href="manage_bookings.php?action=cancel&id=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin cancel booking ini?')">
                                                            <i class="fas fa-times"></i> Cancel
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">Belum ada data booking masuk.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>