<?php
include "../backend/connection.php";

if (isset($_POST['submit_booking'])) {
    // Amankan data dari form
    $customer_name  = mysqli_real_escape_string($koneksi, trim($_POST['customer_name']));
    $customer_phone = mysqli_real_escape_string($koneksi, trim($_POST['customer_phone']));
    $service_id     = mysqli_real_escape_string($koneksi, trim($_POST['service_id']));
    $barber_id      = mysqli_real_escape_string($koneksi, trim($_POST['barber_id']));
    
    // Gunakan pengecekan isset agar tidak muncul warning jika kosong
    $chair_number   = isset($_POST['chair_number']) ? mysqli_real_escape_string($koneksi, trim($_POST['chair_number'])) : 'Kursi 01';
    
    $booking_date   = mysqli_real_escape_string($koneksi, trim($_POST['booking_date']));
    $booking_time   = mysqli_real_escape_string($koneksi, trim($_POST['booking_time']));

    // 1. Validasi Bentrok Kursi
    $cek_bentrok = mysqli_query($koneksi, "
        SELECT * FROM bookings 
        WHERE chair_number = '$chair_number' 
        AND booking_date = '$booking_date' 
        AND booking_time = '$booking_time'
    ");

    if (mysqli_num_rows($cek_bentrok) > 0) {
        echo "<script>
                alert('Maaf, $chair_number pada tanggal dan jam tersebut sudah dibooking orang lain! Silakan pilih kursi atau waktu lain.');
                window.history.back();
              </script>";
        exit();
    }

    // 2. Buat Kode Unik Booking & Simpan
    $booking_code = "GHD-" . date('Ymd') . "-" . strtoupper(substr(md5(uniqid()), 0, 4));

    $query = "INSERT INTO bookings (booking_code, customer_name, customer_phone, service_id, barber_id, chair_number, booking_date, booking_time, status) 
              VALUES ('$booking_code', '$customer_name', '$customer_phone', '$service_id', '$barber_id', '$chair_number', '$booking_date', '$booking_time', 'Pending')";

    $execute = mysqli_query($koneksi, $query);

    if ($execute) {
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