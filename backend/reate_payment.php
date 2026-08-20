<?php

session_start();

include "../connection.php";
require_once "../vendor/autoload.php";

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {

    http_response_code(401);

    echo json_encode([
        "success" => false,
        "message" => "Unauthorized"
    ]);

    exit;
}

$data = json_decode(
    file_get_contents("php://input"),
    true
);

$booking_id = intval(
    $data['booking_id'] ?? 0
);

$user_id = $_SESSION['user_id'];

/*
|--------------------------------------------------------------------------
| MIDTRANS CONFIG
|--------------------------------------------------------------------------
*/

\Midtrans\Config::$serverKey =
    "SERVER_KEY_KAMU";

\Midtrans\Config::$isProduction = false;

\Midtrans\Config::$isSanitized = true;

\Midtrans\Config::$is3ds = true;

/*
|--------------------------------------------------------------------------
| AMBIL BOOKING
|--------------------------------------------------------------------------
*/

$query = mysqli_query(
    $koneksi,
    "SELECT
        b.*,
        s.name AS service_name,
        u.name AS customer_name,
        u.email,
        u.phone
     FROM bookings b
     JOIN services s
        ON s.id = b.service_id
     JOIN users u
        ON u.id = b.user_id
     WHERE b.id = '$booking_id'
     AND b.user_id = '$user_id'
     LIMIT 1"
);

$booking = mysqli_fetch_assoc($query);

if (!$booking) {

    echo json_encode([
        "success" => false,
        "message" => "Booking tidak ditemukan."
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| CEK STATUS
|--------------------------------------------------------------------------
*/

if ($booking['status'] !== 'pending_payment') {

    echo json_encode([
        "success" => false,
        "message" => "Booking sudah diproses."
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| ORDER ID
|--------------------------------------------------------------------------
*/

$order_id =
    $booking['booking_code']
    . "-"
    . time();

/*
|--------------------------------------------------------------------------
| MIDTRANS PARAMETER
|--------------------------------------------------------------------------
*/

$params = [

    "transaction_details" => [

        "order_id" => $order_id,

        "gross_amount" =>
            (int) $booking['total_price']

    ],

    "item_details" => [

        [

            "id" =>
                $booking['service_id'],

            "price" =>
                (int) $booking['total_price'],

            "quantity" => 1,

            "name" =>
                $booking['service_name']

        ]

    ],

    "customer_details" => [

        "first_name" =>
            $booking['customer_name'],

        "email" =>
            $booking['email'],

        "phone" =>
            $booking['phone']

    ],

    "enabled_payments" => [

        "qris"

    ]

];

try {

    $snap_token =
        \Midtrans\Snap::getSnapToken(
            $params
        );

    mysqli_query(
        $koneksi,
        "INSERT INTO payments (
            booking_id,
            order_id,
            payment_method,
            amount,
            status,
            snap_token
        )
        VALUES (
            '$booking_id',
            '$order_id',
            'qris',
            '{$booking['total_price']}',
            'pending',
            '$snap_token'
        )"
    );

    echo json_encode([

        "success" => true,

        "booking_code" =>
            $booking['booking_code'],

        "snap_token" =>
            $snap_token

    ]);

} catch (Exception $e) {

    http_response_code(500);

    echo json_encode([

        "success" => false,

        "message" =>
            "Gagal membuat pembayaran."

    ]);

}