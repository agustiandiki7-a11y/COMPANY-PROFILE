<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login") {
    header("Location: login.php?pesan=belum_login");
    exit;
}

include "connection.php";

// Deteksi nama kolom primary key atau ID yang ada di tabel messages
$cek_kolom = mysqli_query($koneksi, "SHOW COLUMNS FROM messages LIKE 'id'");
$id_col = (mysqli_num_rows($cek_kolom) > 0) ? 'id' : 'id_message';

$query = mysqli_query($koneksi, "SELECT * FROM messages ORDER BY $id_col DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Data Messages & Balas | GHD Barbershop</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/ghd-backend.css" rel="stylesheet">
    <style>
        :root { --lux-black: #050505; --lux-surface: #121212; --lux-gold: #c5a059; --lux-white: #f8f8f8; --lux-text: #a3a3a3; }
        body { background: var(--lux-black) !important; color: var(--lux-text) !important; font-family: 'Montserrat', sans-serif; }
        h1, h2, h3, h4, .card-header h6 { font-family: 'Playfair Display', serif; color: var(--lux-white) !important; }
        .card { background: var(--lux-surface) !important; border: 1px solid rgba(197, 160, 89, 0.2) !important; box-shadow: 0 15px 35px rgba(0,0,0,0.8); }
        .card-header { background: #080808 !important; border-bottom: 1px solid rgba(197, 160, 89, 0.2) !important; }
        .table { color: var(--lux-text) !important; background-color: var(--lux-surface); }
        .table th, .table td { border-color: rgba(197, 160, 89, 0.15) !important; vertical-align: middle; }
        .table th { color: var(--lux-gold) !important; font-family: 'Playfair Display', serif; letter-spacing: 1px; background: #080808; }
        .table-striped tbody tr:nth-of-type(odd) { background-color: rgba(255, 255, 255, 0.02); }
        .table-hover tbody tr:hover { background-color: rgba(197, 160, 89, 0.05); color: var(--lux-white); }
        
        /* Tombol Aksi Mewah */
        .btn-wa-lux { 
            background: rgba(40, 167, 69, 0.15); 
            color: #28a745; 
            border: 1px solid #28a745; 
            font-size: 11px; 
            font-weight: 600; 
            text-transform: uppercase; 
            padding: 5px 10px; 
            border-radius: 4px; 
            transition: 0.3s; 
            text-decoration: none; 
            display: inline-block; 
        }
        .btn-wa-lux:hover { background: #28a745; color: #fff; text-decoration: none; box-shadow: 0 0 10px rgba(40,167,69,0.4); }

        .btn-danger-lux { 
            background: rgba(220, 53, 69, 0.15); 
            color: #ff6b6b; 
            border: 1px solid #dc3545; 
            font-size: 11px; 
            font-weight: 600; 
            text-transform: uppercase; 
            padding: 5px 10px; 
            border-radius: 4px; 
            transition: 0.3s; 
            text-decoration: none; 
            display: inline-block; 
        }
        .btn-danger-lux:hover { background: #dc3545; color: #fff; text-decoration: none; box-shadow: 0 0 10px rgba(220,53,69,0.4); }
    </style>
</head>
<body id="page-top">
    <div id="wrapper">
        <?php include "sidebar.php"; ?>
        <div id="content-wrapper" class="d-flex flex-column" style="background: var(--lux-black);">
            <div id="content">
                <?php include "topbar.php"; ?>

                <div class="container-fluid py-4">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <div>
                            <span style="color: var(--lux-gold); font-size: 11px; letter-spacing: 3px; text-transform: uppercase;">Customer Inbox</span>
                            <h1 class="h3 mb-0" style="color: var(--lux-white);">Data Pesan Masuk & Balas (Messages)</h1>
                        </div>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold" style="color: var(--lux-gold);">Daftar Pesan dari Pengunjung Website</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Pengirim</th>
                                            <th>Kontak (WA / Email)</th>
                                            <th>Pesan Masuk</th>
                                            <th>Waktu</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($query && mysqli_num_rows($query) > 0): ?>
                                            <?php $no = 1; while ($row = mysqli_fetch_assoc($query)) : 
                                                $msg_id = $row['id'] ?? ($row['id_message'] ?? 0);
                                                $nama_pengirim = htmlspecialchars($row['name'] ?? ($row['nama'] ?? 'Pelanggan'));
                                                $pesan_masuk = htmlspecialchars($row['message'] ?? ($row['pesan'] ?? '-'));
                                                $kontak = htmlspecialchars($row['phone'] ?? ($row['whatsapp'] ?? ($row['email'] ?? '-')));
                                                
                                                // Format nomor WhatsApp untuk tombol balas (bersihkan karakter selain angka)
                                                $raw_phone = preg_replace('/[^0-9]/', '', $kontak);
                                                // Jika nomor diawali 0, ubah jadi 62
                                                if (substr($raw_phone, 0, 1) == '0') {
                                                    $raw_phone = '62' . substr($raw_phone, 1);
                                                }

                                                // Pesan balasan otomatis saat tombol WhatsApp diklik
                                                $url_wa = "https://api.whatsapp.com/send?phone=" . $raw_phone . "&text=Halo%20" . urlencode($nama_pengirim) . ",%20terima%20kasih%20telah%20menghubungi%20GHD%20Barbershop.%20Menanggapi%20pesan%20Anda:%20%22" . urlencode($pesan_masuk) . "%22...%0A%0ABagaimana%20ada%20yang%20bisa%20kami%20bantu?";
                                            ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><b class="text-white"><?= $nama_pengirim; ?></b></td>
                                                <td>
                                                    <?= $kontak; ?>
                                                </td>
                                                <td>
                                                    <div style="max-width: 280px; white-space: normal; word-wrap: break-word;">
                                                        <?= $pesan_masuk; ?>
                                                    </div>
                                                </td>
                                                <td style="font-size: 11px; color: #888;"><?= htmlspecialchars($row['created_at'] ?? '-'); ?></td>
                                                <td>
                                                    <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                                        <?php if(!empty($raw_phone) && strlen($raw_phone) >= 9): ?>
                                                            <a href="<?= $url_wa; ?>" target="_blank" class="btn-wa-lux" title="Balas via WhatsApp"><i class="fab fa-whatsapp mr-1"></i> Balas</a>
                                                        <?php endif; ?>
                                                        <a href="hapus_message.php?id=<?= $msg_id; ?>" class="btn-danger-lux" onclick="return confirm('Yakin ingin menghapus pesan ini?')" title="Hapus Pesan"><i class="fas fa-trash mr-1"></i> Hapus</a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="6" class="text-center py-4 text-muted">Belum ada pesan masuk.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>
</html>