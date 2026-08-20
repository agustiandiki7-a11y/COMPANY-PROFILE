<?php

// ============================================================
// KETERANGAN ALUR DATA
// ============================================================
// Halaman ini digunakan untuk menampilkan seluruh data services
// dari tabel `services` pada database `ghd_barbershop`.
// ============================================================

session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login') {
    header("Location: login.php?pesan=belum_login");
    exit;
}


// Menghubungkan database
include "connection.php";


// ============================================================
// MENGAMBIL DATA SERVICES
// ============================================================

$select_services = mysqli_query(
    $koneksi,
    "SELECT * FROM services ORDER BY id_service DESC"
);
// Cek query
if (!$select_services) {
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
                        <div>
                            <h1 class="h3 mb-1 text-gray-800">
                                Services
                            </h1>
                            <p class="mb-0 text-muted">
                                Kelola layanan GHD Barbershop
                            </p>
                        </div>
                        <!-- Tombol Tambah -->
                        <a href="form_services.php"
                           class="btn btn-primary shadow-sm">
                            <i class="fas fa-plus fa-sm text-white-50"></i>
                            Tambah Service
                        </a>
                    </div>
                    <!-- Card -->
                    <div class="card shadow mb-4">
                        <!-- Card Header -->
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                Data Services
                            </h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <!-- Responsive Table -->
                            <div class="table-responsive">
                                <table
                                    class="table table-bordered table-hover"
                                    width="100%"
                                    cellspacing="0"
                                >
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="5%">
                                                No
                                            </th>
                                            <th width="15%">
                                                Image
                                            </th>
                                            <th>
                                                Name
                                            </th>
                                            <th>
                                                Description
                                            </th>
                                            <th>
                                                Price
                                            </th>
                                            <th>
                                                Duration
                                            </th>
                                            <th width="18%">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        while ($tampil = mysqli_fetch_assoc($select_services)):
                                        ?>
                                          <tr>
                                                <!-- No -->
                                                <td>
                                                    <?php echo $no++; ?>
                                                </td>
                                                <!-- Image -->
                                                <td class="text-center">
                                                    <?php
                                                    if (
                                                        !empty($tampil['image']) &&
                                                        file_exists("foto/" . $tampil['image'])
                                                    ):
                                                    ?>
                                                        <img
                                                            src="foto/<?php echo htmlspecialchars($tampil['image']); ?>"
                                                            alt="<?php echo htmlspecialchars($tampil['name']); ?>"
                                                            width="80"
                                                            height="60"
                                                            style="object-fit: cover;"
                                                            class="rounded"
                                                        >
                                                    <?php else: ?>
                                                        <div
                                                            class="bg-light rounded d-flex align-items-center justify-content-center"
                                                            style="width:80px;height:60px;"
                                                        >
                                                            <i class="fas fa-image text-muted"></i>

                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <!-- Name -->
                                                <td>
                                                    <strong>
                                                        <?php
                                                        echo htmlspecialchars($tampil['name']);
                                                        ?>
                                                    </strong>
                                                </td>
                                                <!-- Description -->
                                                <td>
                                                    <?php
                                                    if (!empty($tampil['description'])) {
                                                        echo htmlspecialchars(
                                                            mb_strimwidth(
                                                                $tampil['description'],
                                                                0,
                                                                100,
                                                                "..."
                                                            )
                                                        );
                                                    } else {
                                                        echo '<span class="text-muted">Tidak ada deskripsi</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <!-- Price -->
                                                <td>
                                                    Rp
                                                    <?php
                                                    echo number_format(
                                                        $tampil['price'],
                                                        0,
                                                        ',',
                                                        '.'
                                                    );
                                                    ?>

                                                </td>


                                                <!-- Duration -->
                                                <td>

                                                    <?php

                                                    echo !empty($tampil['duration'])
                                                        ? htmlspecialchars($tampil['duration'])
                                                        : '-';

                                                    ?>

                                                </td>


                                                <!-- Action -->
                                                <td>


                                                    <!-- Edit -->
                                                    <a
                                                        href="update_form_services.php?id_service=<?php echo $tampil['id_service']; ?>"
                                                        class="btn btn-success btn-sm"
                                                    >

                                                        <i class="fas fa-edit"></i>
                                                        Update

                                                    </a>


                                                    <!-- Delete -->
                                                    <a
                                                        href="delete_services.php?id_service=<?php echo $tampil['id_service']; ?>"
                                                        class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Yakin ingin menghapus service ini?')"
                                                    >

                                                        <i class="fas fa-trash"></i>
                                                        Delete

                                                    </a>


                                                </td>


                                            </tr>


                                        <?php endwhile; ?>


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


    <!-- Scroll To Top -->
    <?php include "buttom.php"; ?>


</body>

</html>