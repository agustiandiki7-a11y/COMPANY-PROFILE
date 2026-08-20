<?php

session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php?pesan=belum_login");
    exit;
}

include "connection.php";

/*
|--------------------------------------------------------------------------
| AMBIL ID SERVICES
|--------------------------------------------------------------------------
*/

$id_service = $_GET['id_service'] ?? '';

if ($id_service == '') {
    header("Location: tabel_services.php");
    exit;
}

$id_service = mysqli_real_escape_string($koneksi, $id_service);


/*
|--------------------------------------------------------------------------
| AMBIL DATA GAMBAR
|--------------------------------------------------------------------------
*/

$query_data = mysqli_query(
    $koneksi,
    "SELECT image FROM services WHERE id_service = '$id_service'"
);

$data = mysqli_fetch_assoc($query_data);


/*
|--------------------------------------------------------------------------
| HAPUS DATA DARI DATABASE
|--------------------------------------------------------------------------
*/

$query = mysqli_query(
    $koneksi,
    "DELETE FROM services
     WHERE id_service = '$id_service'"
);


/*
|--------------------------------------------------------------------------
| JIKA BERHASIL
|--------------------------------------------------------------------------
*/

if ($query) {

    // Hapus gambar dari folder foto
    if (
        !empty($data['image']) &&
        file_exists("foto/" . $data['image'])
    ) {

        unlink("foto/" . $data['image']);

    }

    echo "<script>
            alert('Services berhasil dihapus!');
            window.location='tabel_services.php';
          </script>";

} else {

    echo "<script>
            alert('Services gagal dihapus!');
            window.location='tabel_services.php';
          </script>";

}

?>