<?php
session_start();
include "../backend/connection.php";

if (isset($_POST['subscribe'])) {
    $customer_id   = $_SESSION['customer_id'];
    $customer_name = mysqli_real_escape_string($koneksi, $_SESSION['customer_name']);
    $customer_phone= mysqli_real_escape_string($koneksi, $_SESSION['customer_phone']);
    $package_name  = mysqli_real_escape_string($koneksi, $_POST['package_name']);
    $price         = intval($_POST['price']);
    $duration      = mysqli_real_escape_string($koneksi, $_POST['duration']);
    $sub_date      = date('Y-m-d');

    // Simpan data langganan ke database
    $sql = "INSERT INTO subscriptions (customer_id, customer_name, customer_phone, package_name, price, duration, sub_date, status, payment_status) 
            VALUES ('$customer_id', '$customer_name', '$customer_phone', '$package_name', '$price', '$duration', '$sub_date', 'pending', 'unpaid')";
    
    if (mysqli_query($koneksi, $sql)) {
        $last_id = mysqli_insert_id($koneksi);
        // Arahkan ke halaman pembayaran QRIS khusus langganan
        header("Location: payment_qris_sub.php?id=$last_id");
        exit;
    } else {
        echo "Gagal memproses langganan: " . mysqli_error($koneksi);
    }
} else {
    header("Location: langganan.php");
    exit;
}
?>