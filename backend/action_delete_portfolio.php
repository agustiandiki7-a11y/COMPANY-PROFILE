<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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

$id_portfolio = $_GET['id_portfolio'] ?? '';

if ($id_portfolio == '') {

    header("Location: tabel_portfolio.php");
    exit;

}


/*
|--------------------------------------------------------------------------
| ESCAPE ID
|--------------------------------------------------------------------------
*/

$id_portfolio = mysqli_real_escape_string(
    $koneksi,
    $id_portfolio
);


/*
|--------------------------------------------------------------------------
| AMBIL GAMBAR
|--------------------------------------------------------------------------
*/

$image_query = mysqli_query(
    $koneksi,

    "SELECT image FROM portfolio
     WHERE id_portfolio = '$id_portfolio'"
);

$image_data = mysqli_fetch_assoc($image_query);

$image = $image_data['image'] ?? '';


/*
|--------------------------------------------------------------------------
| DELETE DATABASE
|--------------------------------------------------------------------------
*/

$query = mysqli_query(
    $koneksi,

    "DELETE FROM portfolio
     WHERE id_portfolio = '$id_portfolio'"
);


/*
|--------------------------------------------------------------------------
| HASIL DELETE
|--------------------------------------------------------------------------
*/

if ($query) {

    /*
    | Hapus file gambar
    */

    if (
        $image != '' &&
        file_exists("foto/" . $image)
    ) {

        unlink("foto/" . $image);

    }


    echo "<script>

        alert('Portfolio berhasil dihapus!');

        window.location='tabel_portfolio.php';

    </script>";

} else {

    echo "<script>

        alert('Portfolio gagal dihapus!');

        window.location='tabel_portfolio.php';

    </script>";

}

?>