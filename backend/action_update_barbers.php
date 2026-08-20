<?php

include "connection.php";

session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php?pesan=belum_login");
    exit;
}

// Ambil data
$id_barber = $_POST['id_barber'] ?? '';
$name = $_POST['name'] ?? '';
$description = $_POST['description'] ?? '';
$specialty = $_POST['specialty'] ?? '';

$id_barber = mysqli_real_escape_string($koneksi, $id_barber);
$name = mysqli_real_escape_string($koneksi, $name);
$description = mysqli_real_escape_string($koneksi, $description);
$specialty = mysqli_real_escape_string($koneksi, $specialty);


// Ambil data lama
$query_lama = mysqli_query(
    $koneksi,
    "SELECT image FROM barbers
     WHERE id_barber = '$id_barber'"
);

$data_lama = mysqli_fetch_assoc($query_lama);

$image_lama = $data_lama['image'];


// ===============================
// CEK IMAGE BARU
// ===============================

if (
    isset($_FILES['image']) &&
    $_FILES['image']['error'] == 0
) {

    $folder = "foto/";

    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $nama_file = $_FILES['image']['name'];
    $tmp_file = $_FILES['image']['tmp_name'];

    $extension = strtolower(
        pathinfo($nama_file, PATHINFO_EXTENSION)
    );

    $allowed = [
        'jpg',
        'jpeg',
        'png',
        'webp'
    ];

    if (!in_array($extension, $allowed)) {
        die("Format gambar tidak diperbolehkan.");
    }

    $image_baru =
        time() . "_" .
        uniqid() .
        "." .
        $extension;

    move_uploaded_file(
        $tmp_file,
        $folder . $image_baru
    );


    // Hapus gambar lama
    if (
        !empty($image_lama) &&
        file_exists($folder . $image_lama)
    ) {
        unlink($folder . $image_lama);
    }

    $image_lama = $image_baru;
}


// ===============================
// UPDATE DATABASE
// ===============================

$query = mysqli_query(
    $koneksi,
    "UPDATE barbers SET

        name = '$name',
        description = '$description',
        specialty = '$specialty',
        image = '$image_lama'

     WHERE id_barber = '$id_barber'"
);


if ($query) {

    header(
        "Location: tabel_barbers.php?pesan=berhasil_update"
    );

    exit;

} else {

    die(
        "Gagal update data: "
        . mysqli_error($koneksi)
    );

}
?>