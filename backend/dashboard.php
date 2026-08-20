<?php

session_start();

if (!isset($_SESSION['id_admin'])) {
    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - GHD Barbershop</title>
</head>
<body>

    <h1>Dashboard GHD Barbershop</h1>

    <p>
        Selamat datang, 
        <?php echo htmlspecialchars($_SESSION['username']); ?>
    </p>

    <a href="logout.php">Logout</a>

</body>

</html>