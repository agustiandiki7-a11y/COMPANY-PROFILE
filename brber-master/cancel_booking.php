<?php
session_start();
include "../backend/connection.php";

if (!isset($_SESSION['customer_id']) || !isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$booking_id = intval($_GET['id']);
$customer_name = isset($_SESSION['customer_name']) ? $_SESSION['customer_name'] : '';

$cek = mysqli_query($koneksi, "SELECT * FROM bookings WHERE id = $booking_id AND customer_name = '$customer_name'");

if (mysqli_num_rows($cek) > 0) {
    mysqli_query($koneksi, "DELETE FROM bookings WHERE id = $booking_id");
    echo "<script>alert('Booking berhasil dibatalkan.'); window.location.href='riwayat_booking.php';</script>";
} else {
    echo "<script>alert('Akses ditolak.'); window.location.href='riwayat_booking.php';</script>";
}
exit;