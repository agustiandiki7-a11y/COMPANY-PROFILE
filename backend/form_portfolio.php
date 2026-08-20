<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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

        <div id="content">

            <!-- Topbar -->
            <?php include "topbar.php"; ?>

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center mb-4">

                    <h1 class="h3 mb-0 text-gray-800">
                        Add Portfolio
                    </h1>

                </div>

                <!-- Card -->
                <div class="card shadow mb-4">

                    <div class="card-header py-3">

                        <h6 class="m-0 font-weight-bold text-primary">
                            Tambah Data Portfolio
                        </h6>

                    </div>

                    <div class="card-body">

                        <form
                            action="action_insert_portfolio.php"
                            method="POST"
                            enctype="multipart/form-data"
                        >

                            <!-- Title -->
                            <div class="form-group">

                                <label>
                                    Title
                                </label>

                                <input
                                    type="text"
                                    name="title"
                                    class="form-control"
                                    placeholder="Contoh: Classic Haircut"
                                    required
                                >

                            </div>

                            <!-- Description -->
                            <div class="form-group">

                                <label>
                                    Description
                                </label>

                                <textarea
                                    name="description"
                                    class="form-control"
                                    rows="5"
                                    placeholder="Masukkan deskripsi portfolio"
                                ></textarea>

                            </div>

                            <!-- Category -->
                            <div class="form-group">

                                <label>
                                    Category
                                </label>

                                <input
                                    type="text"
                                    name="category"
                                    class="form-control"
                                    placeholder="Contoh: Haircut"
                                    required
                                >

                            </div>

                            <!-- Image -->
                            <div class="form-group">

                                <label>
                                    Image
                                </label>

                                <input
                                    type="file"
                                    name="image"
                                    class="form-control-file"
                                    accept="image/*"
                                    required
                                >

                                <small class="form-text text-muted">
                                    Format: JPG, JPEG, PNG, WEBP. Maksimal 2MB.
                                </small>

                            </div>

                            <hr>

                            <!-- Button -->
                            <a
                                href="tabel_portfolio.php"
                                class="btn btn-secondary"
                            >

                                <i class="fas fa-arrow-left"></i>
                                Kembali

                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >

                                <i class="fas fa-save"></i>
                                Simpan

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

        <?php include "footer.php"; ?>

    </div>

</div>

<?php include "buttom.php"; ?>

</body>

</html>