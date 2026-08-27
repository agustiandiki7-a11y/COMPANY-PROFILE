<?php
session_start();
include "connection.php";

// Proses Tambah Layanan
if (isset($_POST['add_service'])) {
    $name = mysqli_real_escape_string($koneksi, $_POST['name']);
    $price = intval($_POST['price']);
    mysqli_query($koneksi, "INSERT INTO services (name, price) VALUES ('$name', $price)");
    header("Location: admin_services.php");
    exit;
}

// Proses Hapus Layanan
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($koneksi, "DELETE FROM services WHERE id_service = $id");
    header("Location: admin_services.php");
    exit;
}

$services = mysqli_query($koneksi, "SELECT * FROM services");
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kelola Layanan | Admin GHD</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { background: #f4f6f9; font-family: 'Montserrat', sans-serif; }
        .sidebar { background: #050505; min-height: 100vh; color: #fff; padding: 20px; position: fixed; width: 250px; }
        .sidebar a { color: #a3a3a3; text-decoration: none; display: block; padding: 12px 15px; border-radius: 4px; margin-bottom: 5px; font-size: 14px; }
        .sidebar a:hover, .sidebar a.active { background: #c5a059; color: #050505; font-weight: 600; }
        .main-content { margin-left: 250px; padding: 40px; }
        .admin-card { background: #fff; border-radius: 6px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

    <div class="sidebar">
        <h3 style="color: #c5a059; font-family: 'Playfair Display', serif; font-size: 20px; margin-bottom: 30px;">GHD Admin</h3>
        <a href="admin_dashboard.php"><i class="fas fa-home mr-2"></i> Dashboard</a>
        <a href="admin_bookings.php"><i class="fas fa-calendar-check mr-2"></i> Kelola Booking</a>
        <a href="admin_services.php" class="active"><i class="fas fa-cut mr-2"></i> Kelola Layanan</a>
        <a href="admin_barbers.php"><i class="fas fa-users mr-2"></i> Kelola Barber</a>
    </div>

    <div class="main-content">
        <h2 style="font-weight: 700; color: #050505; margin-bottom: 30px;">Kelola Menu Layanan & Harga</h2>

        <div class="row">
            <!-- Form Tambah -->
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

            <!-- Tabel Data -->
            <div class="col-md-8">
                <div class="admin-card">
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
                                    <a href="admin_services.php?delete=<?= $s['id_service']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus layanan ini?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</body>
</html>