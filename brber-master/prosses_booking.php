<?php
include "../backend/connection.php";

if (isset($_POST['submit_booking'])) {
    $customer_name  = mysqli_real_escape_string($koneksi, trim($_POST['customer_name']));
    $customer_phone = mysqli_real_escape_string($koneksi, trim($_POST['customer_phone']));
    $service_id     = mysqli_real_escape_string($koneksi, trim($_POST['service_id']));
    $barber_id      = mysqli_real_escape_string($koneksi, trim($_POST['barber_id']));
    $booking_date   = mysqli_real_escape_string($koneksi, trim($_POST['booking_date']));
    $booking_time   = mysqli_real_escape_string($koneksi, trim($_POST['booking_time']));

    // Buat kode unik booking (misal: GHD-TAHUNBULANTANGGAL-RANDOM)
    $booking_code = "GHD-" . date('Ymd') . "-" . strtoupper(substr(md5(uniqid()), 0, 4));

    // Simpan ke database
    $query = "INSERT INTO bookings (booking_code, customer_name, customer_phone, service_id, barber_id, booking_date, booking_time, status) 
              VALUES ('$booking_code', '$customer_name', '$customer_phone', '$service_id', '$barber_id', '$booking_date', '$booking_time', 'Pending')";

    $execute = mysqli_query($koneksi, $query);

    if ($execute) {
        // Alihkan ke payment.php dengan membawa parameter code
        header("Location: payment.php?code=" . $booking_code);
        exit();
    } else {
        echo "<script>alert('Gagal memproses booking: " . mysqli_error($koneksi) . "'); window.history.back();</script>";
    }
} else {
    header("Location: booking.php");
    exit();
}
?>