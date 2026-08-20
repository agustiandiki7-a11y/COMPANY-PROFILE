<?php
session_start();

if (isset($_SESSION['status']) && $_SESSION['status'] == "login") {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description"
        content="Login Admin GHD Barbershop">
    <meta name="author"
        content="GHD Barbershop">
    <title>GHD Barbershop - Login</title>
    <!-- Font Awesome -->
    <link href="vendor/fontawesome-free/css/all.min.css"
        rel="stylesheet"
        type="text/css">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900"
        rel="stylesheet">
    <!-- SB Admin 2 CSS -->
    <link href="css/sb-admin-2.min.css"
        rel="stylesheet">
</head>

<body class="bg-gradient-primary">
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <!-- LEFT SIDE -->
                            <div class="col-lg-6 d-none d-lg-block bg-login-image">
                                <div class="h-100 d-flex align-items-center justify-content-center">
                                    <div class="text-center text-white">
                                        <i class="fas fa-cut fa-4x mb-3"></i>
                                        <h2 class="font-weight-bold">
                                            GHD Barbershop
                                        </h2>
                                        <p>
                                            Admin Panel
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- LOGIN FORM -->
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">
                                            Welcome Back!
                                        </h1>
                                        <p class="small text-muted">
                                            Login ke Dashboard GHD Barbershop
                                        </p>
                                    </div>
                                    <?php
                                    if (isset($_GET['pesan'])) {
                                        if ($_GET['pesan'] == "gagal") {
                                            echo '
                                        <div class="alert alert-danger">
                                            Username atau password salah.
                                        </div>';
                                        } elseif ($_GET['pesan'] == "belum_login") {
                                            echo '
                                        <div class="alert alert-warning">
                                            Silakan login terlebih dahulu.
                                        </div>';
                                        }
                                    }
                                    ?>
                                    <form action="proses_login.php" method="POST">

                                        <div class="form-group">
                                            <input
                                                type="text"
                                                name="username"
                                                class="form-control"
                                                placeholder="Username"
                                                required>
                                        </div>

                                        <div class="form-group">
                                            <input
                                                type="password"
                                                name="password"
                                                class="form-control"
                                                placeholder="Password"
                                                required>
                                        </div>

                                        <button
                                            type="submit"
                                            class="btn btn-primary btn-user btn-block">

                                            Login

                                        </button>

                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <span class="small text-muted">
                                            GHD Barbershop Admin Panel
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    
</body>

</html>