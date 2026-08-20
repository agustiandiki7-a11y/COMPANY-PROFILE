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

        <?php include "sidebar.php"; ?>

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <?php include "topbar.php"; ?>

                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">

                        <h1 class="h3 mb-0 text-gray-800">
                            Tambah Testimonials
                        </h1>

                    </div>


                    <!-- Form Card -->
                    <div class="card shadow mb-4">

                        <div class="card-header py-3">

                            <h6 class="m-0 font-weight-bold text-primary">
                                Form Tambah Testimonials
                            </h6>

                        </div>


                        <div class="card-body">

                            <form
                                action="action_insert_testimonials.php"
                                method="POST"
                                enctype="multipart/form-data"
                            >

                                <!-- Name -->
                                <div class="form-group">

                                    <label>
                                        Name
                                    </label>

                                    <input
                                        type="text"
                                        name="name"
                                        class="form-control"
                                        placeholder="Masukkan nama"
                                        required
                                    >

                                </div>


                                <!-- Message -->
                                <div class="form-group">

                                    <label>
                                        Message
                                    </label>

                                    <textarea
                                        name="message"
                                        class="form-control"
                                        rows="4"
                                        placeholder="Masukkan testimonial"
                                        required
                                    ></textarea>

                                </div>


                                <!-- Rating -->
                                <div class="form-group">

                                    <label>
                                        Rating
                                    </label>

                                    <select
                                        name="rating"
                                        class="form-control"
                                        required
                                    >

                                        <option value="">
                                            -- Pilih Rating --
                                        </option>

                                        <option value="1">
                                            1 Star
                                        </option>

                                        <option value="2">
                                            2 Stars
                                        </option>

                                        <option value="3">
                                            3 Stars
                                        </option>

                                        <option value="4">
                                            4 Stars
                                        </option>

                                        <option value="5">
                                            5 Stars
                                        </option>

                                    </select>

                                </div>


                                <!-- Photo -->
                                <div class="form-group">

                                    <label>
                                        Photo
                                    </label>

                                    <input
                                        type="file"
                                        name="photo"
                                        class="form-control-file"
                                        accept="image/*"
                                    >

                                    <small class="text-muted">
                                        Format JPG, JPEG, PNG.
                                    </small>

                                </div>


                                <!-- Button -->

                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >
                                    <i class="fas fa-save"></i>
                                    Simpan
                                </button>


                                <a
                                    href="tabel_testimonials.php"
                                    class="btn btn-secondary"
                                >
                                    Kembali
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