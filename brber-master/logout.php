<?php
session_start();

// Menghapus semua variabel sesi yang aktif
$_SESSION = array();

// Menghapus cookie sesi jika ada
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Menghancurkan sesi
session_destroy();

// Pesan pemberitahuan dan alihkan kembali ke halaman beranda
echo "<script>alert('Anda telah berhasil logout.'); window.location.href='index.php';</script>";
exit;
?>