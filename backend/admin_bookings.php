<?php
session_start();
include "connection.php"; // Sesuaikan jalur koneksi di folder backend

// Ambil semua data booking digabung dengan layanan dan barber (diurutkan berdasarkan tanggal terbaru)
$query = mysqli_query($koneksi, "
    SELECT b.*, s.name as service_name, s.price, bar.name as barber_name 
    FROM bookings b 
    LEFT JOIN services s ON b.service_id = s.id_service 
    LEFT JOIN barbers bar ON b.barber_id = bar.id_barber 
    ORDER BY b.booking_date DESC
");
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kelola Data Booking | Admin GHD Barbershop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { background: #f4f6f9; font-family: 'Montserrat', sans-serif; }
        .sidebar { background: #050505; min-height: 100vh; color: #fff; padding: 20px; position: fixed; width: 250px; }
        .sidebar a { color: #a3a3a3; text-decoration: none; display: block; padding: 12px 15px; border-radius: 4px; margin-bottom: 5px; font-size: 14px; }
        .sidebar a:hover, .sidebar a.active { background: #c5a059; color: #050505; font-weight: 600; }
        .main-content { margin-left: 250px; padding: 40px; }
        .admin-card { background: #fff; border-radius: 6px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); padding: 25px; }
        .table th { background: #050505; color: #c5a059; font-size: 13px; text-transform: uppercase; }
        .badge-status { padding: 6px 12px; font-size: 11px; font-weight: 600; border-radius: 4px; }
    </style>
</head>
<body>

    <!-- Sidebar Admin -->
    <div class="sidebar">
        <h3 style="color: #c5a059; font-family: 'Playfair Display', serif; font-size: 20px; margin-bottom: 30px;">GHD Admin</h3>
        <a href="admin_dashboard.php"><i class="fas fa-home mr-2"></i> Dashboard</a>
        <a href="admin_bookings.php" class="active"><i class="fas fa-calendar-check mr-2"></i> Kelola Booking</a>
        <a href="admin_services.php"><i class="fas fa-cut mr-2"></i> Kelola Layanan</a>
        <a href="admin_barbers.php"><i class="fas fa-users mr-2"></i> Kelola Barber</a>
        <hr style="border-color: rgba(255,255,255,0.1);">
        <a href="../frontend/index.php" target="_blank"><i class="fas fa-globe mr-2"></i> Lihat Website</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <span style="color: #c5a059; font-size: 11px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;">Admin Panel</span>
                <h2 style="font-weight: 700; color: #050505; margin: 0;">Kelola Data Booking & Kursi</h2>
            </div>
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
                        <?> 
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
                                <span class="badge bg-success badge-status"><?= htmlspecialchars($row['status'] ?? 'Pending'); ?></span>
                            </td>
                        </tr>
                        <?> 
                            endwhile; 
                        else:
                        ?>
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">Belum ada data booking yang masuk.</td>
                        </tr>
                        <?> endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>