<?php

session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php?pesan=belum_login");
    exit;
}

include "connection.php";


/*
|--------------------------------------------------------------------------
| AMBIL DATA
|--------------------------------------------------------------------------
*/

$id_pricing = $_POST['id_pricing'] ?? '';
$name       = $_POST['name'] ?? '';
$description = $_POST['description'] ?? '';
$price      = $_POST['price'] ?? '';
$duration   = $_POST['duration'] ?? '';


/*
|--------------------------------------------------------------------------
| VALIDASI
|--------------------------------------------------------------------------
*/

if ($id_pricing == '' || $name == '' || $price == '') {

    echo "<script>

        alert('Data pricing belum lengkap!');

        window.history.back();

    </script>";

    exit;
}


/*
|--------------------------------------------------------------------------
| ESCAPE DATA
|--------------------------------------------------------------------------
*/

$id_pricing = mysqli_real_escape_string(
    $koneksi,
    $id_pricing
);

$name = mysqli_real_escape_string(
    $koneksi,
    $name
);

$description = mysqli_real_escape_string(
    $koneksi,
    $description
);

$price = mysqli_real_escape_string(
    $koneksi,
    $price
);

$duration = mysqli_real_escape_string(
    $koneksi,
    $duration
);


/*
|--------------------------------------------------------------------------
| UPDATE DATABASE
|--------------------------------------------------------------------------
*/

$query = mysqli_query(
    $koneksi,

    "UPDATE pricing SET

        name = '$name',
        description = '$description',
        price = '$price',
        duration = '$duration'

     WHERE id_pricing = '$id_pricing'"
);


/*
|--------------------------------------------------------------------------
| HASIL UPDATE
|--------------------------------------------------------------------------
*/

if ($query) {

    echo "<script>

        alert('Pricing berhasil diperbarui!');

        window.location='tabel_pricing.php';

    </script>";
} else {

    echo "<script>

        alert('Pricing gagal diperbarui!');

        window.history.back();

    </script>";
}
