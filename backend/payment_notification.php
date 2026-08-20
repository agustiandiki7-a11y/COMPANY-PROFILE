<?php

include "../connection.php";
require_once "../vendor/autoload.php";

\Midtrans\Config::$serverKey =
    "SERVER_KEY_KAMU";

\Midtrans\Config::$isProduction = false;

\Midtrans\Config::$isSanitized = true;

\Midtrans\Config::$is3ds = true;

try {

    $notification =
        new \Midtrans\Notification();

    $order_id =
        $notification->order_id;

    $transaction_status =
        $notification->transaction_status;

    $transaction_id =
        $notification->transaction_id ?? null;

    $fraud_status =
        $notification->fraud_status ?? null;

    /*
    |--------------------------------------------------------------------------
    | CARI PAYMENT
    |--------------------------------------------------------------------------
    */

    $query = mysqli_query(
        $koneksi,
        "SELECT *
         FROM payments
         WHERE order_id = '$order_id'
         LIMIT 1"
    );

    $payment =
        mysqli_fetch_assoc($query);

    if (!$payment) {

        http_response_code(404);

        exit("Order tidak ditemukan.");
    }

    /*
    |--------------------------------------------------------------------------
    | DEFAULT STATUS
    |--------------------------------------------------------------------------
    */

    $payment_status = "pending";

    $booking_status =
        "pending_payment";

    /*
    |--------------------------------------------------------------------------
    | PEMBAYARAN BERHASIL
    |--------------------------------------------------------------------------
    */

    if (
        $transaction_status === "settlement"
    ) {

        $payment_status =
            "paid";

        $booking_status =
            "confirmed";
    }

    elseif (
        $transaction_status === "capture"
        &&
        $fraud_status !== "challenge"
    ) {

        $payment_status =
            "paid";

        $booking_status =
            "confirmed";
    }

    /*
    |--------------------------------------------------------------------------
    | MENUNGGU
    |--------------------------------------------------------------------------
    */

    elseif (
        $transaction_status === "pending"
    ) {

        $payment_status =
            "pending";

        $booking_status =
            "pending_payment";
    }

    /*
    |--------------------------------------------------------------------------
    | EXPIRED
    |--------------------------------------------------------------------------
    */

    elseif (
        $transaction_status === "expire"
    ) {

        $payment_status =
            "expired";

        $booking_status =
            "cancelled";
    }

    /*
    |--------------------------------------------------------------------------
    | GAGAL
    |--------------------------------------------------------------------------
    */

    elseif (
        $transaction_status === "cancel"
        ||
        $transaction_status === "deny"
    ) {

        $payment_status =
            "failed";

        $booking_status =
            "cancelled";
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE PAYMENT
    |--------------------------------------------------------------------------
    */

    $paid_at = null;

    if ($payment_status === "paid") {

        $paid_at =
            date("Y-m-d H:i:s");
    }

    mysqli_query(
        $koneksi,
        "UPDATE payments SET

            transaction_id =
                " . ($transaction_id
                    ? "'" . mysqli_real_escape_string(
                        $koneksi,
                        $transaction_id
                    ) . "'"
                    : "NULL") . ",

            status =
                '$payment_status',

            paid_at =
                " . ($paid_at
                    ? "'$paid_at'"
                    : "NULL") . "

        WHERE id =
            '{$payment['id']}'"
    );

    /*
    |--------------------------------------------------------------------------
    | UPDATE BOOKING
    |--------------------------------------------------------------------------
    */

    mysqli_query(
        $koneksi,
        "UPDATE bookings SET

            status =
                '$booking_status'

         WHERE id =
            '{$payment['booking_id']}'"
    );

    echo "OK";

} catch (Exception $e) {

    http_response_code(500);

    echo "ERROR";

}