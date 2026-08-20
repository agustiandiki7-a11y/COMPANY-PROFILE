<?php

include "connection.php";

session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php?pesan=belum_login");
    exit;
}

$query = mysqli_query(
    $koneksi,
    "SELECT * FROM pricing ORDER BY id_pricing DESC"
);

if (!$query) {
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
                        Pricing
                    </h1>

                    <a href="form_pricing.php"
                       class="btn btn-primary btn-sm">

                        <i class="fas fa-plus"></i>
                        Add Pricing

                    </a>

                </div>

                <!-- Card -->
                <div class="card shadow mb-4">

                    <div class="card-header py-3">

                        <h6 class="m-0 font-weight-bold text-primary">
                            Data Pricing
                        </h6>

                    </div>

                    <div class="card-body">

                        <div class="table-responsive">

                            <table class="table table-bordered table-hover"
                                   width="100%"
                                   cellspacing="0">

                                <thead class="thead-light">

                                    <tr>

                                        <th width="5%">
                                            No
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

                                        <th width="20%">
                                            Action
                                        </th>

                                    </tr>

                                </thead>

                                <tbody>

                                <?php

                                $no = 1;

                                while ($data = mysqli_fetch_assoc($query)):

                                ?>

                                    <tr>

                                        <td>
                                            <?= $no++; ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($data['name']); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($data['description']); ?>
                                        </td>

                                        <td>
                                            Rp
                                            <?= number_format(
                                                $data['price'],
                                                0,
                                                ',',
                                                '.'
                                            ); ?>
                                        </td>

                                        <td>

                                            <a
                                                href="update_form_pricing.php?id_pricing=<?= $data['id_pricing']; ?>"
                                                class="btn btn-success btn-sm">

                                                <i class="fas fa-edit"></i>
                                                Update

                                            </a>

                                            <a
                                                href="action_delete_pricing.php?id_pricing=<?= $data['id_pricing']; ?>"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin ingin menghapus pricing ini?');">

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

        </div>

        <!-- Footer -->
        <?php include "footer.php"; ?>

    </div>

</div>

<!-- Scroll to Top -->
<?php include "buttom.php"; ?>

</body>

</html>