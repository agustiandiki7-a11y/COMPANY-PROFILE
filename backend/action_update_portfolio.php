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
| AMBIL DATA
|--------------------------------------------------------------------------
*/

$id_portfolio = $_POST['id_portfolio'] ?? '';
$title = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';
$category = $_POST['category'] ?? '';


if (
    $id_portfolio == '' ||
    $title == '' ||
    $category == ''
) {

    echo "<script>

        alert('Data belum lengkap!');

        window.history.back();

    </script>";

    exit;
}


/*
|--------------------------------------------------------------------------
| ESCAPE DATA
|--------------------------------------------------------------------------
*/

$id_portfolio = mysqli_real_escape_string(
    $koneksi,
    $id_portfolio
);

$title = mysqli_real_escape_string(
    $koneksi,
    $title
);

$description = mysqli_real_escape_string(
    $koneksi,
    $description
);

$category = mysqli_real_escape_string(
    $koneksi,
    $category
);


/*
|--------------------------------------------------------------------------
| AMBIL DATA GAMBAR LAMA
|--------------------------------------------------------------------------
*/

$old_query = mysqli_query(
    $koneksi,

    "SELECT image FROM portfolio
     WHERE id_portfolio = '$id_portfolio'"
);

$old_data = mysqli_fetch_assoc($old_query);

$old_image = $old_data['image'] ?? '';

$new_image = $old_image;


/*
|--------------------------------------------------------------------------
| CEK GAMBAR BARU
|--------------------------------------------------------------------------
*/

if (
    isset($_FILES['image']) &&
    $_FILES['image']['error'] == 0
) {

    $image = $_FILES['image'];

    $file_name = $image['name'];
    $file_tmp = $image['tmp_name'];
    $file_size = $image['size'];

    $extension = strtolower(
        pathinfo($file_name, PATHINFO_EXTENSION)
    );


    $allowed_extension = [
        'jpg',
        'jpeg',
        'png',
        'webp'
    ];


    if (!in_array($extension, $allowed_extension)) {

        echo "<script>

            alert('Format gambar tidak diperbolehkan!');

            window.history.back();

        </script>";

        exit;
    }


    if ($file_size > 2 * 1024 * 1024) {

        echo "<script>

            alert('Ukuran gambar maksimal 2MB!');

            window.history.back();

        </script>";

        exit;
    }


    /*
    | Nama gambar baru
    */

    $new_image =
        'portfolio_' .
        time() .
        '_' .
        uniqid() .
        '.' .
        $extension;


    $upload_folder = "foto/";


    if (!is_dir($upload_folder)) {
        mkdir($upload_folder, 0777, true);
    }


    /*
    | Upload gambar baru
    */

    if (!move_uploaded_file(
        $file_tmp,
        $upload_folder . $new_image
    )) {

        echo "<script>

            alert('Gagal mengupload gambar baru!');

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

$new_image = mysqli_real_escape_string(
    $koneksi,
    $new_image
);


$query = mysqli_query(
    $koneksi,

    "UPDATE portfolio SET

        title = '$title',
        description = '$description',
        image = '$new_image',
        category = '$category'

     WHERE id_portfolio = '$id_portfolio'"
);


/*
|--------------------------------------------------------------------------
| HASIL UPDATE
|--------------------------------------------------------------------------
*/

if ($query) {

    /*
    | Hapus gambar lama
    | setelah database berhasil diupdate
    */

    if (
        $new_image != $old_image &&
        $old_image != '' &&
        file_exists("foto/" . $old_image)
    ) {

        unlink("foto/" . $old_image);

    }


    echo "<script>

        alert('Portfolio berhasil diperbarui!');

        window.location='tabel_portfolio.php';

    </script>";

} else {

    /*
    | Jika update gagal,
    | hapus gambar baru yang sudah terupload
    */

    if (
        $new_image != $old_image &&
        file_exists("foto/" . $new_image)
    ) {

        unlink("foto/" . $new_image);

    }


    echo "<script>

        alert('Portfolio gagal diperbarui!');

        window.history.back();

    </script>";
}

?>