<?php

include "connection.php";

session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php?pesan=belum_login");
    exit;
}

$id_barber = $_GET['id_barber'] ?? 0;

$id_barber = mysqli_real_escape_string(
    $koneksi,
    $id_barber
);

$query = mysqli_query(
    $koneksi,
    "SELECT * FROM barbers
     WHERE id_barber = '$id_barber'"
);

if (mysqli_num_rows($query) == 0) {
    die("Data barber tidak ditemukan.");
}

$barber = mysqli_fetch_assoc($query);

?>

<?php include "header.php"; ?>

<body id="page-top">

<div id="wrapper">

    <?php include "sidebar.php"; ?>

    <div id="content-wrapper" class="d-flex flex-column">

        <div id="content">

            <?php include "topbar.php"; ?>

            <div class="container-fluid">

                <div class="d-sm-flex align-items-center mb-4">

                    <h1 class="h3 mb-0 text-gray-800">
                        Update Barber
                    </h1>

                </div>

                <div class="card shadow mb-4">

                    <div class="card-header py-3">

                        <h6 class="m-0 font-weight-bold text-primary">
                            Form Update Barber
                        </h6>

                    </div>

                    <div class="card-body">

                        <form
                            action="action_update_barbers.php"
                            method="POST"
                            enctype="multipart/form-data"
                        >

                            <!-- ID -->
                            <input
                                type="hidden"
                                name="id_barber"
                                value="<?= $barber['id_barber']; ?>"
                            >

                            <!-- Name -->
                            <div class="form-group">

                                <label>Name</label>

                                <input
                                    type="text"
                                    name="name"
                                    class="form-control"
                                    value="<?= htmlspecialchars($barber['name']); ?>"
                                    required
                                >

                            </div>

                            <!-- Specialty -->
                            <div class="form-group">

                                <label>Specialty</label>

                                <input
                                    type="text"
                                    name="specialty"
                                    class="form-control"
                                    value="<?= htmlspecialchars($barber['specialty']); ?>"
                                    required
                                >

                            </div>

                            <!-- Description -->
                            <div class="form-group">

                                <label>Description</label>

                                <textarea
                                    name="description"
                                    class="form-control"
                                    rows="5"
                                ><?= htmlspecialchars($barber['description']); ?></textarea>

                            </div>

                            <!-- Current Image -->
                            <div class="form-group">

                                <label>Current Image</label>

                                <br>

                                <?php if (!empty($barber['image']) && file_exists("foto/" . $barber['image'])) : ?>

                                    <img
                                        src="foto/<?= htmlspecialchars($barber['image']); ?>"
                                        width="100"
                                        height="100"
                                        style="object-fit:cover;"
                                        class="rounded"
                                    >

                                <?php else : ?>

                                    <p class="text-muted">
                                        Belum ada gambar.
                                    </p>

                                <?php endif; ?>

                            </div>

                            <!-- New Image -->
                            <div class="form-group">

                                <label>
                                    Ganti Image
                                </label>

                                <input
                                    type="file"
                                    name="image"
                                    class="form-control-file"
                                    accept="image/*"
                                >

                            </div>

                            <button
                                type="submit"
                                class="btn btn-success">

                                <i class="fas fa-save"></i>
                                Update

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