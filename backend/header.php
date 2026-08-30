

<!DOCTYPE html>

<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description"
        content="Dashboard Admin GHD Barbershop">
    <meta name="author"
        content="GHD Barbershop">
    <title>GHD Barbershop - Dashboard</title>


    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css"
        rel="stylesheet"
        type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Custom styles fr this template -->
    <link href="css/sb-admin-2.min.css"
        rel="stylesheet">
    

        <!-- Google Fonts yang Sama Persis dengan Frontend -->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Oswald:wght@400;500;600;700&display=swap" rel="stylesheet">
<!-- Google Fonts & Tema GHD Backend -->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
<link href="css/ghd-backend.css" rel="stylesheet">
<?php
// Pastikan koneksi sudah ada
// Ambil data logo dari tabel profile
$query_favicon = mysqli_query($koneksi, "SELECT logo FROM profile LIMIT 1");
$row_fav = $query_favicon ? mysqli_fetch_assoc($query_favicon) : [];
$logo_file = !empty($row_fav['logo']) ? '../backend/foto/' . $row_fav['logo'] : 'assets/img/logo.ico';
?>
<link rel="icon" type="image/x-icon" href="<?= $logo_file; ?>">

</head>