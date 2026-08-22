```php
<?php
session_start();
require_once __DIR__ . '/../backend/connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: register.php");
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

if ($name === '' || $email === '' || $phone === '' || $password === '') {
    header("Location: register.php?error=" . urlencode("Semua data wajib diisi."));
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: register.php?error=" . urlencode("Format email tidak valid."));
    exit;
}

if ($password !== $confirm_password) {
    header("Location: register.php?error=" . urlencode("Konfirmasi password tidak sama."));
    exit;
}

if (strlen($password) < 6) {
    header("Location: register.php?error=" . urlencode("Password minimal 6 karakter."));
    exit;
}

$check = mysqli_prepare(
    $koneksi,
    "SELECT id_customer FROM customers WHERE email = ? LIMIT 1"
);

mysqli_stmt_bind_param($check, "s", $email);
mysqli_stmt_execute($check);
mysqli_stmt_store_result($check);

if (mysqli_stmt_num_rows($check) > 0) {
    mysqli_stmt_close($check);

    header("Location: register.php?error=" . urlencode("Email sudah terdaftar."));
    exit;
}

mysqli_stmt_close($check);

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = mysqli_prepare(
    $koneksi,
    "INSERT INTO customers (name, email, phone, password) VALUES (?, ?, ?, ?)"
);

mysqli_stmt_bind_param(
    $stmt,
    "ssss",
    $name,
    $email,
    $phone,
    $password_hash
);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);

    header("Location: login.php?success=" . urlencode("Registrasi berhasil. Silakan login."));
    exit;
}

mysqli_stmt_close($stmt);

header("Location: register.php?error=" . urlencode("Registrasi gagal."));
exit;
```
