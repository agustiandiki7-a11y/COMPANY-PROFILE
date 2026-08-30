<?php
session_start();
include "../backend/connection.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = isset($_POST['email']) ? mysqli_real_escape_string($koneksi, $_POST['email']) : '';
    $password = isset($_POST['password']) ? mysqli_real_escape_string($koneksi, $_POST['password']) : '';

    // Ambil data kustomer berdasarkan email
    $result = mysqli_query($koneksi, "SELECT * FROM customers WHERE email = '$email'");
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        // Verifikasi password (mendukung teks biasa maupun hash)
        if ($password == $row['password'] || password_verify($password, $row['password'])) {
            
            // Simpan Session dengan pengecekan ganda agar tidak gagal
            $_SESSION['customer_id']   = isset($row['id']) ? $row['id'] : (isset($row['customer_id']) ? $row['customer_id'] : 1);
            $_SESSION['customer_name'] = isset($row['name']) ? $row['name'] : (isset($row['customer_name']) ? $row['customer_name'] : 'Pelanggan');
            $_SESSION['customer_email']= $row['email'];
            $_SESSION['customer_phone']= isset($row['phone']) ? $row['phone'] : '-';

            // Paksa session supaya tersimpan sempurna sebelum pindah halaman
            session_write_close();

            // Alihkan langsung ke halaman booking
            header("Location: booking.php");
            exit;
        } else {
            $error = "Password yang Anda masukkan salah!";
        }
    } else {
        $error = "Email tidak ditemukan. Silakan daftar terlebih dahulu.";
    }
}
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Login Kustomer | GHD Barbershop</title>
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
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        h2 { font-family: 'Playfair Display', serif; color: var(--lux-white); }
        .login-card { 
            background: var(--lux-surface); 
            border: 1px solid rgba(197, 160, 89, 0.2); 
            padding: 40px; 
            border-radius: 6px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.8); 
            width: 100%;
            max-width: 450px;
        }
        .form-control { 
            background: #080808 !important; 
            color: #fff !important; 
            border: 1px solid rgba(197, 160, 89, 0.3) !important; 
            height: 48px;
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
            <div class="col-md-6">
                <div class="login-card">
                    <div class="text-center mb-4">
                        <span style="color: var(--lux-gold); font-size: 10px; letter-spacing: 3px; text-transform: uppercase;">Customer Portal</span>
                        <h2>Login Kustomer</h2>
                        <p style="font-size: 12px; margin-top: 5px;">Masuk untuk langsung mengakses menu booking</p>
                    </div>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger py-2" style="font-size: 12px; background: rgba(220,53,69,0.2); border-color: #dc3545; color: #ff6b6b;">
                            <?= $error; ?>
                        </div>
                    <?php endif; ?>

                    <form action="login.php" method="POST">
                        <div class="form-group mb-3">
                            <label>Email Kustomer</label>
                            <input type="email" name="email" class="form-control" placeholder="Masukkan email anda..." required>
                        </div>
                        <div class="form-group mb-4">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Masukkan password..." required>
                        </div>
                        <button type="submit" class="btn btn-lux mb-3">Masuk & Booking</button>
                    </form>

                    <div class="text-center" style="font-size: 12px;">
                        <span>Belum punya akun? <a href="register.php" style="color: var(--lux-gold); text-decoration: none; font-weight: 600;">Daftar di sini</a></span>
                        <br><br>
                        <a href="index.php" style="color: #a3a3a3; text-decoration: none;"><i class="fas fa-arrow-left mr-1"> Kembali ke Beranda</i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>