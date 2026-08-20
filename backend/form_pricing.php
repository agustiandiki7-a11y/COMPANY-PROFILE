<?php

session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php?pesan=belum_login");
    exit;
}

?>

<?php include "header.php"; ?>

<body id="page-top">

<div id="wrapper">

    <!-- Sidebar -->
    <?php include "sidebar.php"; ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <?php include "topbar.php"; ?>

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">

                    <h1 class="h3 mb-0 text-gray-800">
                        Add Pricing
                    </h1>

                </div>

                <!-- Card -->
                <div class="card shadow mb-4">

                    <div class="card-header py-3">

                        <h6 class="m-0 font-weight-bold text-primary">
                            Tambah Pricing
                        </h6>

                    </div>

                    <div class="card-body">

                        <form action="action_insert_pricing.php"
                              method="POST">

                            <!-- Name -->
                            <div class="form-group">

                                <label for="name">
                                    Name
                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    id="name"
                                    class="form-control"
                                    placeholder="Contoh: Haircut"
                                    required
                                >

                            </div>


                            <!-- Description -->
                            <div class="form-group">

                                <label for="description">
                                    Description
                                </label>

                                <textarea
                                    name="description"
                                    id="description"
                                    class="form-control"
                                    rows="4"
                                    placeholder="Deskripsi layanan..."
                                ></textarea>

                            </div>


                            <!-- Price -->
                            <div class="form-group">

                                <label for="price">
                                    Price
                                </label>

                                <div class="input-group">

                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            Rp
                                        </span>
                                    </div>

                                    <input
                                        type="number"
                                        name="price"
                                        id="price"
                                        class="form-control"
                                        placeholder="50000"
                                        min="0"
                                        required
                                    >

                                </div>

                            </div>


                            <!-- Duration -->
                            <div class="form-group">

                                <label for="duration">
                                    Duration
                                </label>

                                <input
                                    type="text"
                                    name="duration"
                                    id="duration"
                                    class="form-control"
                                    placeholder="Contoh: 30 menit"
                                >

                            </div>


                            <hr>


                            <!-- Button -->

                            <a href="tabel_pricing.php"
                               class="btn btn-secondary">

                                <i class="fas fa-arrow-left"></i>
                                Kembali

                            </a>


                            <button
                                type="submit"
                                class="btn btn-primary">

                                <i class="fas fa-save"></i>
                                Simpan

                            </button>

                        </form>

                    </div>

                </div>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->


        <!-- Footer -->
        <?php include "footer.php"; ?>

    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->


<?php include "buttom.php"; ?>

</body>
</html>