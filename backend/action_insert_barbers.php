<?php

include "connection.php";

session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php?pesan=belum_login");
    exit;
}

// Mengambil data dari form
$name = $_POST['name'] ?? '';
$description = $_POST['description'] ?? '';
$specialty = $_POST['specialty'] ?? '';

$name = mysqli_real_escape_string($koneksi, $name);
$description = mysqli_real_escape_string($koneksi, $description);
$specialty = mysqli_real_escape_string($koneksi, $specialty);


// ===============================
// UPLOAD IMAGE
// ===============================

$image = "";

if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {

    $folder = "foto/";

    // Membuat folder jika belum ada
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $nama_file = $_FILES['image']['name'];
    $tmp_file = $_FILES['image']['tmp_name'];

    $extension = strtolower(
        pathinfo($nama_file, PATHINFO_EXTENSION)
    );

    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($extension, $allowed)) {
        die("Format gambar tidak diperbolehkan.");
    }

    // Nama file unik
    $image = time() . "_" . uniqid() . "." . $extension;

    move_uploaded_file(
        $tmp_file,
        $folder . $image
    );
}


// ===============================
// INSERT DATABASE
// ===============================

$query = mysqli_query(
    $koneksi,
    "INSERT INTO barbers
    (name, description, specialty, image)
    VALUES
    ('$name', '$description', '$specialty', '$image')"
);

if ($query) {

    header("Location: tabel_barbers.php?pesan=berhasil_tambah");
    exit;

} else {

    die(
        "Gagal menambahkan data: "
        . mysqli_error($koneksi)
    );

}
?>