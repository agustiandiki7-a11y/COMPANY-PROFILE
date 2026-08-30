<?php
session_start();
include "../backend/connection.php";

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = isset($_POST['name']) ? mysqli_real_escape_string($koneksi, $_POST['name']) : '';
    $email    = isset($_POST['email']) ? mysqli_real_escape_string($koneksi, $_POST['email']) : '';
    $phone    = isset($_POST['phone']) ? mysqli_real_escape_string($koneksi, $_POST['phone']) : '';
    $password = isset($_POST['password']) ? mysqli_real_escape_string($koneksi, $_POST['password']) : '';

    // Validasi sederhana agar tidak kosong
    if (empty($name) || empty($email) || empty($password)) {
        $error = "Nama, email, dan password wajib diisi!";
    } else {
        // Cek apakah email sudah terdaftar sebelumnya
        $cek_email = mysqli_query($koneksi, "SELECT * FROM customers WHERE email = '$email'");
        if (mysqli_num_rows($cek_email) > 0) {
            $error = "Email sudah terdaftar! Silakan gunakan email lain atau langsung login.";
        } else {
            // Masukkan data kustomer baru ke database
            $query = "INSERT INTO customers (name, email, phone, password) VALUES ('$name', '$email', '$phone', '$password')";
            
            if (mysqli_query($koneksi, $query)) {
                echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location.href='login.php';</script>";
                exit;
            } else {
                $error = "Gagal mendaftar: " . mysqli_error($koneksi);
            }
        }
    }
}
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Daftar Akun | GHD Barbershop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="../backend/foto/logo.ico">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/fontawesome-all.min.css">
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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 0;
        }
        h2 { font-family: 'Playfair Display', serif; color: var(--lux-white); }
        .register-card { 
            background: var(--lux-surface); 
            border: 1px solid rgba(197, 160, 89, 0.2); 
            padding: 40px; 
            border-radius: 6px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.8); 
            width: 100%;
            max-width: 480px;
        }
        .form-control { 
            background: #080808 !important; 
            color: #fff !important; 
            border: 1px solid rgba(197, 160, 89, 0.3) !important; 
            height: 45px;
            border-radius: 4px;
        }
        .form-control:focus { 
            border-color: var(--lux-gold) !important; 
            box-shadow: 0 0 10px rgba(197,160,89,0.3); 
        }
        label { color: var(--lux-gold); font-size: 11px; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; }
        .btn-lux { 
            background: var(--lux-gold); 
            color: var(--lux-black); 
            font-weight: 600; 
            text-transform: uppercase; 
            padding: 14px; 
            border-radius: 4px; 
            border: none; 
            width: 100%; 
            letter-spacing: 2px;
            font-size: 12px;
            transition: 0.3s;
        }
        .btn-lux:hover { background: #e8d3a2; box-shadow: 0 0 15px rgba(197,160,89,0.4); }
    </style>
</head>
<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="register-card">
                    <div class="text-center mb-4">
                        <span style="color: var(--lux-gold); font-size: 10px; letter-spacing: 3px; text-transform: uppercase;">New Membership</span>
                        <h2>Daftar Akun Baru</h2>
                        <p style="font-size: 12px; margin-top: 5px;">Buat akun untuk mulai melakukan booking online</p>
                    </div>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger py-2" style="font-size: 12px; background: rgba(220,53,69,0.2); border-color: #dc3545; color: #ff6b6b;">
                            <?= $error; ?>
                        </div>
                    <?php endif; ?>

                    <form action="register.php" method="POST">
                        <div class="form-group mb-3">
                            <label>Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" placeholder="Nama lengkap Anda..." required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Alamat Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Email aktif Anda..." required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Nomor Telepon / WhatsApp</label>
                            <input type="text" name="phone" class="form-control" placeholder="Contoh: 08123456789">
                        </div>
                        <div class="form-group mb-4">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Buat password akun..." required>
                        </div>
                        <button type="submit" class="btn btn-lux mb-3">Daftar Sekarang</button>
                    </form>

                    <div class="text-center" style="font-size: 12px;">
                        <span>Sudah punya akun? <a href="login.php" style="color: var(--lux-gold); text-decoration: none; font-weight: 600;">Login di sini</a></span>
                        <br><br>
                        <a href="index.php" style="color: #a3a3a3; text-decoration: none;"><i class="fas fa-arrow-left mr-1"> Kembali ke Beranda</i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>