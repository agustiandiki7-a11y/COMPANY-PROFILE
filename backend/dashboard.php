<?php
session_start();

if (!isset($_SESSION['id_admin'])) {
    header("Location: login.php");
    exit;
}

include "connection.php";

// Ambil halaman yang sedang aktif dari URL (default: dashboard)
$page = $_GET['page'] ?? 'dashboard';

// -----------------------------------------
// 1. PROSES AKSI KELOLA BOOKING (APPROVE / REJECT)
// -----------------------------------------
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

    mysqli_query($koneksi, "UPDATE bookings SET status = '$new_status' WHERE id_booking = $id OR id = $id");
    header("Location: dashboard.php?page=bookings");
    exit;
}

// -----------------------------------------
// 2. PROSES TAMBAH & HAPUS LAYANAN
// -----------------------------------------
if (isset($_POST['add_service'])) {
    $name = mysqli_real_escape_string($koneksi, $_POST['name']);
    $price = intval($_POST['price']);
    mysqli_query($koneksi, "INSERT INTO services (name, price) VALUES ('$name', $price)");
    header("Location: dashboard.php?page=services");
    exit;
}

if (isset($_GET['delete_service'])) {
    $id_serv = intval($_GET['delete_service']);
    mysqli_query($koneksi, "DELETE FROM services WHERE id_service = $id_serv");
    header("Location: dashboard.php?page=services");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - GHD Barbershop</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { background: #f4f6f9; font-family: 'Montserrat', sans-serif; }
        .sidebar { background: #050505; min-height: 100vh; color: #fff; padding: 20px; position: fixed; width: 260px; z-index: 100; }
        .sidebar h3 { color: #c5a059; font-family: 'Playfair Display', serif; font-size: 20px; margin-bottom: 30px; letter-spacing: 1px; }
        .sidebar a { color: #a3a3a3; text-decoration: none; display: block; padding: 12px 15px; border-radius: 4px; margin-bottom: 8px; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: #c5a059; color: #050505; font-weight: 600; }
        .main-content { margin-left: 260px; padding: 30px; }
        .stat-card { background: #fff; border-radius: 6px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-left: 4px solid #c5a059; height: 100%; }
        .admin-card { background: #fff; border-radius: 6px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); padding: 25px; margin-top: 20px; }
        .table th { background: #050505; color: #c5a059; font-size: 12px; text-transform: uppercase; }
        .badge-status { padding: 6px 12px; font-size: 11px; font-weight: 600; border-radius: 4px; }
    </style>
</head>
<body>

    <!-- SIDEBAR MENU UTAMA -->
    <div class="sidebar">
        <h3>GHD Barbershop</h3>
        <a href="dashboard.php?page=dashboard" class="<?= ($page == 'dashboard') ? 'active' : ''; ?>"><i class="fas fa-home mr-2"></i> Dashboard</a>
        <a href="dashboard.php?page=bookings" class="<?= ($page == 'bookings') ? 'active' : ''; ?>"><i class="fas fa-calendar-check mr-2"></i> Kelola Booking</a>
        <a href="dashboard.php?page=services" class="<?= ($page == 'services') ? 'active' : ''; ?>"><i class="fas fa-cut mr-2"></i> Kelola Layanan</a>
        <hr style="border-color: rgba(255,255,255,0.1);">
        <a href="../frontend/index.php" target="_blank"><i class="fas fa-globe mr-2"></i> Lihat Website</a>
        <a href="logout.php" onclick="return confirm('Yakin ingin keluar?')"><i class="fas fa-sign-out-alt mr-2 text-danger"></i> Logout</a>
    </div>

    <!-- KONTEN UTAMA YANG BERUBAH SESUAI MENU -->
    <div class="main-content">
        
        <!-- TOP HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-4 rounded shadow-sm">
            <div>
                <span style="color: #c5a059; font-size: 11px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;">Admin Control Panel</span>
                <h3 style="font-weight: 700; color: #050505; margin: 0;">
                    <?php 
                        if($page == 'bookings') echo 'Kelola & Persetujuan Booking';
                        elseif($page == 'services') echo 'Kelola Menu Layanan & Harga';
                        else echo 'Dashboard Utama';
                    ?>
                </h3>
            </div>
            <div>
                <span class="text-muted" style="font-size: 13px;">Halo, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
            </div>
        </div>

        <!-- KONTEN 1: HALAMAN DASHBOARD UTAMA -->
        <?php if($page == 'dashboard'): 
            $q_book = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM bookings"));
            $q_cust = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM customers"));
            $q_serv = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM services"));
        ?>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="stat-card">
                        <span style="color: #888; font-size: 12px; text-transform: uppercase; font-weight: 600;"><i class="fas fa-calendar-alt text-warning mr-1"></i> Total Pesanan Booking</span>
                        <h2 style="font-size: 32px; font-weight: 700; color: #050505; margin: 10px 0 0 0;"><?= $q_book['total']; ?></h2>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="stat-card">
                        <span style="color: #888; font-size: 12px; text-transform: uppercase; font-weight: 600;"><i class="fas fa-users text-primary mr-1"></i> Pelanggan Terdaftar</span>
                        <h2 style="font-size: 32px; font-weight: 700; color: #050505; margin: 10px 0 0 0;"><?= $q_cust['total']; ?></h2>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="stat-card">
                        <span style="color: #888; font-size: 12px; text-transform: uppercase; font-weight: 600;"><i class="fas fa-cut text-success mr-1"></i> Total Jenis Layanan</span>
                        <h2 style="font-size: 32px; font-weight: 700; color: #050505; margin: 10px 0 0 0;"><?= $q_serv['total']; ?></h2>
                    </div>
                </div>
            </div>

            <div class="admin-card text-center py-5">
                <h4 style="color: #333;">Selamat Datang di Panel Admin GHD Barbershop</h4>
                <p class="text-muted">Gunakan menu di sebelah kiri untuk mengelola data booking, menyetujui jadwal pelanggan, atau mengatur harga layanan.</p>
                <a href="dashboard.php?page=bookings" class="btn btn-dark mt-2" style="background: #050505;">Kelola Booking Sekarang</a>
            </div>
        <?php endif; ?>

        <!-- KONTEN 2: HALAMAN KELOLA BOOKING & PERSETUJUAN -->
        <?php if($page == 'bookings'): 
            $query_book = mysqli_query($koneksi, "
                SELECT b.*, s.name as service_name, s.price, bar.name as barber_name 
                FROM bookings b 
                LEFT JOIN services s ON b.service_id = s.id_service 
                LEFT JOIN barbers bar ON b.barber_id = bar.id_barber 
                ORDER BY b.booking_date DESC
            ");
        ?>
            <div class="admin-card">
                <h4 style="font-weight: 700; color: #050505; margin-bottom: 20px;"><i class="fas fa-clipboard-check text-warning"></i> Daftar Pesanan & Validasi Kursi</h4>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
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
                            if(mysqli_num_rows($query_book) > 0):
                                while($row = mysqli_fetch_assoc($query_book)): 
                                    $booking_id = $row['id_booking'] ?? $row['id'] ?? 0;
                                    $status = $row['status'] ?? 'Pending';
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
                                    <?php if($status == 'Disetujui'): ?>
                                        <span class="badge bg-success badge-status" style="background-color: #28a745; color: #fff;">Disetujui</span>
                                    <?php elseif($status == 'Ditolak'): ?>
                                        <span class="badge bg-danger badge-status" style="background-color: #dc3545; color: #fff;">Ditolak</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning badge-status" style="background-color: #ffc107; color: #000;">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="dashboard.php?page=bookings&action=approve&id=<?= $booking_id; ?>" class="btn btn-success btn-sm" title="Setujui" onclick="return confirm('Setujui pesanan ini?')"><i class="fas fa-check"></i></a>
                                        <a href="dashboard.php?page=bookings&action=reject&id=<?= $booking_id; ?>" class="btn btn-danger btn-sm" title="Tolak" onclick="return confirm('Tolak pesanan ini?')"><i class="fas fa-times"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; else: ?>
                            <tr><td colspan="10" class="text-center py-4 text-muted">Belum ada data booking.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

        <!-- KONTEN 3: HALAMAN KELOLA LAYANAN -->
        <?php if($page == 'services'): 
            $services = mysqli_query($koneksi, "SELECT * FROM services");
        ?>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="admin-card">
                        <h5 style="font-weight: 700; margin-bottom: 20px;">Tambah Layanan Baru</h5>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label font-weight-bold" style="font-size: 12px;">Nama Layanan</label>
                                <input type="text" name="name" class="form-control" required placeholder="Contoh: Haircut & Styling">
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-weight-bold" style="font-size: 12px;">Harga (Rp)</label>
                                <input type="number" name="price" class="form-control" required placeholder="Contoh: 45000">
                            </div>
                            <button type="submit" name="add_service" class="btn btn-dark w-100" style="background: #050505;">Simpan Layanan</button>
                        </form>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="admin-card">
                        <h4 style="font-weight: 700; color: #050505; margin-bottom: 20px;"><i class="fas fa-cut text-warning"></i> Daftar Menu Layanan</h4>
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Layanan</th>
                                    <th>Harga</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no=1; while($s = mysqli_fetch_assoc($services)): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><strong><?= htmlspecialchars($s['name']); ?></strong></td>
                                    <td style="color: #c5a059; font-weight: 600;">Rp <?= number_format($s['price'], 0, ',', '.'); ?></td>
                                    <td>
                                        <a href="dashboard.php?page=services&delete_service=<?= $s['id_service']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus layanan ini?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>

</body>
</html> 