<?php

require_once 'connection.php';


/* =========================================================
   READ / UNREAD
   ========================================================= */

if (isset($_GET['action'], $_GET['id'])) {

    $id = (int) $_GET['id'];
    $action = $_GET['action'];

    if ($id > 0) {

        if ($action === 'read') {

            $stmt = mysqli_prepare(
                $koneksi,
                "UPDATE messages
                 SET status = 'read'
                 WHERE id_message = ?"
            );

            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } elseif ($action === 'unread') {

            $stmt = mysqli_prepare(
                $koneksi,
                "UPDATE messages
                 SET status = 'unread'
                 WHERE id_message = ?"
            );

            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    header("Location: tabel_messages.php");
    exit;
}


/* =========================================================
   HAPUS MESSAGE
   ========================================================= */

if (isset($_GET['delete'])) {

    $id = (int) $_GET['delete'];

    if ($id > 0) {

        $stmt = mysqli_prepare(
            $koneksi,
            "DELETE FROM messages
             WHERE id_message = ?"
        );

        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    header("Location: tabel_messages.php");
    exit;
}


/* =========================================================
   AMBIL DATA MESSAGE
   ========================================================= */

$query = "
    SELECT
        id_message,
        name,
        email,
        phone,
        subject,
        message,
        status,
        created_at
    FROM messages
    ORDER BY created_at DESC
";

$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Query gagal: " . mysqli_error($koneksi));
}


/* =========================================================
   HITUNG UNREAD
   ========================================================= */

$unreadQuery = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total
     FROM messages
     WHERE status = 'unread'"
);

$unreadData = mysqli_fetch_assoc($unreadQuery);

$totalUnread = (int) $unreadData['total'];

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Messages - GHD Barbershop</title>


    <!-- Font Awesome -->
    <link
        href="vendor/fontawesome-free/css/all.min.css"
        rel="stylesheet">


    <!-- SB Admin 2 -->
    <link
        href="css/sb-admin-2.min.css"
        rel="stylesheet">


    <!-- DataTables -->
    <link
        href="vendor/datatables/dataTables.bootstrap4.min.css"
        rel="stylesheet">

</head>


<body id="page-top">


    <div id="wrapper">


        <!-- =====================================================
         SIDEBAR
         ===================================================== -->

        <?php include 'sidebar.php'; ?>


        <!-- =====================================================
         CONTENT WRAPPER
         ===================================================== -->

        <div
            id="content-wrapper"
            class="d-flex flex-column">


            <div id="content">


                <!-- TOPBAR -->

                <?php include 'topbar.php'; ?>


                <!-- =================================================
                 CONTAINER
                 ================================================= -->

                <div class="container-fluid">


                    <!-- PAGE HEADING -->

                    <div
                        class="d-sm-flex align-items-center justify-content-between mb-4">

                        <div>

                            <h1 class="h3 mb-1 text-gray-800">
                                Messages
                            </h1>

                            <p class="mb-0 text-muted">
                                Pesan yang dikirim oleh pelanggan.
                            </p>

                        </div>


                        <!-- UNREAD -->

                        <div>

                            <span class="badge badge-danger px-3 py-2">

                                <i class="fas fa-envelope mr-1"></i>

                                <?= $totalUnread; ?>

                                Pesan Belum Dibaca

                            </span>

                        </div>

                    </div>


                    <!-- =================================================
                     CARD
                     ================================================= -->

                    <div class="card shadow mb-4">


                        <!-- HEADER -->

                        <div class="card-header py-3">

                            <h6
                                class="m-0 font-weight-bold text-primary">

                                <i class="fas fa-envelope mr-2"></i>

                                Data Messages

                            </h6>

                        </div>


                        <!-- BODY -->

                        <div class="card-body">


                            <div class="table-responsive">


                                <table
                                    class="table table-bordered table-hover"
                                    id="messageTable"
                                    width="100%"
                                    cellspacing="0">


                                    <thead>

                                        <tr>

                                            <th>No</th>

                                            <th>Nama</th>

                                            <th>Email</th>

                                            <th>No. HP</th>

                                            <th>Subject</th>

                                            <th>Pesan</th>

                                            <th>Tanggal</th>

                                            <th>Status</th>

                                            <th>Aksi</th>

                                        </tr>

                                    </thead>


                                    <tbody>


                                        <?php

                                        $no = 1;

                                        while ($row = mysqli_fetch_assoc($result)):

                                            $id = (int) $row['id_message'];

                                            $status = $row['status'];

                                        ?>


                                            <tr>


                                                <!-- NO -->

                                                <td>

                                                    <?= $no++; ?>

                                                </td>


                                                <!-- NAMA -->

                                                <td>

                                                    <?= htmlspecialchars(
                                                        $row['name']
                                                    ); ?>

                                                </td>


                                                <!-- EMAIL -->

                                                <td>

                                                    <?= htmlspecialchars(
                                                        $row['email']
                                                    ); ?>

                                                </td>


                                                <!-- PHONE -->

                                                <td>

                                                    <?= !empty($row['phone'])
                                                        ? htmlspecialchars(
                                                            $row['phone']
                                                        )
                                                        : '-';
                                                    ?>

                                                </td>


                                                <!-- SUBJECT -->

                                                <td>

                                                    <?= htmlspecialchars(
                                                        $row['subject']
                                                    ); ?>

                                                </td>


                                                <!-- MESSAGE -->

                                                <td>

                                                    <span
                                                        class="d-inline-block text-truncate"
                                                        style="max-width: 250px;"
                                                        title="<?= htmlspecialchars(
                                                                    $row['message']
                                                                ); ?>">

                                                        <?= htmlspecialchars(
                                                            $row['message']
                                                        ); ?>

                                                    </span>

                                                </td>


                                                <!-- DATE -->

                                                <td>

                                                    <?= date(
                                                        'd-m-Y H:i',
                                                        strtotime(
                                                            $row['created_at']
                                                        )
                                                    ); ?>

                                                </td>


                                                <!-- STATUS -->

                                                <td>

                                                    <?php if ($status === 'read'): ?>

                                                        <span
                                                            class="badge badge-success">

                                                            <i
                                                                class="fas fa-envelope-open mr-1"></i>

                                                            Read

                                                        </span>

                                                    <?php else: ?>

                                                        <span
                                                            class="badge badge-warning">

                                                            <i
                                                                class="fas fa-envelope mr-1"></i>

                                                            Unread

                                                        </span>

                                                    <?php endif; ?>

                                                </td>


                                                <!-- AKSI -->

                                                <td
                                                    class="text-center"
                                                    style="white-space: nowrap;">


                                                    <!-- LIHAT -->

                                                    <button
                                                        type="button"
                                                        class="btn btn-info btn-sm"
                                                        data-toggle="modal"
                                                        data-target="#messageModal<?= $id; ?>"
                                                        title="Lihat pesan">

                                                        <i class="fas fa-eye"></i>

                                                    </button>


                                                    <!-- READ -->

                                                    <?php if ($status === 'unread'): ?>

                                                        <a
                                                            href="tabel_messages.php?action=read&id=<?= $id; ?>"
                                                            class="btn btn-success btn-sm"
                                                            title="Tandai sudah dibaca">

                                                            <i
                                                                class="fas fa-envelope-open"></i>

                                                        </a>

                                                    <?php else: ?>


                                                        <!-- UNREAD -->

                                                        <a
                                                            href="tabel_messages.php?action=unread&id=<?= $id; ?>"
                                                            class="btn btn-warning btn-sm"
                                                            title="Tandai belum dibaca">

                                                            <i
                                                                class="fas fa-envelope"></i>

                                                        </a>

                                                    <?php endif; ?>


                                                    <!-- DELETE -->

                                                    <a
                                                        href="tabel_messages.php?delete=<?= $id; ?>"
                                                        class="btn btn-danger btn-sm"
                                                        title="Hapus pesan"
                                                        onclick="return confirm(
                                                    'Yakin ingin menghapus pesan ini?'
                                                );">

                                                        <i class="fas fa-trash"></i>

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


            <!-- =====================================================
             FOOTER
             ===================================================== -->

            <footer class="sticky-footer bg-white">

                <div class="container my-auto">

                    <div
                        class="copyright text-center my-auto">

                        <span>

                            Copyright &copy;
                            GHD Barbershop
                            <?= date('Y'); ?>

                        </span>

                    </div>

                </div>

            </footer>


        </div>

    </div>


    <!-- =========================================================
     MODAL DETAIL PESAN
     ========================================================= -->

    <?php

    mysqli_data_seek($result, 0);

    while ($row = mysqli_fetch_assoc($result)):

        $id = (int) $row['id_message'];

    ?>

        <div
            class="modal fade"
            id="messageModal<?= $id; ?>"
            tabindex="-1"
            role="dialog"
            aria-hidden="true">

            <div
                class="modal-dialog modal-dialog-centered"
                role="document">

                <div class="modal-content">


                    <!-- HEADER -->

                    <div class="modal-header">

                        <h5 class="modal-title">

                            <i class="fas fa-envelope mr-2"></i>

                            Detail Pesan

                        </h5>


                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal">

                            <span>
                                &times;
                            </span>

                        </button>

                    </div>


                    <!-- BODY -->

                    <div class="modal-body">


                        <div class="mb-3">

                            <small class="text-muted">
                                Nama
                            </small>

                            <div class="font-weight-bold">

                                <?= htmlspecialchars(
                                    $row['name']
                                ); ?>

                            </div>

                        </div>


                        <div class="mb-3">

                            <small class="text-muted">
                                Email
                            </small>

                            <div>

                                <?= htmlspecialchars(
                                    $row['email']
                                ); ?>

                            </div>

                        </div>


                        <div class="mb-3">

                            <small class="text-muted">
                                No. HP
                            </small>

                            <div>

                                <?= !empty($row['phone'])
                                    ? htmlspecialchars(
                                        $row['phone']
                                    )
                                    : '-';
                                ?>

                            </div>

                        </div>


                        <div class="mb-3">

                            <small class="text-muted">
                                Subject
                            </small>

                            <div class="font-weight-bold">

                                <?= htmlspecialchars(
                                    $row['subject']
                                ); ?>

                            </div>

                        </div>


                        <div class="mb-3">

                            <small class="text-muted">
                                Pesan
                            </small>

                            <div
                                class="border rounded p-3 bg-light"
                                style="white-space: pre-wrap;">

                                <?= htmlspecialchars(
                                    $row['message']
                                ); ?>

                            </div>

                        </div>


                        <div>

                            <small class="text-muted">
                                Dikirim
                            </small>

                            <div>

                                <?= date(
                                    'd-m-Y H:i',
                                    strtotime(
                                        $row['created_at']
                                    )
                                ); ?>

                            </div>

                        </div>


                    </div>


                    <!-- FOOTER -->

                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal">

                            Tutup

                        </button>

                    </div>


                </div>

            </div>

        </div>

    <?php endwhile; ?>


    <!-- =========================================================
     SCROLL TOP
     ========================================================= -->

    <a
        class="scroll-to-top rounded"
        href="#page-top">

        <i class="fas fa-angle-up"></i>

    </a>


    <!-- =========================================================
     JAVASCRIPT
     ========================================================= -->

    <script src="vendor/jquery/jquery.min.js"></script>

    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <script src="js/sb-admin-2.min.js"></script>


    <!-- DATATABLES -->

    <script src="vendor/datatables/jquery.dataTables.min.js"></script>

    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>


    <script>
        $(document).ready(function() {

            $('#messageTable').DataTable({

                pageLength: 10,

                order: [
                    [0, 'asc']
                ]

            });

        });
    </script>


</body>

</html>