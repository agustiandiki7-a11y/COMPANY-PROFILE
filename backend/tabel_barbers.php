<?php
// KETERANGAN ALUR DATA:
// Halaman ini menampilkan data barber dari tabel `barbers`.

// Menghubungkan database
include "connection.php";

// Mengecek session login
session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php?pesan=belum_login");
    exit;
}

// Mengambil seluruh data barber
$query_barbers = mysqli_query(
    $koneksi,
    "SELECT * FROM barbers ORDER BY id_barber DESC"
);

if (!$query_barbers) {
    die("Query gagal: " . mysqli_error($koneksi));
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
                        Barbers
                    </h1>

                    <a href="form_barbers.php"
                       class="btn btn-primary btn-sm shadow-sm">
                        <i class="fas fa-plus fa-sm text-white-50"></i>
                        Add Barber
                    </a>
                </div>

                <!-- Card -->
                <div class="card shadow mb-4">

                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            Data Barbers
                        </h6>
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">

                            <table class="table table-bordered table-hover"
                                   width="100%"
                                   cellspacing="0">

                                <thead class="thead-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="15%">Image</th>
                                        <th>Name</th>
                                        <th>Specialty</th>
                                        <th>Description</th>
                                        <th width="20%">Action</th>
                                    </tr>
                                </thead>

                                <tbody>

                                <?php
                                $no = 1;

                                while ($barber = mysqli_fetch_assoc($query_barbers)) :
                                ?>

                                    <tr>

                                        <!-- Nomor -->
                                        <td>
                                            <?= $no++; ?>
                                        </td>

                                        <!-- Image -->
                                        <td class="text-center">

                                            <?php if (!empty($barber['image']) && file_exists("foto/" . $barber['image'])) : ?>

                                                <img
                                                    src="foto/<?= htmlspecialchars($barber['image']); ?>"
                                                    alt="Barber"
                                                    width="70"
                                                    height="70"
                                                    style="object-fit: cover;"
                                                    class="rounded-circle"
                                                >

                                            <?php else : ?>

                                                <div
                                                    class="bg-light rounded-circle d-flex align-items-center justify-content-center"
                                                    style="width:70px;height:70px;"
                                                >
                                                    <i class="fas fa-user fa-2x text-gray-400"></i>
                                                </div>

                                            <?php endif; ?>

                                        </td>

                                        <!-- Name -->
                                        <td>
                                            <strong>
                                                <?= htmlspecialchars($barber['name']); ?>
                                            </strong>
                                        </td>

                                        <!-- Specialty -->
                                        <td>
                                            <span class="badge badge-primary">
                                                <?= htmlspecialchars($barber['specialty']); ?>
                                            </span>
                                        </td>

                                        <!-- Description -->
                                        <td>
                                            <?= htmlspecialchars($barber['description']); ?>
                                        </td>

                                        <!-- Action -->
                                        <td>

                                            <a href="update_form_barbers.php?id_barber=<?= $barber['id_barber']; ?>"
                                               class="btn btn-success btn-sm">

                                                <i class="fas fa-edit"></i>
                                                Update

                                            </a>

                                            <a href="delete_barbers.php?id_barber=<?= $barber['id_barber']; ?>"
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('Yakin ingin menghapus barber ini?');">

                                                <i class="fas fa-trash"></i>
                                                Delete

                                            </a>

                                        </td>

                                    </tr>

                                <?php endwhile; ?>

                                <?php if (mysqli_num_rows($query_barbers) == 0) : ?>

                                    <tr>
                                        <td colspan="6"
                                            class="text-center text-muted py-4">

                                            <i class="fas fa-user-slash fa-2x mb-2"></i>

                                            <br>

                                            Belum ada data barber.

                                        </td>
                                    </tr>

                                <?php endif; ?>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End Main Content -->

        <!-- Footer -->
        <?php include "footer.php"; ?>

    </div>
    <!-- End Content Wrapper -->

</div>
<!-- End Page Wrapper -->

<?php include "buttom.php"; ?>

</body>
</html>