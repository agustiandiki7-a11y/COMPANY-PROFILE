<?php
session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php?pesan=belum_login");
    exit;
}

include "connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name        = mysqli_real_escape_string($koneksi, $_POST['name']);
    $description = mysqli_real_escape_string($koneksi, $_POST['description']);
    $price       = mysqli_real_escape_string($koneksi, $_POST['price']);
    $duration    = mysqli_real_escape_string($koneksi, $_POST['duration']);

    // =========================
    // UPLOAD GAMBAR
    // =========================
    $image = "";

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {

        $nama_file = $_FILES['image']['name'];
        $tmp_file  = $_FILES['image']['tmp_name'];

        $ekstensi = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));

        $ekstensi_diperbolehkan = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ekstensi, $ekstensi_diperbolehkan)) {
            echo "<script>
                    alert('Format gambar harus JPG, JPEG, PNG, atau WEBP!');
                    window.history.back();
                  </script>";
            exit;
        }

        // Nama file dibuat unik
        $image = time() . '_' . $nama_file;

        // Folder foto
        if (!is_dir("foto")) {
            mkdir("foto", 0777, true);
        }

        move_uploaded_file(
            $tmp_file,
            "foto/" . $image
        );
    }

    // =========================
    // INSERT DATA
    // =========================

    $query = mysqli_query(
        $koneksi,
        "INSERT INTO services
        (name, description, price, duration, image)
        VALUES
        ('$name', '$description', '$price', '$duration', '$image')"
    );

    if ($query) {

        echo "<script>
                alert('Data services berhasil ditambahkan!');
                window.location='tabel_services.php';
              </script>";
    } else {

        echo "<script>
                alert('Data gagal ditambahkan: " . mysqli_error($koneksi) . "');
                window.history.back();
              </script>";
    }
}
?>

<?php include "header.php"; ?>

<body id="page-top">

    <div id="wrapper">

        <!-- SIDEBAR -->
        <?php include "sidebar.php"; ?>

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <!-- TOPBAR -->
                <?php include "topbar.php"; ?>

                <div class="container-fluid">

                    <!-- PAGE HEADING -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">

                        <h1 class="h3 mb-0 text-gray-800">
                            Tambah Services
                        </h1>

                        <a href="tabel_services.php"
                            class="btn btn-secondary btn-sm">

                            <i class="fas fa-arrow-left"></i>
                            Kembali

                        </a>

                    </div>

                    <!-- FORM CARD -->
                    <div class="card shadow mb-4">

                        <div class="card-header py-3">

                            <h6 class="m-0 font-weight-bold text-primary">
                                Form Tambah Services
                            </h6>

                        </div>

                        <div class="card-body">

                            <form method="POST"
                                enctype="multipart/form-data">

                                <!-- NAME -->
                                <div class="form-group">

                                    <label>
                                        Nama Services
                                    </label>
 
                                    <input
                                        type="text"
                                        name="name"
                                        class="form-control"
                                        placeholder="Contoh: Haircut"
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
                                        rows="4"
                                        placeholder="Masukkan deskripsi layanan"></textarea>

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
                                        placeholder="Contoh: 50000"
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
                                        placeholder="Contoh: 30 Menit">

                                </div>


                                <!-- IMAGE -->
                                <div class="form-group">

                                    <label>
                                        Gambar Services
                                    </label>

                                    <input
                                        type="file"
                                        name="image"
                                        class="form-control-file"
                                        accept=".jpg,.jpeg,.png,.webp">

                                    <small class="form-text text-muted">
                                        Format: JPG, JPEG, PNG, WEBP
                                    </small>

                                </div>


                                <hr>


                                <!-- BUTTON -->
                                <button
                                    type="submit"
                                    class="btn btn-primary">

                                    <i class="fas fa-save"></i>
                                    Simpan

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


            <!-- FOOTER -->
            <?php include "footer.php"; ?>

        </div>

    </div>


    <!-- BOTTOM -->
    <?php include "buttom.php"; ?>

</body>

</html>