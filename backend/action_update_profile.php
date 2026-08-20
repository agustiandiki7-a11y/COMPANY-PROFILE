<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login') {
    header("Location: login.php?pesan=belum_login");
    exit;
}

include "connection.php";

$id_profile = $_POST['id_profile'] ?? '';
$name = $_POST['name'] ?? '';
$description = $_POST['description'] ?? '';
$address = $_POST['address'] ?? '';
$phone = $_POST['phone'] ?? '';
$email = $_POST['email'] ?? '';
$instagram = $_POST['instagram'] ?? '';
$opening_hours = $_POST['opening_hours'] ?? '';

if ($id_profile === '' || $name === '') {
    echo "<script>
        alert('ID profile dan nama wajib diisi!');
        window.history.back();
    </script>";
    exit;
}

$id_profile = mysqli_real_escape_string($koneksi, $id_profile);
$name = mysqli_real_escape_string($koneksi, $name);
$description = mysqli_real_escape_string($koneksi, $description);
$address = mysqli_real_escape_string($koneksi, $address);
$phone = mysqli_real_escape_string($koneksi, $phone);
$email = mysqli_real_escape_string($koneksi, $email);
$instagram = mysqli_real_escape_string($koneksi, $instagram);
$opening_hours = mysqli_real_escape_string($koneksi, $opening_hours);

// Ambil logo lama
$query_lama = mysqli_query(
    $koneksi,
    "SELECT logo FROM profile WHERE id_profile = '$id_profile'"
);

if (!$query_lama) {
    die("Query logo gagal: " . mysqli_error($koneksi));
}

$data_lama = mysqli_fetch_assoc($query_lama);

if (!$data_lama) {
    die("Data profile tidak ditemukan.");
}

$logo_lama = $data_lama['logo'];
$logo_baru = $logo_lama;

// Upload logo baru kalau ada
if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
    $nama_file = $_FILES['logo']['name'];
    $tmp_file = $_FILES['logo']['tmp_name'];
    $ukuran_file = $_FILES['logo']['size'];

    $ekstensi = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($ekstensi, $allowed)) {
        echo "<script>
            alert('Format logo tidak diperbolehkan!');
            window.history.back();
        </script>";
        exit;
    }

    if ($ukuran_file > 2 * 1024 * 1024) {
        echo "<script>
            alert('Ukuran logo maksimal 2MB!');
            window.history.back();
        </script>";
        exit;
    }

    if (!is_dir("foto")) {
        mkdir("foto", 0777, true);
    }

    $logo_baru = 'logo_' . time() . '_' . uniqid() . '.' . $ekstensi;

    if (!move_uploaded_file($tmp_file, "foto/" . $logo_baru)) {
        echo "<script>
            alert('Logo gagal diupload!');
            window.history.back();
        </script>";
        exit;
    }
}

// Update database
$query_update = mysqli_query(
    $koneksi,
    "UPDATE profile SET
        name = '$name',
        description = '$description',
        address = '$address',
        phone = '$phone',
        email = '$email',
        instagram = '$instagram',
        opening_hours = '$opening_hours',
        logo = '$logo_baru'
     WHERE id_profile = '$id_profile'"
);

if (!$query_update) {
    // Hapus logo baru kalau database gagal diupdate
    if ($logo_baru !== $logo_lama && file_exists("foto/" . $logo_baru)) {
        unlink("foto/" . $logo_baru);
    }

    die("UPDATE gagal: " . mysqli_error($koneksi));
}

// Hapus logo lama kalau diganti
if (
    $logo_baru !== $logo_lama &&
    !empty($logo_lama) &&
    file_exists("foto/" . $logo_lama)
) {
    unlink("foto/" . $logo_lama);
}

echo "<script>
    alert('Profile berhasil diupdate!');
    window.location='tabel_profile.php';
</script>";
exit;
?>