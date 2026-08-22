```php
<?php
session_start();
require_once __DIR__ . '/../backend/connection.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: payment.php");
    exit;
}

$id_booking = (int)($_POST['id_booking'] ?? 0);
$amount = (float)($_POST['amount'] ?? 0);
$payment_method = trim($_POST['payment_method'] ?? '');

if ($id_booking <= 0 || $amount <= 0 || $payment_method === '') {
    header("Location: payment.php?error=" . urlencode("Data pembayaran belum lengkap."));
    exit;
}

/* Cek booking milik customer yang sedang login */
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id_booking
     FROM bookings
     WHERE id_booking = ? AND id_customer = ?
     LIMIT 1"
);

mysqli_stmt_bind_param(
    $stmt,
    "ii",
    $id_booking,
    $_SESSION['customer_id']
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$booking = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$booking) {
    header("Location: payment.php?error=" . urlencode("Booking tidak ditemukan."));
    exit;
}

/* Simpan pembayaran */
$status = "paid";

$stmt = mysqli_prepare(
    $koneksi,
    "INSERT INTO payments
    (id_booking, amount, payment_method, status, paid_at)
    VALUES (?, ?, ?, ?, NOW())"
);

mysqli_stmt_bind_param(
    $stmt,
    "idss",
    $id_booking,
    $amount,
    $payment_method,
    $status
);

if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);

    header("Location: payment.php?error=" . urlencode("Pembayaran gagal diproses."));
    exit;
}

mysqli_stmt_close($stmt);

/* Update status booking */
$booking_status = "paid";

$stmt = mysqli_prepare(
    $koneksi,
    "UPDATE bookings
     SET status = ?
     WHERE id_booking = ? AND id_customer = ?"
);

mysqli_stmt_bind_param(
    $stmt,
    "sii",
    $booking_status,
    $id_booking,
    $_SESSION['customer_id']
);

mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

/* Berhasil */
header("Location: payment.php?success=" . urlencode("Pembayaran berhasil."));
exit;
?>
```
