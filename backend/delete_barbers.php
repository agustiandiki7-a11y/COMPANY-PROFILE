<?php

include "connection.php";

session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php?pesan=belum_login");
    exit;
}

$id_barber = $_GET['id_barber'] ?? 0;

$id_barber = mysqli_real_escape_string(
    $koneksi,
    $id_barber
);


// Ambil gambar terlebih dahulu
$query_data = mysqli_query(
    $koneksi,
    "SELECT image FROM barbers
     WHERE id_barber = '$id_barber'"
);

$data = mysqli_fetch_assoc($query_data);


// Hapus data database
$query = mysqli_query(
    $koneksi,
    "DELETE FROM barbers
     WHERE id_barber = '$id_barber'"
);


if ($query) {

    // Hapus file gambar
    if (
        !empty($data['image']) &&
        file_exists("foto/" . $data['image'])
    ) {

        unlink("foto/" . $data['image']);

    }

    header(
        "Location: tabel_barbers.php?pesan=berhasil_hapus"
    );

    exit;

} else {

    die(
        "Gagal menghapus data: "
        . mysqli_error($koneksi)
    );

}
?>