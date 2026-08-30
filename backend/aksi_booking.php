<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login") {
    header("Location: login.php?pesan=belum_login");
    exit;
}

include "connection.php";

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    // Deteksi nama kolom primary key di tabel bookings secara aman
    $cek_kolom_db = mysqli_query($koneksi, "SHOW COLUMNS FROM bookings LIKE 'id'");
    $primary_key = (mysqli_num_rows($cek_kolom_db) > 0) ? 'id' : 'id_booking';

    $status_baru = '';

    if ($action == 'approve') {
        $status_baru = 'approved';
    } elseif ($action == 'reject') {
        $status_baru = 'rejected';
    } elseif ($action == 'finish') {
        $status_baru = 'completed';
    } elseif ($action == 'pending') {
        $status_baru = 'pending';
    } elseif ($action == 'delete') {
        $query_hapus = mysqli_query($koneksi, "DELETE FROM bookings WHERE $primary_key = $id");
        if ($query_hapus) {
            header("Location: tabel_bookings.php?pesan=dihapus");
            exit;
        } else {
            die("Gagal menghapus data: " . mysqli_error($koneksi));
        }
    }

    // Eksekusi Update Status
    if (!empty($status_baru)) {
        $query_update = mysqli_query($koneksi, "UPDATE bookings SET status = '$status_baru' WHERE $primary_key = $id");
        if ($query_update) {
            header("Location: tabel_bookings.php?pesan=sukses");
            exit;
        } else {
            die("Gagal memperbarui status ke database: " . mysqli_error($koneksi));
        }
    }
}

// Aksi hapus semua data
if (isset($_GET['action']) && $_GET['action'] == 'hapus_semua') {
    $query_kosongkan = mysqli_query($koneksi, "DELETE FROM bookings");
    if ($query_kosongkan) {
        header("Location: tabel_bookings.php?pesan=dihapus");
        exit;
    } else {
        die("Gagal mengosongkan data: " . mysqli_error($koneksi));
    }
}

header("Location: tabel_bookings.php");
exit;
?>  