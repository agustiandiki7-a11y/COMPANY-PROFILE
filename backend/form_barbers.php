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

    <div id="content-wrapper" class="d-flex flex-column">

        <div id="content">

            <!-- Topbar -->
            <?php include "topbar.php"; ?>

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center mb-4">

                    <h1 class="h3 mb-0 text-gray-800">
                        Add Barber
                    </h1>

                </div>

                <!-- Card -->
                <div class="card shadow mb-4">

                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            Form Tambah Barber
                        </h6>
                    </div>

                    <div class="card-body">

                        <form action="action_insert_barbers.php"
                              method="POST"
                              enctype="multipart/form-data">

                            <!-- Name -->
                            <div class="form-group">

                                <label>
                                    Name
                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    class="form-control"
                                    placeholder="Masukkan nama barber"
                                    required
                                >

                            </div>

                            <!-- Specialty -->
                            <div class="form-group">

                                <label>
                                    Specialty
                                </label>

                                <input
                                    type="text"
                                    name="specialty"
                                    class="form-control"
                                    placeholder="Contoh: Haircut, Fade, Styling"
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
                                    placeholder="Masukkan deskripsi barber"
                                ></textarea>

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
                                >

                                <small class="form-text text-muted">
                                    Format JPG, JPEG, PNG.
                                </small>

                            </div>

                            <!-- Button -->
                            <button
                                type="submit"
                                class="btn btn-primary">

                                <i class="fas fa-save"></i>
                                Save

                            </button>

                            <a
                                href="tabel_barbers.php"
                                class="btn btn-secondary">

                                <i class="fas fa-arrow-left"></i>
                                Back

                            </a>

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