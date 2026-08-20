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
| AMBIL DATA FORM
|--------------------------------------------------------------------------
*/

$title = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';
$category = $_POST['category'] ?? '';


/*
|--------------------------------------------------------------------------
| VALIDASI
|--------------------------------------------------------------------------
*/

if ($title == '' || $category == '') {

    echo "<script>

        alert('Title dan Category wajib diisi!');

        window.history.back();

    </script>";

    exit;
}


/*
|--------------------------------------------------------------------------
| ESCAPE DATA
|--------------------------------------------------------------------------
*/

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
| UPLOAD IMAGE
|--------------------------------------------------------------------------
*/

$image_name = '';

if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {

    $image = $_FILES['image'];

    $file_name = $image['name'];
    $file_tmp = $image['tmp_name'];
    $file_size = $image['size'];

    $extension = strtolower(
        pathinfo($file_name, PATHINFO_EXTENSION)
    );

    /*
    | Format yang diperbolehkan
    */

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


    /*
    | Maksimal 2MB
    */

    if ($file_size > 2 * 1024 * 1024) {

        echo "<script>

            alert('Ukuran gambar maksimal 2MB!');

            window.history.back();

        </script>";

        exit;
    }


    /*
    | Buat nama file unik
    */

    $image_name =
        'portfolio_' .
        time() .
        '_' .
        uniqid() .
        '.' .
        $extension;


    /*
    | Folder penyimpanan
    */

    $upload_folder = "foto/";


    /*
    | Pastikan folder ada
    */

    if (!is_dir($upload_folder)) {
        mkdir($upload_folder, 0777, true);
    }


    /*
    | Pindahkan file
    */

    if (!move_uploaded_file(
        $file_tmp,
        $upload_folder . $image_name
    )) {

        echo "<script>

            alert('Gagal mengupload gambar!');

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

    "INSERT INTO portfolio
    (
        title,
        description,
        image,
        category
    )
    VALUES
    (
        '$title',
        '$description',
        '$image_name',
        '$category'
    )"
);


/*
|--------------------------------------------------------------------------
| HASIL
|--------------------------------------------------------------------------
*/

if ($query) {

    echo "<script>

        alert('Portfolio berhasil ditambahkan!');

        window.location='tabel_portfolio.php';

    </script>";

} else {

    /*
    | Hapus gambar jika database gagal
    */

    if (
        $image_name != '' &&
        file_exists("foto/" . $image_name)
    ) {

        unlink("foto/" . $image_name);

    }

    echo "<script>

        alert('Portfolio gagal ditambahkan!');

        window.history.back();

    </script>";
}

?>