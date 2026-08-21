<?php
require_once 'connection.php';

$sql = "
    SELECT
        p.id,
        p.order_id,
        p.transaction_id,
        p.payment_method,
        p.amount,
        p.status,
        p.paid_at,
        p.created_at,
        b.booking_code,
        b.customer_name,
        b.customer_phone
    FROM payments p
    INNER JOIN bookings b ON p.booking_id = b.id
    ORDER BY p.id DESC
";

$result = mysqli_query($koneksi, $sql);

if (!$result) {
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Payments - GHD Barbershop</title>

    <!-- Font Awesome -->
    <link
        href="vendor/fontawesome-free/css/all.min.css"
        rel="stylesheet"
    >

    <!-- SB Admin 2 -->
    <link
        href="css/sb-admin-2.min.css"
        rel="stylesheet"
    >

    <!-- DataTables -->
    <link
        href="vendor/datatables/dataTables.bootstrap4.min.css"
        rel="stylesheet"
    >
</head>

<body id="page-top">

    <div id="wrapper">

        <!-- SIDEBAR -->
        <?php include 'sidebar.php'; ?>

        <!-- CONTENT WRAPPER -->
        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <!-- TOPBAR -->
                <?php include 'topbar.php'; ?>

                <!-- MAIN CONTENT -->
                <div class="container-fluid">

                    <!-- PAGE TITLE -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">
                            Payments
                        </h1>
                    </div>

                    <!-- PAYMENT CARD -->
                    <div class="card shadow mb-4">

                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-qrcode mr-2"></i>
                                Data Pembayaran QRIS
                            </h6>
                        </div>

                        <div class="card-body">

                            <div class="table-responsive">
                                <table
                                    class="table table-bordered"
                                    id="paymentTable"
                                    width="100%"
                                    cellspacing="0"
                                >
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Booking</th>
                                            <th>Customer</th>
                                            <th>No. HP</th>
                                            <th>Order ID</th>
                                            <th>Metode</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Dibayar</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        $no = 1;

                                        while ($row = mysqli_fetch_assoc($result)):
                                        ?>
                                            <tr>
                                                <!-- NO -->
                                                <td>
                                                    <?= $no++; ?>
                                                </td>

                                                <!-- BOOKING CODE -->
                                                <td>
                                                    <strong class="text-primary">
                                                        <?= htmlspecialchars($row['booking_code']); ?>
                                                    </strong>
                                                </td>

                                                <!-- CUSTOMER -->
                                                <td>
                                                    <?= htmlspecialchars($row['customer_name']); ?>
                                                </td>

                                                <!-- PHONE -->
                                                <td>
                                                    <?= htmlspecialchars($row['customer_phone']); ?>
                                                </td>

                                                <!-- ORDER ID -->
                                                <td>
                                                    <?= htmlspecialchars($row['order_id']); ?>
                                                </td>

                                                <!-- PAYMENT METHOD -->
                                                <td>
                                                    <span class="badge badge-primary">
                                                        <i class="fas fa-qrcode mr-1"></i>
                                                        <?= htmlspecialchars(strtoupper($row['payment_method'])); ?>
                                                    </span>
                                                </td>

                                                <!-- AMOUNT -->
                                                <td>
                                                    <strong>
                                                        Rp <?= number_format($row['amount'], 0, ',', '.'); ?>
                                                    </strong>
                                                </td>

                                                <!-- STATUS -->
                                                <td>
                                                    <?php if ($row['status'] === 'paid'): ?>

                                                        <span class="badge badge-success">
                                                            <i class="fas fa-check mr-1"></i>
                                                            Lunas
                                                        </span>

                                                    <?php elseif ($row['status'] === 'failed'): ?>

                                                        <span class="badge badge-danger">
                                                            <i class="fas fa-times mr-1"></i>
                                                            Gagal
                                                        </span>

                                                    <?php elseif ($row['status'] === 'expired'): ?>

                                                        <span class="badge badge-secondary">
                                                            <i class="fas fa-clock mr-1"></i>
                                                            Expired
                                                        </span>

                                                    <?php else: ?>

                                                        <span class="badge badge-warning">
                                                            <i class="fas fa-clock mr-1"></i>
                                                            Pending
                                                        </span>

                                                    <?php endif; ?>
                                                </td>

                                                <!-- PAID AT -->
                                                <td>
                                                    <?php if (!empty($row['paid_at'])): ?>

                                                        <?= date('d-m-Y H:i', strtotime($row['paid_at'])); ?>

                                                    <?php else: ?>

                                                        -

                                                    <?php endif; ?>
                                                </td>

                                                <!-- ACTION -->
                                                <td>
                                                    <?php if ($row['status'] !== 'paid'): ?>

                                                        <button
                                                            type="button"
                                                            class="btn btn-primary btn-sm"
                                                            data-toggle="modal"
                                                            data-target="#qrisModal<?= (int) $row['id']; ?>"
                                                        >
                                                            <i class="fas fa-qrcode mr-1"></i>
                                                            Bayar QRIS
                                                        </button>

                                                    <?php else: ?>

                                                        <button
                                                            type="button"
                                                            class="btn btn-success btn-sm"
                                                            disabled
                                                        >
                                                            <i class="fas fa-check mr-1"></i>
                                                            Sudah Dibayar
                                                        </button>

                                                    <?php endif; ?>
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

            <!-- FOOTER -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
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

    <!-- QRIS MODALS
         Diletakkan di luar <table> agar HTML valid.
    -->
    <?php
    mysqli_data_seek($result, 0);

    while ($row = mysqli_fetch_assoc($result)):
    ?>
        <div
            class="modal fade"
            id="qrisModal<?= (int) $row['id']; ?>"
            tabindex="-1"
            role="dialog"
            aria-hidden="true"
        >
            <div
                class="modal-dialog modal-dialog-centered"
                role="document"
            >
                <div class="modal-content">

                    <!-- MODAL HEADER -->
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-qrcode mr-2"></i>
                            Pembayaran QRIS
                        </h5>

                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <!-- MODAL BODY -->
                    <div class="modal-body text-center">

                        <h5 class="font-weight-bold">
                            GHD BARBERSHOP
                        </h5>

                        <p class="text-muted mb-2">
                            Kode Booking
                        </p>

                        <h5 class="text-primary">
                            <?= htmlspecialchars($row['booking_code']); ?>
                        </h5>

                        <hr>

                        <p class="mb-1">
                            Total Pembayaran
                        </p>

                        <h3 class="font-weight-bold text-success">
                            Rp <?= number_format($row['amount'], 0, ',', '.'); ?>
                        </h3>

                        <div class="mt-3 mb-3">
                            <!--
                                GANTI FILE INI DENGAN GAMBAR QRIS
                                MILIK BARBERSHOP.
                            -->
                            <img
                                src="foto/qriss1.jpeg"
                                alt="QRIS GHD Barbershop"
                                class="img-fluid"
                                style="width: 250px; height: 250px; object-fit: contain;"
                            >
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-1"></i>
                            Scan QRIS menggunakan aplikasi pembayaran
                            yang mendukung QRIS.
                        </div>

                        <p class="small text-muted mb-0">
                            Setelah pembayaran, admin dapat memperbarui
                            status pembayaran.
                        </p>

                    </div>

                    <!-- MODAL FOOTER -->
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal"
                        >
                            Tutup
                        </button>
                    </div>

                </div>
            </div>
        </div>
    <?php endwhile; ?>

    <!-- SCROLL TO TOP -->
    <a
        class="scroll-to-top rounded"
        href="#page-top"
    >
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- JAVASCRIPT -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>

    <!-- DataTables -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#paymentTable').DataTable();
        });
    </script>

</body>

</html>
'''

