<?php

session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php?pesan=belum_login");
    exit;
}

include "connection.php";


/*
|--------------------------------------------------------------------------
| AMBIL DATA DARI FORM
|--------------------------------------------------------------------------
*/

$name        = $_POST['name'] ?? '';
$description = $_POST['description'] ?? '';
$price       = $_POST['price'] ?? '';
$duration    = $_POST['duration'] ?? '';


/*
|--------------------------------------------------------------------------
| VALIDASI
|--------------------------------------------------------------------------
*/

if ($name == '' || $price == '') {

    echo "<script>
        alert('Name dan Price wajib diisi!');
        window.history.back();
    </script>";

    exit;
}


/*
|--------------------------------------------------------------------------
| AMANKAN DATA
|--------------------------------------------------------------------------
*/

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
| INSERT DATA
|--------------------------------------------------------------------------
*/

$query = mysqli_query(
    $koneksi,

    "INSERT INTO pricing
    (
        name,
        description,
        price,
        duration
    )
    VALUES
    (
        '$name',
        '$description',
        '$price',
        '$duration'
    )"
);


/*
|--------------------------------------------------------------------------
| HASIL
|--------------------------------------------------------------------------
*/

if ($query) {

    echo "<script>

        alert('Pricing berhasil ditambahkan!');

        window.location='tabel_pricing.php';

    </script>";

} else {

    echo "<script>

        alert('Pricing gagal ditambahkan!');

        window.history.back();

    </script>";

}

?>