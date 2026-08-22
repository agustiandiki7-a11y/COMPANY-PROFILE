```php
<?php
session_start();
require_once __DIR__ . '/../backend/connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    header("Location: login.php?error=" . urlencode("Email dan password wajib diisi."));
    exit;
}

$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id_customer, name, email, phone, password
     FROM customers
     WHERE email = ?
     LIMIT 1"
);

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$customer = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$customer || !password_verify($password, $customer['password'])) {
    header("Location: login.php?error=" . urlencode("Email atau password salah."));
    exit;
}

session_regenerate_id(true);

$_SESSION['customer_id'] = $customer['id_customer'];
$_SESSION['customer_name'] = $customer['name'];
$_SESSION['customer_email'] = $customer['email'];
$_SESSION['customer_phone'] = $customer['phone'];
$_SESSION['status'] = 'login';

$redirect = $_GET['redirect'] ?? $_POST['redirect'] ?? 'booking.php';

if ($redirect !== 'booking.php' && $redirect !== 'index.php') {
    $redirect = 'booking.php';
}

header("Location: " . $redirect);
exit;
```
