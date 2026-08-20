<?php

session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php?pesan=belum_login");
    exit;
}

include "connection.php";

/*
|--------------------------------------------------------------------------
| AMBIL DATA FORM
|--------------------------------------------------------------------------
*/

$name          = $_POST['name'] ?? '';
$description   = $_POST['description'] ?? '';
$address       = $_POST['address'] ?? '';
$phone         = $_POST['phone'] ?? '';
$email         = $_POST['email'] ?? '';
$instagram     = $_POST['instagram'] ?? '';
$opening_hours = $_POST['opening_hours'] ?? '';

/*
|--------------------------------------------------------------------------
| ESCAPE DATA
|--------------------------------------------------------------------------
*/

$name          = mysqli_real_escape_string($koneksi, $name);
$description   = mysqli_real_escape_string($koneksi, $description);
$address       = mysqli_real_escape_string($koneksi, $address);
$phone         = mysqli_real_escape_string($koneksi, $phone);
$email         = mysqli_real_escape_string($koneksi, $email);
$instagram     = mysqli_real_escape_string($koneksi, $instagram);
$opening_hours = mysqli_real_escape_string($koneksi, $opening_hours);

/*
|--------------------------------------------------------------------------
| VALIDASI
|--------------------------------------------------------------------------
*/

if ($name == '') {

    echo "<script>
        alert('Nama profile wajib diisi!');
        window.history.back();
    </script>";

    exit;
}

/*
|--------------------------------------------------------------------------
| UPLOAD LOGO
|--------------------------------------------------------------------------
*/

$logo = '';

if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {

    $nama_file = $_FILES['logo']['name'];
    $tmp_file  = $_FILES['logo']['tmp_name'];

    $ekstensi = strtolower(
        pathinfo($nama_file, PATHINFO_EXTENSION)
    );

    $ekstensi_diperbolehkan = [
        'jpg',
        'jpeg',
        'png',
        'webp'
    ];

    if (!in_array($ekstensi, $ekstensi_diperbolehkan)) {

        echo "<script>
            alert('Format logo tidak diperbolehkan!');
            window.history.back();
        </script>";

        exit;
    }

    $logo = time() . '_' . uniqid() . '.' . $ekstensi;

    if (!is_dir("foto")) {
        mkdir("foto", 0777, true);
    }

    if (!move_uploaded_file(
        $tmp_file,
        "foto/" . $logo
    )) {

        echo "<script>
            alert('Logo gagal diupload!');
            window.history.back();
        </script>";

        exit;
    }
}

/*
|--------------------------------------------------------------------------
| INSERT DATABASE
|--------------------------------------------------------------------------
*/

$query = mysqli_query(
    $koneksi,
    "INSERT INTO profile
    (
        name,
        description,
        address,
        phone,
        email,
        instagram,
        opening_hours,
        logo
    )
    VALUES
    (
        '$name',
        '$description',
        '$address',
        '$phone',
        '$email',
        '$instagram',
        '$opening_hours',
        '$logo'
    )"
);

/*
|--------------------------------------------------------------------------
| HASIL
|--------------------------------------------------------------------------
*/

if ($query) {

    echo "<script>
        alert('Profile berhasil ditambahkan!');
        window.location='tabel_profile.php';
    </script>";

} else {

    if (!empty($logo) && file_exists("foto/" . $logo)) {
        unlink("foto/" . $logo);
    }

    echo "<script>
        alert('Profile gagal ditambahkan!');
        window.history.back();
    </script>";
}

?>