<?php
session_start();
include "connection.php";

if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = intval($_GET['id']);

    if ($action == 'approve') {
        mysqli_query($koneksi, "UPDATE bookings SET status = 'approved' WHERE id = $id");
    } elseif ($action == 'reject') {
        mysqli_query($koneksi, "UPDATE bookings SET status = 'rejected' WHERE id = $id");
    } elseif ($action == 'finish' || $action == 'complete') {
        mysqli_query($koneksi, "UPDATE bookings SET status = 'completed' WHERE id = $id");
    } elseif ($action == 'pending') {
        mysqli_query($koneksi, "UPDATE bookings SET status = 'pending' WHERE id = $id");
    } elseif ($action == 'delete') {
        // Hapus data, otomatis kursi yang dipesan kembali tersedia di frontend
        mysqli_query($koneksi, "DELETE FROM bookings WHERE id = $id");
        header("Location: tabel_bookings.php?pesan=dihapus");
        exit;
    } elseif ($action == 'hapus_semua') {
        mysqli_query($koneksi, "DELETE FROM bookings");
        header("Location: tabel_bookings.php?pesan=dihapus_semua");
        exit;
    }

    header("Location: tabel_bookings.php?pesan=sukses");
    exit;
} else {
    header("Location: tabel_bookings.php");
    exit;
}
?>