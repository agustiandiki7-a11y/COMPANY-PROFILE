<?php

session_start();
include "../connection.php";

if (!isset($_SESSION['user_id'])) {
    die("Silakan login.");
}

$id = intval($_GET['id']);

$query = mysqli_query(
    $koneksi,
    "SELECT
        b.*,
        s.name AS service_name,
        u.name AS customer_name
     FROM bookings b
     JOIN services s
        ON s.id = b.service_id
     JOIN users u
        ON u.id = b.user_id
     WHERE b.id = '$id'
     AND b.user_id = '" . $_SESSION['user_id'] . "'
     LIMIT 1"
);

$booking = mysqli_fetch_assoc($query);

if (!$booking) {
    die("Booking tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Pembayaran QRIS</title>

    <script
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="CLIENT_KEY_KAMU">
    </script>

</head>

<body>

<h2>Pembayaran Booking</h2>

<p>
    Kode Booking:
    <strong>
        <?= htmlspecialchars($booking['booking_code']) ?>
    </strong>
</p>

<p>
    Layanan:
    <?= htmlspecialchars($booking['service_name']) ?>
</p>

<p>
    Total:
    <strong>
        Rp <?= number_format($booking['total_price'], 0, ',', '.') ?>
    </strong>
</p>

<button id="pay-button">
    Bayar Sekarang
</button>

<script>

document.getElementById("pay-button")
    .addEventListener("click", function () {

        fetch("create_payment.php", {

            method: "POST",

            headers: {
                "Content-Type": "application/json"
            },

            body: JSON.stringify({
                booking_id: <?= $booking['id'] ?>
            })

        })

        .then(response => response.json())

        .then(data => {

            if (!data.success) {
                alert(data.message);
                return;
            }

            snap.pay(
                data.snap_token,
                {
                    onSuccess: function(result) {

                        console.log(result);

                        window.location.href =
                            "booking_sukses.php?code="
                            + data.booking_code;

                    },

                    onPending: function(result) {

                        alert(
                            "Pembayaran masih menunggu."
                        );

                    },

                    onError: function(result) {

                        alert(
                            "Pembayaran gagal."
                        );

                    },

                    onClose: function() {

                        console.log(
                            "Popup pembayaran ditutup."
                        );

                    }
                }
            );

        });

    });

</script>

</body>
</html>