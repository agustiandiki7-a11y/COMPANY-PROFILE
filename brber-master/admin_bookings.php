<?php
session_start();
include "connection.php"; // Sesuaikan jalur koneksi di folder backend

// Ambil semua data booking digabung dengan layanan dan barber
$query = mysqli_query($koneksi, "
    SELECT b.*, s.name as service_name, s.price, bar.name as barber_name 
    FROM bookings b 
    LEFT JOIN services s ON b.service_id = s.id_service 
    LEFT JOIN barbers bar ON b.barber_id = bar.id_barber 
    ORDER BY b.id_booking DESC
");
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kelola Data Booking | Admin GHD Barbershop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css"> <!-- Sesuaikan jalur CSS adminmu jika berbeda -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { background: #f4f6f9; font-family: 'Montserrat', sans-serif; }
        .admin-card { background: #fff; border-radius: 6px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); padding: 25px; margin-top: 30px; }
        .table th { background: #050505; color: #c5a059; font-size: 13px; text-transform: uppercase; }
        .badge-status { padding: 6px 12px; font-size: 11px; font-weight: 600; border-radius: 4px; }
    </style>
</head>
<body>

    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <span style="color: #c5a059; font-size: 11px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;">Admin Panel</span>
                <h2 style="font-weight: 700; color: #050505; margin: 0;">Kelola Data Booking & Kursi</h2>
            </div>
            <a href="index.php" class="btn btn-dark btn-sm"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>
        </div>

        <div class="admin-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Booking</th>
                            <th>Pelanggan</th>
                            <th>No. WhatsApp</th>
                            <th>Layanan & Harga</th>
                            <th>Barber</th>
                            <th>Nomor Kursi</th>
                            <th>Jadwal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        if(mysqli_num_rows($query) > 0):
                            while($row = mysqli_fetch_assoc($query)): 
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><strong style="color: #c5a059;"><?= htmlspecialchars($row['booking_code']); ?></strong></td>
                            <td><?= htmlspecialchars($row['customer_name']); ?></td>
                            <td><?= htmlspecialchars($row['customer_phone']); ?></td>
                            <td>
                                <?= htmlspecialchars($row['service_name'] ?? '-'); ?><br>
                                <small class="text-muted">Rp <?= number_format($row['price'] ?? 0, 0, ',', '.'); ?></small>
                            </td>
                            <td><?= htmlspecialchars($row['barber_name'] ?? '-'); ?></td>
                            <td><strong style="color: #28a745;"><?= htmlspecialchars($row['chair_number'] ?? '-'); ?></strong></td>
                            <td>
                                <?= date('d M Y', strtotime($row['booking_date'])); ?><br>
                                <small class="text-muted">Pukul <?= htmlspecialchars($row['booking_time']); ?></small>
                            </td>
                            <td>
                                <span class="badge bg-success badge-status"><?= htmlspecialchars($row['status']); ?></span>
                            </td>
                        </tr>
                        <?php 
                            endwhile; 
                        else:
                        ?>
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">Belum ada data booking yang masuk.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>