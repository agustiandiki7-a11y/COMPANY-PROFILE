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
| BERSIHKAN DATA
|--------------------------------------------------------------------------
*/

$name        = mysqli_real_escape_string($koneksi, $name);
$description = mysqli_real_escape_string($koneksi, $description);
$price       = mysqli_real_escape_string($koneksi, $price);
$duration    = mysqli_real_escape_string($koneksi, $duration);

/*
|--------------------------------------------------------------------------
| VALIDASI
|--------------------------------------------------------------------------
*/

if ($name == '' || $price == '') {

    echo "<script>
        alert('Nama services dan harga wajib diisi!');
        window.history.back();
    </script>";

    exit;
}

/*
|--------------------------------------------------------------------------
| UPLOAD IMAGE
|--------------------------------------------------------------------------
*/

$image = '';

if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {

    $nama_file = $_FILES['image']['name'];
    $tmp_file  = $_FILES['image']['tmp_name'];

    // Ambil ekstensi file
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

    // Buat nama file unik
    $image = time() . '_' . uniqid() . '.' . $ekstensi;

    // Pastikan folder foto tersedia
    if (!is_dir("foto")) {
        mkdir("foto", 0777, true);
    }

    // Upload gambar
    if (!move_uploaded_file($tmp_file, "foto/" . $image)) {

        echo "<script>
            alert('Gambar gagal diupload!');
            window.history.back();
        </script>";

        exit;
    }
}

/*
|--------------------------------------------------------------------------
| INSERT KE DATABASE
|--------------------------------------------------------------------------
*/

$query = mysqli_query(
    $koneksi,
    "INSERT INTO services
    (
        name,
        description,
        price,
        duration,
        image
    )
    VALUES
    (
        '$name',
        '$description',
        '$price',
        '$duration',
        '$image'
    )"
);

/*
|--------------------------------------------------------------------------
| HASIL INSERT
|--------------------------------------------------------------------------
*/

if ($query) {

    echo "<script>
        alert('Services berhasil ditambahkan!');
        window.location='tabel_services.php';
    </script>";

} else {

    // Jika database gagal menyimpan,
    // hapus gambar yang tadi sudah diupload
    if (!empty($image) && file_exists("foto/" . $image)) {
        unlink("foto/" . $image);
    }

    echo "<script>
        alert('Services gagal ditambahkan!');
        window.history.back();
    </script>";
}

?>