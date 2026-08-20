<?php

include "connection.php";

session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php?pesan=belum_login");
    exit;
}


// =====================================================
// AMBIL DATA DARI FORM
// =====================================================

$name = mysqli_real_escape_string(
    $koneksi,
    $_POST['name']
);

$message = mysqli_real_escape_string(
    $koneksi,
    $_POST['message']
);

$rating = (int) $_POST['rating'];


// =====================================================
// UPLOAD FOTO
// =====================================================

$photo = "";

if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {

    $nama_file = $_FILES['photo']['name'];
    $tmp_file = $_FILES['photo']['tmp_name'];

    $ext = strtolower(
        pathinfo($nama_file, PATHINFO_EXTENSION)
    );

    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($ext, $allowed)) {

        die("Format foto tidak diperbolehkan.");

    }

    // Buat nama file baru
    $photo = time() . "_" . basename($nama_file);

    move_uploaded_file(
        $tmp_file,
        "foto/" . $photo
    );
}


// =====================================================
// INSERT DATABASE
// =====================================================

$sql = "INSERT INTO testimonials
        (name, message, rating, photo)
        VALUES
        ('$name', '$message', '$rating', '$photo')";


if (mysqli_query($koneksi, $sql)) {

    header("Location: tabel_testimonials.php?pesan=berhasil");
    exit;

} else {

    die(
        "Data gagal ditambahkan: " .
        mysqli_error($koneksi)
    );

}