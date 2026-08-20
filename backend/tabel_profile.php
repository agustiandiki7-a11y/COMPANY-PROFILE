<?php

session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php?pesan=belum_login");
    exit;
}

include "connection.php";

$query_profile = mysqli_query(
    $koneksi,
    "SELECT * FROM profile ORDER BY id_profile DESC"
);

if (!$query_profile) {
    die("Query gagal: " . mysqli_error($koneksi));
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

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">

                        <h1 class="h3 mb-0 text-gray-800">
                            Profile
                        </h1>

                    </div>

                    <!-- Tombol Tambah -->
                  

                    <!-- Card -->
                    <div class="card shadow mb-4">

                        <div class="card-header py-3">

                            <h6 class="m-0 font-weight-bold text-primary">
                                Data Profile GHD Barbershop
                            </h6>

                        </div>

                        <div class="card-body">

                            <div class="table-responsive">

                                <table class="table table-bordered table-striped">

                                    <thead>

                                        <tr>

                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Address</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>Instagram</th>
                                            <th>Opening Hours</th>
                                            <th>Logo</th>
                                            <th>Action</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php
                                        $no = 1;

                                        while ($data = mysqli_fetch_assoc($query_profile)) :
                                        ?>

                                            <tr>

                                                <td>
                                                    <?php echo $no++; ?>
                                                </td>

                                                <td>
                                                    <?php echo htmlspecialchars($data['name']); ?>
                                                </td>

                                                <td>
                                                    <?php echo htmlspecialchars($data['description']); ?>
                                                </td>

                                                <td>
                                                    <?php echo htmlspecialchars($data['address']); ?>
                                                </td>

                                                <td>
                                                    <?php echo htmlspecialchars($data['phone']); ?>
                                                </td>

                                                <td>
                                                    <?php echo htmlspecialchars($data['email']); ?>
                                                </td>

                                                <td>
                                                    <?php echo htmlspecialchars($data['instagram']); ?>
                                                </td>

                                                <td>
                                                    <?php echo htmlspecialchars($data['opening_hours']); ?>
                                                </td>

                                                <td>

                                                    <?php if (!empty($data['logo']) && file_exists("foto/" . $data['logo'])) : ?>

                                                        <img
                                                            src="foto/<?php echo htmlspecialchars($data['logo']); ?>"
                                                            width="80"
                                                            height="80"
                                                            style="object-fit: cover;"
                                                            class="rounded"
                                                            alt="Logo"
                                                        >

                                                    <?php else : ?>

                                                        <span class="text-muted">
                                                            No Logo
                                                        </span>

                                                    <?php endif; ?>

                                                </td>

                                                <td style="white-space: nowrap;">

                                                    <a
                                                        href="update_form_profile.php?id_profile=<?php echo $data['id_profile']; ?>"
                                                        class="btn btn-success btn-sm"
                                                    >

                                                        <i class="fas fa-edit"></i>
                                                        Update

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

    <?php include "buttom.php"; ?>

</body>

</html>