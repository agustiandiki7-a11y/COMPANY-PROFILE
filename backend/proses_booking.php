<?php

session_start();
include "../connection.php";

if (!isset($_SESSION['user_id'])) {
    die("Silakan login terlebih dahulu.");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request.");
}

$user_id = $_SESSION['user_id'];
$service_id = intval($_POST['service_id']);
$barber_id = !empty($_POST['barber_id'])
    ? intval($_POST['barber_id'])
    : null;

$booking_date = $_POST['booking_date'];
$booking_time = $_POST['booking_time'];

$query = mysqli_query(
    $koneksi,
    "SELECT price FROM services
     WHERE id = '$service_id'
     AND status = 'active'"
);

$service = mysqli_fetch_assoc($query);

if (!$service) {
    die("Layanan tidak ditemukan.");
}

$total_price = $service['price'];

/*
|--------------------------------------------------------------------------
| CEK SLOT
|--------------------------------------------------------------------------
*/

$barberCondition = $barber_id === null
    ? "barber_id IS NULL"
    : "barber_id = '$barber_id'";

$cek = mysqli_query(
    $koneksi,
    "SELECT id FROM bookings
     WHERE booking_date = '$booking_date'
     AND booking_time = '$booking_time'
     AND $barberCondition
     AND status IN (
         'pending_payment',
         'confirmed',
         'checked_in'
     )
     LIMIT 1"
);

if (mysqli_num_rows($cek) > 0) {
    die("Jadwal tersebut sudah digunakan.");
}

/*
|--------------------------------------------------------------------------
| GENERATE KODE BOOKING
|--------------------------------------------------------------------------
*/

$booking_code =
    "GHD-" .
    date("Ymd") .
    "-" .
    strtoupper(substr(md5(uniqid()), 0, 6));

/*
|--------------------------------------------------------------------------
| INSERT BOOKING
|--------------------------------------------------------------------------
*/

$sql = "
    INSERT INTO bookings (
        booking_code,
        user_id,
        service_id,
        barber_id,
        booking_date,
        booking_time,
        total_price,
        status
    )
    VALUES (
        '$booking_code',
        '$user_id',
        '$service_id',
        " . ($barber_id === null ? "NULL" : "'$barber_id'") . ",
        '$booking_date',
        '$booking_time',
        '$total_price',
        'pending_payment'
    )
";

if (!mysqli_query($koneksi, $sql)) {
    die("Booking gagal: " . mysqli_error($koneksi));
}

$booking_id = mysqli_insert_id($koneksi);

header(
    "Location: pembayaran.php?id=" . $booking_id
);

exit;