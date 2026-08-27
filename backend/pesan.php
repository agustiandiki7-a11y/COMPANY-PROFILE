<?php
// Sertakan koneksi database
include "connection.php";

// Ambil tanggal yang dipilih (default tanggal hari ini)
$tanggal_pilih = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');

// Query untuk mencari nomor kursi yang sudah dipesan dan statusnya masih Pending atau Disetujui
$query_kursi = mysqli_query($koneksi, "
    SELECT chair_number 
    FROM bookings 
    WHERE booking_date = '$tanggal_pilih' 
    AND status IN ('Disetujui', 'Pending')
");

$kursi_terisi = [];
while($row = mysqli_fetch_assoc($query_kursi)) {
    // Masukkan nomor kursi yang sudah dipesan ke dalam array
    $kursi_terisi[] = $row['chair_number']; 
}
?>

<!-- CONTOH PENERAPAN DI FORM FRONTEND -->
<div class="card p-4">
    <h4>Pilih Nomor Kursi Barbershop</h4>
    <p class="text-muted">Tanggal terpilih: <strong><?= $tanggal_pilih; ?></strong></p>

    <form action="proses_booking.php" method="POST">
        <!-- Input Tanggal (jika diganti, reload halaman untuk update kursi terisi) -->
        <div class="mb-3">
            <label>Tanggal Kunjungan</label>
            <input type="date" name="booking_date" class="form-control" value="<?= $tanggal_pilih; ?>" onchange="location.href='booking.php?tanggal='+this.value">
        </div>

        <div class="mb-3">
            <label>Pilih Kursi (Kursi yang abu-abu/terisi berarti sudah dipesan)</label>
            <div class="row mt-2">
                <?php 
                // Misalkan ada 8 kursi total di barbershop
                for($i = 1; $i <= 8; $i++): 
                    // Cek apakah nomor kursi $i ada di dalam daftar kursi terisi
                    $sudah_dipesan = in_array($i, $kursi_terisi); 
                ?>
                    <div class="col-3 mb-3">
                        <div class="form-check button-kursi-wrapper">
                            <input class="form-check-input" type="radio" name="chair_number" id="chair_<?= $i; ?>" value="<?= $i; ?>" <?= $sudah_dipesan ? 'disabled' : ''; ?> required>
                            <label class="form-check-label btn w-100 <?= $sudah_dipesan ? 'btn-secondary text-white' : 'btn-outline-dark'; ?>" for="chair_<?= $i; ?>" style="cursor: <?= $sudah_dipesan ? 'not-allowed' : 'pointer'; ?>;">
                                Kursi #<?= $i; ?><br>
                                <small><?= $sudah_dipesan ? 'Terisi / Dipesan' : 'Tersedia'; ?></small>
                            </label>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>

        <!-- Tombol Kirim Booking -->
        <button type="submit" class="btn btn-dark btn-block">Konfirmasi Pesan Booking</button>
    </form>
</div>                     