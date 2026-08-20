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

                    <h1 class="h3 mb-4 text-gray-800">
                        Add Profile
                    </h1>

                    <div class="card shadow mb-4">

                        <div class="card-header py-3">

                            <h6 class="m-0 font-weight-bold text-primary">
                                Tambah Profile GHD Barbershop
                            </h6>

                        </div>

                        <div class="card-body">

                            <form
                                action="action_insert_profile.php"
                                method="POST"
                                enctype="multipart/form-data"
                            >

                                <!-- Name -->
                                <div class="form-group">

                                    <label>Name</label>

                                    <input
                                        type="text"
                                        name="name"
                                        class="form-control"
                                        placeholder="GHD Barbershop"
                                        required
                                    >

                                </div>

                                <!-- Description -->
                                <div class="form-group">

                                    <label>Description</label>

                                    <textarea
                                        name="description"
                                        class="form-control"
                                        rows="4"
                                        placeholder="Masukkan deskripsi barbershop..."
                                    ></textarea>

                                </div>

                                <!-- Address -->
                                <div class="form-group">

                                    <label>Address</label>

                                    <textarea
                                        name="address"
                                        class="form-control"
                                        rows="3"
                                        placeholder="Masukkan alamat barbershop..."
                                    ></textarea>

                                </div>

                                <!-- Phone -->
                                <div class="form-group">

                                    <label>Phone</label>

                                    <input
                                        type="text"
                                        name="phone"
                                        class="form-control"
                                        placeholder="08xxxxxxxxxx"
                                    >

                                </div>

                                <!-- Email -->
                                <div class="form-group">

                                    <label>Email</label>

                                    <input
                                        type="email"
                                        name="email"
                                        class="form-control"
                                        placeholder="info@ghdbarbershop.com"
                                    >

                                </div>

                                <!-- Instagram -->
                                <div class="form-group">

                                    <label>Instagram</label>

                                    <input
                                        type="text"
                                        name="instagram"
                                        class="form-control"
                                        placeholder="@ghdbarbershop"
                                    >

                                </div>

                                <!-- Opening Hours -->
                                <div class="form-group">

                                    <label>Opening Hours</label>

                                    <input
                                        type="text"
                                        name="opening_hours"
                                        class="form-control"
                                        placeholder="09:00 - 21:00"
                                    >

                                </div>

                                <!-- Logo -->
                                <div class="form-group">

                                    <label>Logo</label>

                                    <input
                                        type="file"
                                        name="logo"
                                        class="form-control-file"
                                        accept=".jpg,.jpeg,.png,.webp"
                                    >

                                    <small class="text-muted">
                                        Format: JPG, JPEG, PNG, WEBP
                                    </small>

                                </div>

                                <hr>

                                <a
                                    href="tabel_profile.php"
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