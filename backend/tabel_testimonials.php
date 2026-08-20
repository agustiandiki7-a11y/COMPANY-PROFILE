<?php
// =====================================================
// TABEL TESTIMONIALS
// Mengambil data dari tabel `testimonials`
// =====================================================

include "connection.php";

session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php?pesan=belum_login");
    exit;
}

// Ambil seluruh data testimonials
$query = mysqli_query(
    $koneksi,
    "SELECT * FROM testimonials ORDER BY id_testimonial DESC"
);

if (!$query) {
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<?php include "header.php"; ?>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include "sidebar.php"; ?>
        <!-- End Sidebar -->


        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include "topbar.php"; ?>
                <!-- End Topbar -->


                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">

                        <h1 class="h3 mb-0 text-gray-800">
                            Testimonials
                        </h1>

                    </div>


                    <!-- Card -->
                    <div class="card shadow mb-4">

                        <!-- Card Header -->
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">

                            <h6 class="m-0 font-weight-bold text-primary">
                                Data Testimonials
                            </h6>

                            <a href="form_testimonials.php"
                               class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i>
                                ADD
                            </a>

                        </div>


                        <!-- Card Body -->
                        <div class="card-body">

                            <div class="table-responsive">

                                <table class="table table-bordered table-hover"
                                       width="100%"
                                       cellspacing="0">

                                    <thead class="thead-light">

                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Name</th>
                                            <th>Message</th>
                                            <th width="10%">Rating</th>
                                            <th width="12%">Photo</th>
                                            <th width="20%">Action</th>
                                        </tr>

                                    </thead>


                                    <tbody>

                                        <?php
                                        $no = 1;

                                        while ($tampil = mysqli_fetch_assoc($query)) {
                                        ?>

                                            <tr>

                                                <!-- No -->
                                                <td>
                                                    <?= $no++; ?>
                                                </td>


                                                <!-- Name -->
                                                <td>
                                                    <?= htmlspecialchars($tampil['name']); ?>
                                                </td>


                                                <!-- Message -->
                                                <td>
                                                    <?= htmlspecialchars($tampil['message']); ?>
                                                </td>


                                                <!-- Rating -->
                                                <td>

                                                    <?php
                                                    $rating = (int) $tampil['rating'];

                                                    for ($i = 1; $i <= 5; $i++) {

                                                        if ($i <= $rating) {
                                                            echo '<i class="fas fa-star text-warning"></i>';
                                                        } else {
                                                            echo '<i class="far fa-star text-muted"></i>';
                                                        }

                                                    }
                                                    ?>

                                                </td>


                                                <!-- Photo -->
                                                <td class="text-center">

                                                    <?php if (!empty($tampil['photo']) && file_exists("foto/" . $tampil['photo'])): ?>

                                                        <img
                                                            src="foto/<?= htmlspecialchars($tampil['photo']); ?>"
                                                            width="55"
                                                            height="55"
                                                            class="rounded-circle"
                                                            style="object-fit: cover;"
                                                            alt="Photo"
                                                        >

                                                    <?php else: ?>

                                                        <i class="fas fa-user-circle fa-3x text-secondary"></i>

                                                    <?php endif; ?>

                                                </td>


                                                <!-- Action -->
                                                <td>

                                                    <a
                                                        href="update_form_testimonials.php?id_testimonial=<?= $tampil['id_testimonial']; ?>"
                                                        class="btn btn-success btn-sm"
                                                    >
                                                        <i class="fas fa-edit"></i>
                                                        Update
                                                    </a>


                                                    <a
                                                        href="delete_testimonials.php?id_testimonial=<?= $tampil['id_testimonial']; ?>"
                                                        class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Yakin ingin menghapus testimonial ini?')"
                                                    >
                                                        <i class="fas fa-trash"></i>
                                                        Delete
                                                    </a>

                                                </td>

                                            </tr>

                                        <?php
                                        }
                                        ?>

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
            <!-- End Footer -->

        </div>
        <!-- End Content Wrapper -->

    </div>
    <!-- End Page Wrapper -->


    <!-- Scroll to Top -->
    <?php include "buttom.php"; ?>

</body>

</html>