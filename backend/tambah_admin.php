<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login" || $_SESSION['role'] !== 'superadmin') {
    header("Location: index.php");
    exit;
}
include "connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $role = mysqli_real_escape_string($koneksi, $_POST['role']);

    $query = mysqli_query($koneksi, "INSERT INTO admin (username, password, role) VALUES ('$username', '$password', '$role')");
    if ($query) {
        echo "<script>alert('Admin baru berhasil ditambahkan!'); window.location.href='tabel_admin.php';</script>";
        exit;
    } else {
        $error = "Gagal menambah admin: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Tambah Admin | GHD Barbershop</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
    <div id="wrapper">
        <?php include "sidebar.php"; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include "topbar.php"; ?>
                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">Tambah Akun Admin Baru</h1>
                    <div class="card shadow mb-4 col-lg-6">
                        <div class="card-body">
                            <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                            <form method="POST">
                                <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" name="username" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Hak Akses (Role)</label>
                                    <select name="role" class="form-control">
                                        <option value="admin">Admin Biasa</option>
                                        <option value="superadmin">Super Admin</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan Admin</button>
                                <a href="tabel_admin.php" class="btn btn-secondary">Kembali</a>
                            </form>
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