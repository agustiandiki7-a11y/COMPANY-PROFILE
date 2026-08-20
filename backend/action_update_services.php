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

$id_service  = $_POST['id_service'] ?? '';
$name        = $_POST['name'] ?? '';
$description = $_POST['description'] ?? '';
$price       = $_POST['price'] ?? '';
$duration    = $_POST['duration'] ?? '';


/*
|--------------------------------------------------------------------------
| VALIDASI
|--------------------------------------------------------------------------
*/

if ($id_service == '' || $name == '' || $price == '') {

    echo "<script>
        alert('Data wajib diisi!');
        window.history.back();
    </script>";

    exit;
}


/*
|--------------------------------------------------------------------------
| ESCAPE DATA
|--------------------------------------------------------------------------
*/

$id_service  = mysqli_real_escape_string($koneksi, $id_service);
$name        = mysqli_real_escape_string($koneksi, $name);
$description = mysqli_real_escape_string($koneksi, $description);
$price       = mysqli_real_escape_string($koneksi, $price);
$duration    = mysqli_real_escape_string($koneksi, $duration);


/*
|--------------------------------------------------------------------------
| AMBIL GAMBAR LAMA
|--------------------------------------------------------------------------
*/

$query_lama = mysqli_query(
    $koneksi,
    "SELECT image
     FROM services
     WHERE id_service = '$id_service'"
);

if (!$query_lama) {

    die("Query gagal: " . mysqli_error($koneksi));

}

$data_lama = mysqli_fetch_assoc($query_lama);

if (!$data_lama) {

    echo "<script>
        alert('Data services tidak ditemukan!');
        window.location='tabel_services.php';
    </script>";

    exit;
}

$image_lama = $data_lama['image'];

$image_baru = $image_lama;


/*
|--------------------------------------------------------------------------
| CEK JIKA ADA GAMBAR BARU
|--------------------------------------------------------------------------
*/

if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {

    $nama_file = $_FILES['image']['name'];
    $tmp_file  = $_FILES['image']['tmp_name'];

    // Ambil ekstensi
    $ekstensi = strtolower(
        pathinfo($nama_file, PATHINFO_EXTENSION)
    );


    // Format yang diperbolehkan
    $ekstensi_diperbolehkan = [
        'jpg',
        'jpeg',
        'png',
        'webp'
    ];


    if (!in_array($ekstensi, $ekstensi_diperbolehkan)) {

        echo "<script>
            alert('Format gambar harus JPG, JPEG, PNG, atau WEBP!');
            window.history.back();
        </script>";

        exit;
    }


    /*
    | Buat nama gambar baru
    */

    $image_baru = time() . '_' . uniqid() . '.' . $ekstensi;


    /*
    | Pastikan folder foto tersedia
    */

    if (!is_dir("foto")) {
        mkdir("foto", 0777, true);
    }


    /*
    | Upload gambar baru
    */

    if (!move_uploaded_file(
        $tmp_file,
        "foto/" . $image_baru
    )) {

        echo "<script>
            alert('Gambar gagal diupload!');
            window.history.back();
        </script>";

        exit;
    }
}


/*
|--------------------------------------------------------------------------
| UPDATE DATABASE
|--------------------------------------------------------------------------
*/

$query = mysqli_query(
    $koneksi,
    "UPDATE services SET
        name = '$name',
        description = '$description',
        price = '$price',
        duration = '$duration',
        image = '$image_baru'
     WHERE id_service = '$id_service'"
);


/*
|--------------------------------------------------------------------------
| HASIL UPDATE
|--------------------------------------------------------------------------
*/

if ($query) {

    /*
    | Jika gambar diganti,
    | hapus gambar lama
    */

    if (
        !empty($image_lama) &&
        $image_lama != $image_baru &&
        file_exists("foto/" . $image_lama)
    ) {

        unlink("foto/" . $image_lama);

    }


    echo "<script>
        alert('Services berhasil diperbarui!');
        window.location='tabel_services.php';
    </script>";

} else {

    /*
    | Jika UPDATE database gagal
    | dan gambar baru sudah terupload,
    | hapus gambar baru.
    */

    if (
        $image_baru != $image_lama &&
        file_exists("foto/" . $image_baru)
    ) {

        unlink("foto/" . $image_baru);

    }


    echo "<script>
        alert('Services gagal diperbarui!');
        window.history.back();
    </script>";
}

?>