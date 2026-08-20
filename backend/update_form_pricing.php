<?php

session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php?pesan=belum_login");
    exit;
}

include "connection.php";

$id_pricing = $_GET['id_pricing'] ?? '';

if ($id_pricing == '') {
    header("Location: tabel_pricing.php");
    exit;
}

$id_pricing = mysqli_real_escape_string($koneksi, $id_pricing);

$query = mysqli_query(
    $koneksi,
    "SELECT * FROM pricing
     WHERE id_pricing = '$id_pricing'"
);

if (!$query) {
    die("Query gagal: " . mysqli_error($koneksi));
}

$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>
        alert('Data pricing tidak ditemukan!');
        window.location='tabel_pricing.php';
    </script>";
    exit;
}

?>

<?php include "header.php"; ?>

<body id="page-top">

<div id="wrapper">

    <?php include "sidebar.php"; ?>

    <div id="content-wrapper" class="d-flex flex-column">

        <div id="content">

            <?php include "topbar.php"; ?>

            <div class="container-fluid">

                <h1 class="h3 mb-4 text-gray-800">
                    Update Pricing
                </h1>

                <div class="card shadow mb-4">

                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            Edit Pricing
                        </h6>
                    </div>

                    <div class="card-body">

                        <form
                            action="action_update_pricing.php"
                            method="POST"
                        >

                            <!-- ID -->
                            <input
                                type="hidden"
                                name="id_pricing"
                                value="<?php echo $data['id_pricing']; ?>"
                            >

                            <!-- Name -->
                            <div class="form-group">

                                <label>Name</label>

                                <input
                                    type="text"
                                    name="name"
                                    class="form-control"
                                    value="<?php echo htmlspecialchars($data['name']); ?>"
                                    required
                                >

                            </div>

                            <!-- Description -->
                            <div class="form-group">

                                <label>Description</label>

                                <textarea
                                    name="description"
                                    class="form-control"
                                    rows="4"
                                ><?php echo htmlspecialchars($data['description']); ?></textarea>

                            </div>

                            <!-- Price -->
                            <div class="form-group">

                                <label>Price</label>

                                <div class="input-group">

                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            Rp
                                        </span>
                                    </div>

                                    <input
                                        type="number"
                                        name="price"
                                        class="form-control"
                                        value="<?php echo $data['price']; ?>"
                                        min="0"
                                        required
                                    >

                                </div>

                            </div>

                            <!-- Duration -->
                            <div class="form-group">

                                <label>Duration</label>

                                <input
                                    type="text"
                                    name="duration"
                                    class="form-control"
                                    value="<?php echo htmlspecialchars($data['duration']); ?>"
                                >

                            </div>

                            <hr>

                            <a
                                href="tabel_pricing.php"
                                class="btn btn-secondary"
                            >
                                <i class="fas fa-arrow-left"></i>
                                Kembali
                            </a>

                            <button
                                type="submit"
                                class="btn btn-success"
                            >
                                <i class="fas fa-save"></i>
                                Update
                            </button>

                        </form>

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