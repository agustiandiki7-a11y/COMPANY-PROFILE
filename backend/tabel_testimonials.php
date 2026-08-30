<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login") {
    header("Location: login.php?pesan=belum_login");
    exit;
}

include "connection.php";

// Ambil semua data ulasan / testimoni
$query = mysqli_query($koneksi, "SELECT * FROM testimonials ORDER BY id_testimonial DESC");

include "header.php";
?>
<body id="page-top">
    <style>
        body { background: #f8f9fc; font-family: 'Montserrat', sans-serif; color: #333; }
        .simple-card { background: #fff; border: 1px solid #e3e6f0; border-radius: 8px; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.08); }
        .table-simple th { background: #f1f3f9; color: #4e73df; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; border-bottom: 2px solid #e3e6f0; padding: 12px; }
        .table-simple td { vertical-align: middle !important; font-size: 13px; padding: 12px; border-top: 1px solid #f8f9fc; }
    </style>

    <div id="wrapper">
        <?php include "sidebar.php"; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include "topbar.php"; ?>
                <div class="container-fluid px-4 py-4">

                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Kelola Ulasan & Rating Pelanggan</h1>
                    </div>

                    <?php if(isset($_GET['pesan']) && $_GET['pesan'] == 'dihapus'): ?>
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                            <strong>Terhapus!</strong> Ulasan pelanggan berhasil dihapus dari website.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                    <?php endif; ?>

                    <div class="simple-card mb-4 overflow-hidden">
                        <div class="table-responsive">
                            <table class="table table-hover table-simple mb-0" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Nama Pelanggan</th>
                                        <th>Rating</th>
                                        <th>Pesan Ulasan</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1;
                                    if($query && mysqli_num_rows($query) > 0):
                                        while($row = mysqli_fetch_assoc($query)): 
                                            $id_testi = $row['id_testimonial']; // Sesuaikan dengan kolom primary key di database Anda
                                    ?>
                                    <tr>
                                        <td class="text-center text-muted"><?= $no++; ?></td>
                                        <td><strong><?= htmlspecialchars($row['name']); ?></strong></td>
                                        <td>
                                            <span class="text-warning font-weight-bold">
                                                <?= str_repeat('★', (int)$row['rating']); ?>
                                                <small class="text-muted">(<?= $row['rating']; ?>/5)</small>
                                            </span>
                                        </td>
                                        <td><?= nl2br(htmlspecialchars($row['message'])); ?></td>
                                        <td class="text-center">
                                            <a href="aksi_testimoni.php?action=delete&id=<?= $id_testi; ?>" class="btn btn-sm btn-danger font-weight-bold" onclick="return confirm('Yakin ingin menghapus ulasan ini?')">
                                                <i class="fas fa-trash mr-1"></i> Hapus
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; else: ?>
                                    <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada ulasan yang masuk.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
            <?php include "footer.php"; ?>
        </div>
    </div>
    <?php include "buttom.php"; ?>
</body>
</html>