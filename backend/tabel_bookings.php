<?php

require_once 'connection.php';

$sql = "SELECT * FROM bookings ORDER BY id DESC";

$result = mysqli_query($koneksi, $sql);

if (!$result) {
    die(
        "Query gagal: "
        . mysqli_error($koneksi)
    );
}
?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1">

    <title>Bookings - GHD Barbershop</title>


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


        <!-- SIDEBAR -->

        <?php include 'sidebar.php'; ?>


        <!-- CONTENT -->

        <div
            id="content-wrapper"
            class="d-flex flex-column">


            <div id="content">


                <!-- TOPBAR -->

                <?php include 'topbar.php'; ?>


                <!-- CONTAINER -->

                <div class="container-fluid">


                    <!-- PAGE HEADING -->

                    <div
                        class="d-sm-flex align-items-center justify-content-between mb-4">


                        <h1 class="h3 mb-0 text-gray-800">

                            Bookings

                        </h1>


                        <a
                            href="#"
                            class="btn btn-primary btn-sm">

                            <i class="fas fa-plus"></i>

                            Tambah Booking

                        </a>


                    </div>


                    <!-- CARD -->

                    <div class="card shadow mb-4">


                        <div class="card-header py-3">

                            <h6
                                class="m-0 font-weight-bold text-primary">

                                <i
                                    class="fas fa-calendar-check mr-2">
                                </i>

                                Data Booking Pelanggan

                            </h6>

                        </div>


                        <div class="card-body">


                            <div class="table-responsive">


                                <table
                                    class="table table-bordered"
                                    id="dataTable"
                                    width="100%"
                                    cellspacing="0">


                                    <thead>

                                        <tr>

                                            <th>No</th>

                                            <th>Kode Booking</th>

                                            <th>Customer</th>

                                            <th>No. HP</th>

                                            <th>Service</th>

                                            <th>Barber</th>

                                            <th>Tanggal</th>

                                            <th>Jam</th>

                                            <th>Total</th>

                                            <th>Status</th>

                                        </tr>

                                    </thead>


                                    <tbody>


                                        <?php

                                        $no = 1;


                                        while (
                                            $row =
                                            mysqli_fetch_assoc($result)
                                        ) {

                                        ?>


                                            <tr>


                                                <!-- NO -->

                                                <td>

                                                    <?php

                                                    echo $no++;

                                                    ?>

                                                </td>


                                                <!-- KODE -->

                                                <td>

                                                    <span
                                                        class="font-weight-bold text-primary">

                                                        <?php

                                                        echo htmlspecialchars(
                                                            $row['booking_code']
                                                        );

                                                        ?>

                                                    </span>

                                                </td>


                                                <!-- CUSTOMER -->

                                                <td>

                                                    <?php

                                                    echo htmlspecialchars(
                                                        $row['customer_name']
                                                    );

                                                    ?>

                                                </td>


                                                <!-- PHONE -->

                                                <td>

                                                    <?php

                                                    echo htmlspecialchars(
                                                        $row['customer_phone']
                                                    );

                                                    ?>

                                                </td>


                                                <!-- SERVICE -->

                                                <td>

                                                    <?php

                                                    echo htmlspecialchars(
                                                        $row['service_name']
                                                    );

                                                    ?>

                                                </td>


                                                <!-- BARBER -->

                                                <td>

                                                    <?php

                                                    if (
                                                        !empty($row['barber_name'])
                                                    ) {

                                                        echo htmlspecialchars(
                                                            $row['barber_name']
                                                        );
                                                    } else {

                                                        echo '-';
                                                    }

                                                    ?>

                                                </td>


                                                <!-- DATE -->

                                                <td>

                                                    <?php

                                                    echo date(
                                                        'd-m-Y',
                                                        strtotime(
                                                            $row['booking_date']
                                                        )
                                                    );

                                                    ?>

                                                </td>


                                                <!-- TIME -->

                                                <td>

                                                    <?php

                                                    echo date(
                                                        'H:i',
                                                        strtotime(
                                                            $row['booking_time']
                                                        )
                                                    );

                                                    ?>

                                                </td>


                                                <!-- TOTAL -->

                                                <td>

                                                    Rp

                                                    <?php

                                                    echo number_format(
                                                        $row['total_price'],
                                                        0,
                                                        ',',
                                                        '.'
                                                    );

                                                    ?>

                                                </td>


                                                <!-- STATUS -->

                                                <td>


                                                    <?php

                                                    if (
                                                        $row['status']
                                                        ==
                                                        'confirmed'
                                                    ) {

                                                    ?>

                                                        <span
                                                            class="badge badge-success">

                                                            Confirmed

                                                        </span>

                                                    <?php

                                                    } elseif (
                                                        $row['status']
                                                        ==
                                                        'pending_payment'
                                                    ) {

                                                    ?>

                                                        <span
                                                            class="badge badge-warning">

                                                            Menunggu Pembayaran

                                                        </span>

                                                    <?php

                                                    } elseif (
                                                        $row['status']
                                                        ==
                                                        'checked_in'
                                                    ) {

                                                    ?>

                                                        <span
                                                            class="badge badge-info">

                                                            Check In

                                                        </span>

                                                    <?php

                                                    } elseif (
                                                        $row['status']
                                                        ==
                                                        'completed'
                                                    ) {

                                                    ?>

                                                        <span
                                                            class="badge badge-primary">

                                                            Selesai

                                                        </span>

                                                    <?php

                                                    } elseif (
                                                        $row['status']
                                                        ==
                                                        'cancelled'
                                                    ) {

                                                    ?>

                                                        <span
                                                            class="badge badge-danger">

                                                            Dibatalkan

                                                        </span>

                                                    <?php

                                                    } else {

                                                    ?>

                                                        <span
                                                            class="badge badge-secondary">

                                                            <?php

                                                            echo htmlspecialchars(
                                                                $row['status']
                                                            );

                                                            ?>

                                                        </span>

                                                    <?php

                                                    }

                                                    ?>


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

            </div>


            <!-- FOOTER -->

            <footer class="sticky-footer bg-white">

                <div class="container my-auto">

                    <div
                        class="copyright text-center my-auto">

                        <span>

                            Copyright &copy;
                            GHD Barbershop
                            <?php echo date('Y'); ?>

                        </span>

                    </div>

                </div>

            </footer>


        </div>

    </div>


    <!-- SCROLL TOP -->

    <a
        class="scroll-to-top rounded"
        href="#page-top">

        <i class="fas fa-angle-up"></i>

    </a>


    <!-- JAVASCRIPT -->

    <script
        src="vendor/jquery/jquery.min.js">
    </script>

    <script
        src="vendor/bootstrap/js/bootstrap.bundle.min.js">
    </script>

    <script
        src="vendor/jquery-easing/jquery.easing.min.js">
    </script>

    <script
        src="js/sb-admin-2.min.js">
    </script>


    <!-- DATATABLE -->

    <script
        src="vendor/datatables/jquery.dataTables.min.js">
    </script>

    <script
        src="vendor/datatables/dataTables.bootstrap4.min.js">
    </script>


    <script>
        $(document).ready(function() {

            $('#dataTable').DataTable();

        });
    </script>


</body>

</html>