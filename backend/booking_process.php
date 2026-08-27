<?php
session_start();
include "../backend/connection.php";

// Pastikan user sudah login
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['submit_booking'])) {
    $customer_name  = mysqli_real_escape_string($koneksi, $_POST['customer_name']);
    $customer_phone = mysqli_real_escape_string($koneksi, $_POST['customer_phone']);
    $service_id     = intval($_POST['service_id']);
    $barber_id      = intval($_POST['barber_id']);
    $chair_number   = mysqli_real_escape_string($koneksi, $_POST['chair_number']);
    $booking_date   = mysqli_real_escape_string($koneksi, $_POST['booking_date']);
    $booking_time   = mysqli_real_escape_string($koneksi, $_POST['booking_time']);
    $booking_code   = "GHD-" . rand(10000, 99999); // Kode unik booking

    // 1. VALIDASI DOUBLE BOOKING (PENTING!)
    // Cek apakah kursi sudah dipesan dengan status 'pending' atau 'approved' pada tanggal & kursi yang sama
    $cek_double = mysqli_query($koneksi, "
        SELECT id FROM bookings 
        WHERE chair_number = '$chair_number' 
        AND booking_date = '$booking_date' 
        AND status IN ('pending', 'approved')
        LIMIT 1
    ");

    if (mysqli_num_rows($cek_double) > 0) {
        // Jika sudah ada yang booking, kembalikan dengan pesan error
        echo "<script>alert('Maaf, kursi tersebut baru saja dipesan oleh customer lain! Silakan pilih kursi lain.'); window.location='booking.php?date=$booking_date';</script>";
        exit;
    }

    // 2. SIMPAN KE DATABASE (Status awal: 'pending')
    $query_insert = "
        INSERT INTO bookings (booking_code, customer_name, customer_phone, service_id, barber_id, chair_number, booking_date, booking_time, status) 
        VALUES ('$booking_code', '$customer_name', '$customer_phone', '$service_id', '$barber_id', '$chair_number', '$booking_date', '$booking_time', 'pending')
    ";

    $insert = mysqli_query($koneksi, $query_insert);

    if ($insert) {
        // Berhasil, arahkan ke halaman sukses atau riwayat dengan membawa kode booking
        echo "<script>alert('Booking berhasil dikirim! Kode Booking Anda: $booking_code. Menunggu persetujuan Admin.'); window.location='index.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal memproses booking: " . mysqli_error($koneksi) . "'); window.location='booking.php';</script>";
        exit;
    }
} else {
    header("Location: booking.php");
    exit;
}
?>