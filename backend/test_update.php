<?php
include "connection.php";

// Ambil ID data pertama di tabel bookings
$q = mysqli_query($koneksi, "SELECT * FROM bookings LIMIT 1");
$data = mysqli_fetch_assoc($q);

if(!$data) {
    echo "Tabel bookings kosong!";
    exit;
}

$id_pertama = $data['id'] ?? $data['id_booking'] ?? $data['id_orders'] ?? 1;
echo "ID yang akan di-test: $id_pertama<br>";

// Coba update paksa ke approved
$update = mysqli_query($koneksi, "UPDATE bookings SET status = 'approved' WHERE id = '$id_pertama' OR id_booking = '$id_pertama'");

if($update) {
    echo "<h3 style='color:green;'>UPDATE BERHASIL! Cek database sekarang, apakah status berubah jadi 'approved'?</h3>";
} else {
    echo "<h3 style='color:red;'>GAGAL: " . mysqli_error($koneksi) . "</h3>";
}
?>