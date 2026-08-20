<?php
include "connection.php"; // Sesuaikan path koneksi

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    
    // Tangkap data file gambar
    $image_name = $_FILES['image']['name'];
    $image_tmp  = $_FILES['image']['tmp_name'];
    $image_error = $_FILES['image']['error'];

    // Cek apakah ada gambar yang diupload
    if ($image_error === 0) {
        // Buat nama file unik agar tidak bentrok (contoh: 1787123883_namafile.jpg)
        $new_image_name = time() . '_' . basename($image_name);
        
        // Tentukan folder tujuan penyimpanan (pastikan folder assets/img/ ada)
        $upload_dir = "assets/img/";
        
        // Pindahkan file dari temp ke folder tujuan
        if (move_uploaded_file($image_tmp, $upload_dir . $new_image_name)) {
            
            // Simpan nama file beserta data teks lainnya ke database
            $query = "INSERT INTO barbers (name, image) VALUES (?, ?)";
            $stmt = mysqli_prepare($koneksi, $query);
            mysqli_stmt_bind_param($stmt, "ss", $name, $new_image_name);
            
            if (mysqli_stmt_execute($stmt)) {
                header("Location: tabel_barber.php"); // Alihkan jika berhasil
                exit();
            } else {
                echo "Gagal menyimpan ke database: " . mysqli_error($koneksi);
            }
            mysqli_stmt_close($stmt);

        } else {
            echo "Gagal memindahkan file gambar ke folder tujuan!";
        }
    } else {
        echo "Tidak ada gambar yang diupload atau terjadi error pada file.";
    }
}
?>