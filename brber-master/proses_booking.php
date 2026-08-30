<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "../backend/connection.php";

if (!isset($_SESSION['customer_id']) && !isset($_SESSION['customer_name'])) {
    echo "<script>alert('Silakan login terlebih dahulu.'); window.location.href='login.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_name = $_SESSION['customer_name'] ?? 'Pelanggan';
    $customer_phone = $_SESSION['customer_phone'] ?? '-';
    
    $booking_date = mysqli_real_escape_string($koneksi, $_POST['booking_date']);
    $booking_time = mysqli_real_escape_string($koneksi, $_POST['booking_time']);
    $barber_id = !empty($_POST['barber_id']) ? intval($_POST['barber_id']) : NULL;
    $chair_number = mysqli_real_escape_string($koneksi, $_POST['chair_number']);
    
    // Generate Kode Booking Unik
    $booking_code = 'GHD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
    
    // Ambil layanan pertama atau default jika tidak dipilih
    $service_id = intval($_POST['service_id'] ?? 1); 

    // Cek apakah kolom customer_id ada di tabel bookings atau tidak
    $cek_kolom = mysqli_query($koneksi, "SHOW COLUMNS FROM bookings LIKE 'customer_id'");
    
    if (mysqli_num_rows($cek_kolom) > 0) {
        // Jika tabel memiliki kolom customer_id
        $customer_id = $_SESSION['customer_id'] ?? 1;
        $query = "INSERT INTO bookings (booking_code, customer_id, customer_name, customer_phone, service_id, barber_id, chair_number, booking_date, booking_time, status) 
                  VALUES ('$booking_code', '$customer_id', '$customer_name', '$customer_phone', '$service_id', " . ($barber_id ? $barber_id : "NULL") . ", '$chair_number', '$booking_date', '$booking_time', 'pending')";
    } else {
        // Jika tabel tidak punya kolom customer_id (menyimpan nama/telepon langsung)
        $query = "INSERT INTO bookings (booking_code, customer_name, customer_phone, service_id, barber_id, chair_number, booking_date, booking_time, status) 
                  VALUES ('$booking_code', '$customer_name', '$customer_phone', '$service_id', " . ($barber_id ? $barber_id : "NULL") . ", '$chair_number', '$booking_date', '$booking_time', 'pending')";
    }
    
    if (mysqli_query($koneksi, $query)) {
        // Ambil ID booking yang baru saja dimasukkan
        $booking_id = mysqli_insert_id($koneksi);
        
        // Arahkan kustomer langsung ke halaman Pembayaran (Payment)
        echo "<script>alert('Booking berhasil dibuat! Silakan selesaikan pembayaran.'); window.location.href='payment.php?id=$booking_id';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal melakukan booking: " . mysqli_error($koneksi) . "'); window.location.href='booking.php';</script>";
        exit;
    }
}
?>