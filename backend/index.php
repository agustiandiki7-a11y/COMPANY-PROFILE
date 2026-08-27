<?php
session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login") {
    header("Location: login.php?pesan=belum_login");
    exit;
}
    
include "connection.php";

// Hitung statistik untuk dashboard
$q_book = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM bookings"));
$q_cust = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM customers"));
$q_serv = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM services"));

include "header.php";
?>
<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
       <?php include "sidebar.php"?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include "topbar.php"?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>

                    <!-- SELAMAT DATANG CARD -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <h3 class="text-dark font-weight-bold">SELAMAT DATANG, <?php echo strtoupper(htmlspecialchars($_SESSION['username'] ?? 'Admin')); ?>!</h3>
                            <p class="text-muted mb-0">Ini adalah pusat kendali GHD Barbershop. Silakan gunakan menu di sebelah kiri untuk mengelola booking, layanan, dan data pelanggan.</p>
                        </div>
                    </div>

                    <!-- ROW STATISTIK (KOTAK INFORMASI) -->
                    <div class="row">
                        <!-- Total Booking -->
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Pesanan Booking</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $q_book['total']; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pelanggan Terdaftar -->
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Pelanggan Terdaftar</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $q_cust['total']; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Jenis Layanan -->
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Jenis Layanan</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $q_serv['total']; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-cut fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END ROW STATISTIK -->

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
          <?php include "footer.php" ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
   <?php include "buttom.php"?>

</body>

</html>