<?php
session_start();
include "../backend/connection.php";

// Pastikan kustomer sudah login dan ada ID booking yang dikirim
if (!isset($_SESSION['customer_id']) || !isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$booking_id = intval($_GET['id']);
$customer_id = $_SESSION['customer_id'];

// Keamanan: Pastikan booking ini benar-benar milik kustomer yang sedang login
$cek = mysqli_query($koneksi, "SELECT * FROM bookings WHERE id = $booking_id AND customer_id = $customer_id");
if (mysqli_num_rows($cek) > 0) {
    // Hapus data booking dari database (atau ubah status jadi 'cancelled' tergantung struktur tabelmu)
    mysqli_query($koneksi, "DELETE FROM bookings WHERE id = $booking_id");
    
    echo "<script>alert('Booking berhasil dibatalkan. Kursi kembali tersedia.'); window.location.href='index.php';</script>";
} else {
    echo "<script>alert('Akses ditolak atau data tidak ditemukan.'); window.location.href='index.php';</script>";
}
exit;