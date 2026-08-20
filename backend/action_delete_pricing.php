<?php

session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php?pesan=belum_login");
    exit;
}

include "connection.php";


/*
|--------------------------------------------------------------------------
| AMBIL ID
|--------------------------------------------------------------------------
*/

$id_pricing = $_GET['id_pricing'] ?? '';

if ($id_pricing == '') {

    header("Location: tabel_pricing.php");
    exit;

}


/*
|--------------------------------------------------------------------------
| ESCAPE ID
|--------------------------------------------------------------------------
*/

$id_pricing = mysqli_real_escape_string(
    $koneksi,
    $id_pricing
);


/*
|--------------------------------------------------------------------------
| DELETE DATA
|--------------------------------------------------------------------------
*/

$query = mysqli_query(
    $koneksi,

    "DELETE FROM pricing
     WHERE id_pricing = '$id_pricing'"
);


/*
|--------------------------------------------------------------------------
| HASIL DELETE
|--------------------------------------------------------------------------
*/

if ($query) {

    echo "<script>

        alert('Pricing berhasil dihapus!');

        window.location='tabel_pricing.php';

    </script>";

} else {

    echo "<script>

        alert('Pricing gagal dihapus!');

        window.location='tabel_pricing.php';

    </script>";

}

?>
