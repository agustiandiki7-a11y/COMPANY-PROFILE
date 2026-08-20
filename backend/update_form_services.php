<?php
session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php?pesan=belum_login");
    exit;
}

include "connection.php";

// Ambil ID dari URL
$id_service = $_GET['id_service'] ?? '';

if ($id_service == '') {
    header("Location: tabel_services.php");
    exit;
}

// Ambil data services berdasarkan ID
$query = mysqli_query(
    $koneksi,
    "SELECT * FROM services WHERE id_service = '$id_service'"
);

if (!$query) {
    die("Query gagal: " . mysqli_error($koneksi));
}

$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>
            alert('Data services tidak ditemukan!');
            window.location='tabel_services.php';
          </script>";
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
                            Update Services
                        </h1>

                        <a href="tabel_services.php"
                            class="btn btn-secondary btn-sm">

                            <i class="fas fa-arrow-left"></i>
                            Kembali

                        </a>

                    </div>


                    <!-- Card -->
                    <div class="card shadow mb-4">

                        <div class="card-header py-3">

                            <h6 class="m-0 font-weight-bold text-primary">
                                Form Update Services
                            </h6>

                        </div>


                        <div class="card-body">

                            <form action="action_update_services.php"
                                method="POST"
                                enctype="multipart/form-data">

                                <!-- ID -->
                                <input
                                    type="hidden"
                                    name="id_service"
                                    value="<?php echo $data['id_service']; ?>">


                                <!-- NAME -->
                                <div class="form-group">

                                    <label>
                                        Nama Services
                                    </label>

                                    <input
                                        type="text"
                                        name="name"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($data['name']); ?>"
                                        required>

                                </div>


                                <!-- DESCRIPTION -->
                                <div class="form-group">

                                    <label>
                                        Deskripsi
                                    </label>

                                    <textarea
                                        name="description"
                                        class="form-control"
                                        rows="4"><?php echo htmlspecialchars($data['description']); ?></textarea>

                                </div>


                                <!-- PRICE -->
                                <div class="form-group">

                                    <label>
                                        Harga
                                    </label>

                                    <input
                                        type="number"
                                        name="price"
                                        class="form-control"
                                        value="<?php echo $data['price']; ?>"
                                        min="0"
                                        required>

                                </div>


                                <!-- DURATION -->
                                <div class="form-group">

                                    <label>
                                        Durasi
                                    </label>

                                    <input
                                        type="text"
                                        name="duration"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($data['duration']); ?>"
                                        placeholder="Contoh: 30 Menit">

                                </div>


                                <!-- IMAGE -->
                                <div class="form-group">

                                    <label>
                                        Gambar Services
                                    </label>

                                    <br>

                                    <?php if (!empty($data['image']) && file_exists("foto/" . $data['image'])): ?>

                                        <img
                                            src="foto/<?php echo htmlspecialchars($data['image']); ?>"
                                            width="150"
                                            class="img-thumbnail mb-3"
                                            alt="Gambar Services">

                                        <br>

                                    <?php else: ?>

                                        <p class="text-muted">
                                            Belum ada gambar.
                                        </p>

                                    <?php endif; ?>


                                    <input
                                        type="file"
                                        name="image"
                                        class="form-control-file"
                                        accept=".jpg,.jpeg,.png,.webp">

                                    <small class="form-text text-muted">
                                        Kosongkan jika tidak ingin mengganti gambar.
                                    </small>

                                </div>


                                <hr>


                                <!-- BUTTON -->
                                <button
                                    type="submit"
                                    class="btn btn-success">

                                    <i class="fas fa-save"></i>
                                    Update

                                </button>


                                <a
                                    href="tabel_services.php"
                                    class="btn btn-secondary">

                                    Batal

                                </a>

                            </form>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Footer -->
            <?php include "footer.php"; ?>

        </div>

    </div>


    <!-- Bottom -->
    <?php include "buttom.php"; ?>

</body>

</html>