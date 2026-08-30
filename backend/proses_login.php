<?php 
session_start();
include 'connection.php';

// Keamanan 1: Mencegah SQL Injection dengan mysqli_real_escape_string
$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$password = mysqli_real_escape_string($koneksi, $_POST['password']); 

// Jika password di database kamu menggunakan enkripsi MD5, buka komentar kode di bawah ini:
// $password = md5($password);

$query = mysqli_query($koneksi, "SELECT * FROM admin WHERE username='$username' AND password='$password'");
$cek = mysqli_num_rows($query);

if($cek > 0){
    $data = mysqli_fetch_assoc($query);
    
    // Keamanan 2: Simpan sesi login & hak akses
    $_SESSION['username'] = $username;
    $_SESSION['status'] = "login";
    $_SESSION['role'] = $data['role'] ?? 'admin'; // Menyimpan status superadmin / admin
    $_SESSION['admin_id'] = $data['id'];
    
    header("location:index.php");
}else{
    header("location:login.php?pesan=gagal");
}
?>