<?php
include "../backend/connection.php";

if (isset($_POST['finish_payment'])) {
    $booking_id = intval($_POST['booking_id']);
    
    // Handle upload bukti bayar jika ada
    $proof_name = null;
    if (!empty($_FILES['proof']['name'])) {
        $target_dir = "assets/img/proofs/";
        if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
        $proof_name = time() . '_' . basename($_FILES['proof']['name']);
        move_uploaded_file($_FILES['proof']['tmp_name'], $target_dir . $proof_name);
        
        mysqli_query($koneksi, "UPDATE payments SET proof_image='$proof_name' WHERE booking_id=$booking_id");
    }

    // Update status booking & payment
    mysqli_query($koneksi, "UPDATE bookings SET status_booking='Waiting Verification' WHERE id_booking=$booking_id");
    mysqli_query($koneksi, "UPDATE payments SET status_payment='Pending' WHERE booking_id=$booking_id");

    header("Location: status.php?id=$booking_id");
    exit;
}
?>