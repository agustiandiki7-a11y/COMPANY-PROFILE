<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login" || $_SESSION['role'] !== 'superadmin') {
    header("Location: index.php");
    exit;
}

include "connection.php";

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Mencegah superadmin menghapus akunnya sendiri yang sedang aktif
    if ($id == $_SESSION['admin_id']) {
        echo "<script>alert('Anda tidak dapat menghapus akun yang sedang Anda gunakan sendiri!'); window.location.href='tabel_admin.php';</script>";
        exit;
    }

    $query = mysqli_query($koneksi, "DELETE FROM admin WHERE id = $id");
    if ($query) {
        header("Location: tabel_admin.php?pesan=dihapus");
        exit;
    } else {
        die("Gagal menghapus admin: " . mysqli_error($koneksi));
    }
}

header("Location: tabel_admin.php");
exit;
?>