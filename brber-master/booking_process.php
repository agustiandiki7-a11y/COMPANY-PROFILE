<?php
include "../backend/connection.php";

if (isset($_POST['submit_booking'])) {
    $customer_name  = trim($_POST['customer_name']);
    $customer_phone = trim($_POST['customer_phone']);
    $service_id     = (int)$_POST['service_id'];
    $barber_id      = (int)$_POST['barber_id'];
    $booking_date   = $_POST['booking_date'];
    $booking_time   = $_POST['booking_time'];

    if (empty($customer_name) || empty($customer_phone) || empty($service_id) || empty($barber_id) || empty($booking_date) || empty($booking_time)) {
        exit("Semua data wajib diisi!");
    }

    // 1. Ambil nama layanan & harga dari tabel `services`
    $q_service = mysqli_query($koneksi, "SELECT name, price FROM services WHERE id_service = $service_id LIMIT 1");
    $service   = mysqli_fetch_assoc($q_service);
    if (!$service) exit("Layanan tidak valid.");
    
    $service_name = $service['name'];
    $total_price  = (float)$service['price'];

    // 2. Ambil nama barber dari tabel `barbers`
    $q_barber = mysqli_query($koneksi, "SELECT name FROM barbers WHERE id_barber = $barber_id LIMIT 1");
    $barber   = mysqli_fetch_assoc($q_barber);
    if (!$barber) exit("Barber tidak valid.");
    
    $barber_name = $barber['name'];

    // 3. Buat Kode Booking Unik
    $booking_code = 'GHD-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 4));
    $booking_status = 'pending_payment';

    // 4. Simpan ke tabel `bookings`
    $stmt_booking = mysqli_prepare($koneksi, "INSERT INTO bookings (booking_code, customer_name, customer_phone, service_name, barber_name, booking_date, booking_time, total_price, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt_booking, "sssssssds", $booking_code, $customer_name, $customer_phone, $service_name, $barber_name, $booking_date, $booking_time, $total_price, $booking_status);
    mysqli_stmt_execute($stmt_booking);
    
    // Ambil ID booking yang baru saja dimasukkan
    $booking_id = mysqli_insert_id($koneksi);
    mysqli_stmt_close($stmt_booking);

    // 5. Simpan data tagihan ke tabel `payments` (Metode QRIS)[cite: 1]
    $order_id = 'ORDER-' . $booking_code;
    $payment_method = 'QRIS';
    $payment_status = 'pending';

    $stmt_payment = mysqli_prepare($koneksi, "INSERT INTO payments (booking_id, order_id, payment_method, amount, status) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt_payment, "issds", $booking_id, $order_id, $payment_method, $total_price, $payment_status);
    mysqli_stmt_execute($stmt_payment);
    mysqli_stmt_close($stmt_payment);

    // 6. Alihkan ke halaman pembayaran QRIS dengan membawa kode booking
    header("Location: payment.php?code=" . urlencode($booking_code));
    exit();
} else {
    header("Location: booking.php");
    exit();
}
?>