<?php

include "connection.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php?pesan=belum_login");
    exit;
}

/*
|--------------------------------------------------------------------------
| AMBIL DATA PORTFOLIO
|--------------------------------------------------------------------------
*/

$query = mysqli_query(
    $koneksi,
    "SELECT * FROM portfolio ORDER BY id_portfolio DESC"
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
                            Portfolio
                        </h1>

                        <a href="form_portfolio.php"
                            class="btn btn-primary btn-sm">

                            <i class="fas fa-plus"></i>
                            Add Portfolio

                        </a>

                    </div>

                    <!-- Card -->
                    <div class="card shadow mb-4">

                        <div class="card-header py-3">

                            <h6 class="m-0 font-weight-bold text-primary">
                                Data Portfolio
                            </h6>

                        </div>

                        <div class="card-body">

                            <div class="table-responsive">

                                <table
                                    class="table table-bordered table-hover"
                                    width="100%"
                                    cellspacing="0">

                                    <thead class="thead-light">

                                        <tr>

                                            <th width="5%">
                                                No
                                            </th>

                                            <th width="15%">
                                                Image
                                            </th>

                                            <th>
                                                Title
                                            </th>

                                            <th>
                                                Description
                                            </th>

                                            <th>
                                                Category
                                            </th>

                                            <th width="18%">
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

                                                <!-- No -->
                                                <td>
                                                    <?= $no++; ?>
                                                </td>

                                                <!-- Image -->
                                                <td class="text-center">

                                                    <?php if (
                                                        !empty($data['image']) &&
                                                        file_exists("foto/" . $data['image'])
                                                    ): ?>

                                                        <img
                                                            src="foto/<?= htmlspecialchars($data['image']); ?>"
                                                            alt="<?= htmlspecialchars($data['title']); ?>"
                                                            width="100"
                                                            height="70"
                                                            style="object-fit: cover;"
                                                            class="rounded">

                                                    <?php else: ?>

                                                        <span class="text-muted">
                                                            No Image
                                                        </span>

                                                    <?php endif; ?>

                                                </td>

                                                <!-- Title -->
                                                <td>
                                                    <strong>
                                                        <?= htmlspecialchars($data['title']); ?>
                                                    </strong>
                                                </td>

                                                <!-- Description -->
                                                <td>
                                                    <?= htmlspecialchars($data['description']); ?>
                                                </td>

                                                <!-- Category -->
                                                <td>

                                                    <span class="badge badge-info">
                                                        <?= htmlspecialchars($data['category']); ?>
                                                    </span>

                                                </td>

                                                <!-- Action -->
                                                <td>

                                                    <a
                                                        href="update_form_portfolio.php?id_portfolio=<?= $data['id_portfolio']; ?>"
                                                        class="btn btn-success btn-sm">

                                                        <i class="fas fa-edit"></i>
                                                        Update

                                                    </a>

                                                    <a
                                                        href="action_delete_portfolio.php?id_portfolio=<?= $data['id_portfolio']; ?>"
                                                        class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Yakin ingin menghapus portfolio ini?');">

                                                        <i class="fas fa-trash"></i>
                                                        Delete

                                                    </a>

                                                </td>

                                            </tr>

                                        <?php endwhile; ?>

                                    </tbody>

                                </table>

                            </div>

                        </div> `        

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