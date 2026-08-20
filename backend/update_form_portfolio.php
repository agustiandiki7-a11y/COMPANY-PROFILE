<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php?pesan=belum_login");
    exit;
}

include "connection.php";


$id_portfolio = $_GET['id_portfolio'] ?? '';

if ($id_portfolio == '') {
    header("Location: tabel_portfolio.php");
    exit;
}


$id_portfolio = mysqli_real_escape_string(
    $koneksi,
    $id_portfolio
);


$query = mysqli_query(
    $koneksi,

    "SELECT * FROM portfolio
     WHERE id_portfolio = '$id_portfolio'"
);


if (!$query) {
    die("Query gagal: " . mysqli_error($koneksi));
}


$data = mysqli_fetch_assoc($query);


if (!$data) {

    echo "<script>

        alert('Data portfolio tidak ditemukan!');

        window.location='tabel_portfolio.php';

    </script>";

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

                <h1 class="h3 mb-4 text-gray-800">
                    Update Portfolio
                </h1>

                <div class="card shadow mb-4">

                    <div class="card-header py-3">

                        <h6 class="m-0 font-weight-bold text-primary">
                            Edit Data Portfolio
                        </h6>

                    </div>

                    <div class="card-body">

                        <form
                            action="action_update_portfolio.php"
                            method="POST"
                            enctype="multipart/form-data"
                        >

                            <!-- ID -->
                            <input
                                type="hidden"
                                name="id_portfolio"
                                value="<?= $data['id_portfolio']; ?>"
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
                                    value="<?= htmlspecialchars($data['title']); ?>"
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
                                ><?= htmlspecialchars($data['description']); ?></textarea>

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
                                    value="<?= htmlspecialchars($data['category']); ?>"
                                    required
                                >

                            </div>

                            <!-- Current Image -->
                            <div class="form-group">

                                <label>
                                    Current Image
                                </label>

                                <br>

                                <?php if (
                                    !empty($data['image']) &&
                                    file_exists("foto/" . $data['image'])
                                ): ?>

                                    <img
                                        src="foto/<?= htmlspecialchars($data['image']); ?>"
                                        width="180"
                                        height="120"
                                        style="object-fit: cover;"
                                        class="rounded mb-3"
                                    >

                                <?php else: ?>

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

                                <small class="form-text text-muted">
                                    Kosongkan jika tidak ingin mengganti gambar.
                                </small>

                            </div>

                            <hr>

                            <a
                                href="tabel_portfolio.php"
                                class="btn btn-secondary"
                            >

                                <i class="fas fa-arrow-left"></i>
                                Kembali

                            </a>

                            <button
                                type="submit"
                                class="btn btn-success"
                            >

                                <i class="fas fa-save"></i>
                                Update

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