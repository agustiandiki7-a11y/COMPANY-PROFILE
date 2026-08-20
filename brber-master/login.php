<?php
session_start();
include "connection.php"; // Sesuaikan jika path file koneksimu berbeda

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Cek data ke tabel `admin` di database[cite: 1]
    $stmt = mysqli_prepare($koneksi, "SELECT * FROM admin WHERE username = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $admin = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    // Verifikasi username dan password (sesuai data default sql: admin / admin123)[cite: 1]
    if ($admin && $password === $admin['password']) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $admin['username'];
        
        // Jika benar, arahkan ke halaman kelola admin bookings
        header("Location: admin_bookings.php");
        exit();
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - GHD Barbershop</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body style="background: #0d0d0d; color: #fff; height: 100vh; display: flex; align-items: center; justify-content: center;">
    <div class="container">
        <div class="row justify-content-center">
            <!-- Kotak Login Card dengan tema dark-mode (#171717) -->
            <div class="col-md-5" style="background: #171717; padding: 40px; border-radius: 14px; border: 1px solid #2b2b2b; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                
                <!-- Logo / Brand Header -->
                <div style="text-align: center; margin-bottom: 25px;">
                    <a href="index.php" style="font-size: 24px; font-weight: 900; color: #fff; text-decoration: none; letter-spacing: 1px;">
                        GHD <span style="color: #f0b90b; font-size: 11px; display: block; letter-spacing: 3px;">ADMIN PANEL</span>
                    </a>
                </div>

                <!-- Pesan Error jika Login Gagal -->
                <?php if (!empty($error)) : ?>
                    <div style="background: rgba(220, 53, 69, 0.2); border: 1px solid #dc3545; color: #ff6b6b; padding: 10px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; text-align: center;">
                        <?= $error; ?>
                    </div>
                <?php endif; ?>

                <!-- Form Login -->
                <form action="" method="POST">
                    <div class="form-group mb-3" style="margin-bottom: 20px;">
                        <label style="font-weight: 600; color: #fff; margin-bottom: 8px; display: block; font-size: 14px;">Username</label>
                        <input type="text" name="username" class="form-control" required placeholder="Masukkan username admin" style="background: #0f0f0f; border: 1px solid #3a3a3a; color: #fff; padding: 12px; width: 100%; border-radius: 8px;">
                    </div>

                    <div class="form-group mb-3" style="margin-bottom: 25px;">
                        <label style="font-weight: 600; color: #fff; margin-bottom: 8px; display: block; font-size: 14px;">Password</label>
                        <input type="password" name="password" class="form-control" required placeholder="Masukkan password admin" style="background: #0f0f0f; border: 1px solid #3a3a3a; color: #fff; padding: 12px; width: 100%; border-radius: 8px;">
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; background: #f0b90b; border: none; color: #111; font-weight: 800; border-radius: 8px; cursor: pointer; font-size: 15px; letter-spacing: 1px;">
                        MASUK ADMIN
                    </button>
                </form>

                <!-- Tombol Kembali ke Beranda -->
                <div style="text-align: center; margin-top: 25px;">
                    <a href="index.php" style="color: #a7a7a7; font-size: 13px; text-decoration: none;">&larr; Kembali ke Beranda Website</a>
                </div>

            </div>
        </div>
    </div>
</body>
</html>