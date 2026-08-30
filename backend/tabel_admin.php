<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login") {
    header("Location: login.php?pesan=belum_login");
    exit;
}

// Batasi hanya Super Admin yang bisa mengakses halaman ini
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'superadmin') {
    echo "<script>alert('Akses Ditolak! Halaman ini khusus untuk Super Admin.'); window.location.href='index.php';</script>";
    exit;
}

include "connection.php";

// Ambil data semua admin
$query_admin = mysqli_query($koneksi, "SELECT * FROM admin ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kelola Akun Admin | GHD Barbershop</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        :root {
            --lux-black: #050505;
            --lux-surface: #121212;
            --lux-gold: #c5a059;
            --lux-white: #f8f8f8;
            --lux-text: #a3a3a3;
        }
        body { 
            background: var(--lux-black) !important; 
            color: var(--lux-text) !important; 
            font-family: 'Montserrat', sans-serif; 
        }
        h1, h2, h3, h4, .card-header h6 { 
            font-family: 'Playfair Display', serif; 
            color: var(--lux-white) !important; 
        }
        .card {
            background: var(--lux-surface) !important;
            border: 1px solid rgba(197, 160, 89, 0.2) !important;
            box-shadow: 0 15px 35px rgba(0,0,0,0.8);
        }
        .card-header {
            background: #080808 !important;
            border-bottom: 1px solid rgba(197, 160, 89, 0.2) !important;
        }
        .table {
            color: var(--lux-text) !important;
            background-color: var(--lux-surface);
        }
        .table th, .table td {
            border-color: rgba(197, 160, 89, 0.15) !important;
            vertical-align: middle;
        }
        .table th {
            color: var(--lux-gold) !important;
            font-family: 'Playfair Display', serif;
            letter-spacing: 1px;
            background: #080808;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(255, 255, 255, 0.02);
        }
        .table-hover tbody tr:hover {
            background-color: rgba(197, 160, 89, 0.05);
            color: var(--lux-white);
        }
        
        /* Tombol Mewah GHD */
        .btn-lux {
            background: var(--lux-gold);
            color: var(--lux-black);
            font-weight: 600;
            text-transform: uppercase;
            padding: 8px 16px;
            border-radius: 4px;
            border: none;
            letter-spacing: 1px;
            font-size: 11px;
            transition: 0.3s;
        }
        .btn-lux:hover {
            background: #e8d3a2;
            color: var(--lux-black);
            box-shadow: 0 0 15px rgba(197,160,89,0.4);
        }
        .btn-danger-lux {
            background: rgba(220, 53, 69, 0.15);
            color: #ff6b6b;
            border: 1px solid #dc3545;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            padding: 5px 12px;
            border-radius: 4px;
            transition: 0.3s;
        }
        .btn-danger-lux:hover {
            background: #dc3545;
            color: #fff;
            box-shadow: 0 0 10px rgba(220,53,69,0.4);
        }
        .btn-info-lux {
            background: rgba(23, 162, 184, 0.15);
            color: #17a2b8;
            border: 1px solid #17a2b8;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            padding: 5px 12px;
            border-radius: 4px;
            transition: 0.3s;
        }
        .btn-info-lux:hover {
            background: #17a2b8;
            color: #fff;
            box-shadow: 0 0 10px rgba(23,162,184,0.4);
        }
    </style>
</head>
<body id="page-top">
    <div id="wrapper">
        <?php include "sidebar.php"; ?>
        <div id="content-wrapper" class="d-flex flex-column" style="background: var(--lux-black);">
            <div id="content">
                <?php include "topbar.php"; ?>

                <div class="container-fluid py-4">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <div>
                            <span style="color: var(--lux-gold); font-size: 11px; letter-spacing: 3px; text-transform: uppercase;">Super Admin Control</span>
                            <h1 class="h3 mb-0" style="color: var(--lux-white); font-family: 'Playfair Display', serif;">Kelola Akun Admin</h1>
                        </div>
                        <a href="tambah_admin.php" class="btn btn-lux"><i class="fas fa-plus mr-1"></i> Tambah Admin Baru</a>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold" style="color: var(--lux-gold);">Daftar Administrator Sistem</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Username</th>
                                            <th>Role / Hak Akses</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; while ($row = mysqli_fetch_assoc($query_admin)) : ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><b class="text-white"><?= htmlspecialchars($row['username']); ?></b></td>
                                            <td>
                                                <?php if (($row['role'] ?? 'admin') == 'superadmin'): ?>
                                                    <span style="background: rgba(197,160,89,0.15); color: var(--lux-gold); border: 1px solid var(--lux-gold); padding: 4px 12px; border-radius: 30px; font-weight: 600; font-size: 11px; text-transform: uppercase;">Super Admin</span>
                                                <?php else: ?>
                                                    <span style="background: rgba(108, 117, 125, 0.15); color: #adb5bd; border: 1px solid #6c757d; padding: 4px 12px; border-radius: 30px; font-weight: 600; font-size: 11px; text-transform: uppercase;">Admin Biasa</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="edit_admin.php?id=<?= $row['id']; ?>" class="btn btn-info-lux mr-1"><i class="fas fa-edit mr-1"></i> Edit</a>
                                                <?php if ($row['id'] != $_SESSION['admin_id']): ?>
                                                    <a href="aksi_admin.php?action=delete&id=<?= $row['id']; ?>" class="btn btn-danger-lux" onclick="return confirm('Yakin ingin menghapus admin ini?')"><i class="fas fa-trash mr-1"></i> Hapus</a>
                                                <?php else: ?>
                                                    <span class="text-muted small font-italic" style="font-size: 11px;">(Akun Anda Saat Ini)</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>
</html>