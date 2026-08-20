<?php

session_start();

include "connection.php";


// ============================================================
// MENGAMBIL DATA DARI FORM LOGIN
// ============================================================

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';


// Membersihkan username

$username = mysqli_real_escape_string(
    $koneksi,
    $username
);


// ============================================================
// MENCARI DATA ADMIN
// ============================================================

$query = mysqli_query(
    $koneksi,
    "SELECT * FROM admin WHERE username = '$username'"
);


// Mengecek query

if (!$query) {

    die("Query gagal: " . mysqli_error($koneksi));

}


// ============================================================
// CEK DATA ADMIN
// ============================================================

if (mysqli_num_rows($query) == 1) {

    $admin = mysqli_fetch_assoc($query);


    // ========================================================
    // CEK PASSWORD
    // ========================================================

    if ($password === $admin['password']) {


        // Membuat session login

        $_SESSION['status'] = "login";

        $_SESSION['id_admin'] = $admin['id_admin'];

        $_SESSION['username'] = $admin['username'];


        // ====================================================
        // REDIRECT KE DASHBOARD
        // ====================================================

        header("Location: index.php");

        exit;


    } else {

        // Password salah

        header("Location: login.php?pesan=gagal");

        exit;

    }


} else {

    // Username tidak ditemukan

    header("Location: login.php?pesan=gagal");

    exit;

}

?>