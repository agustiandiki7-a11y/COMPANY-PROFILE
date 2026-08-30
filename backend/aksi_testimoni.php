<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login") {
    header("Location: login.php?pesan=belum_login");
    exit;
}

include "connection.php";

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Sesuaikan nama kolom primary key tabel testimonials (biasanya 'id' atau 'id_testimonial')
    $query = mysqli_query($koneksi, "DELETE FROM testimonials WHERE id_testimonial = $id");
    
    if ($query) {
        header("Location: tabel_testimonials.php?pesan=dihapus");
        exit();
    } else {
        echo "Gagal menghapus data: " . mysqli_error($koneksi);
    }
}