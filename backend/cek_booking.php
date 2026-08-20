<?php

session_start();

include "../connection.php";

if (
    !isset($_SESSION['role'])
    ||
    $_SESSION['role'] !== 'admin'
) {

    die("Akses ditolak.");
}

$code = trim(
    $_POST['booking_code'] ?? ''
);

if ($code === '') {

    die("Kode booking wajib diisi.");
}

$query = mysqli_query(
    $koneksi,
    "SELECT
        b.*,
        u.name AS customer_name,
        u.phone,
        s.name AS service_name,
        br.name AS barber_name,
        p.status AS payment_status
     FROM bookings b

     JOIN users u
        ON u.id = b.user_id

     JOIN services s
        ON s.id = b.service_id

     LEFT JOIN barbers br
        ON br.id = b.barber_id

     LEFT JOIN payments p
        ON p.booking_id = b.id

     WHERE b.booking_code = '$code'

     LIMIT 1"
);

$data = mysqli_fetch_assoc($query);

if (!$data) {

    die("❌ Kode booking tidak ditemukan.");
}

if ($data['payment_status'] !== 'paid') {

    die("❌ Pembayaran belum lunas.");
}

if ($data['status'] !== 'confirmed') {

    die("❌ Booking tidak dapat check-in. "
        . "Status: "
        . $data['status']);
}

/*
|--------------------------------------------------------------------------
| CHECK IN
|--------------------------------------------------------------------------
*/

mysqli_query(
    $koneksi,
    "UPDATE bookings
     SET status = 'checked_in'
     WHERE id = '{$data['id']}'"
);

echo "
<h2>✅ Check-in Berhasil</h2>

<p>
Kode Booking:
<strong>{$data['booking_code']}</strong>
</p>

<p>
Pelanggan:
<strong>{$data['customer_name']}</strong>
</p>

<p>
Layanan:
<strong>{$data['service_name']}</strong>
</p>

<p>
Barber:
<strong>{$data['barber_name']}</strong>
</p>

<p>
Tanggal:
<strong>{$data['booking_date']}</strong>
</p>

<p>
Jam:
<strong>{$data['booking_time']}</strong>
</p>
";
