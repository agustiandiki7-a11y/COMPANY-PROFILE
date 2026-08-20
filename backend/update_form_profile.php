    <?php

session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php?pesan=belum_login");
    exit;
}

include "connection.php";

$id_profile = $_GET['id_profile'] ?? '';

if ($id_profile == '') {
    header("Location: tabel_profile.php");
    exit;
}

$id_profile = mysqli_real_escape_string(
    $koneksi,
    $id_profile
);

$query = mysqli_query(
    $koneksi,
    "SELECT * FROM profile
     WHERE id_profile = '$id_profile'"
);

$data = mysqli_fetch_assoc($query);

if (!$data) {

    echo "<script>
        alert('Data profile tidak ditemukan!');
        window.location='tabel_profile.php';
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
                        Update Profile
                    </h1>

                    <div class="card shadow mb-4">

                        <div class="card-header py-3">

                            <h6 class="m-0 font-weight-bold text-primary">
                                Edit Profile GHD Barbershop
                            </h6>

                        </div>

                        <div class="card-body">

                            <form
                                action="action_update_profile.php"
                                method="POST"
                                enctype="multipart/form-data"
                            >

                                <input
                                    type="hidden"
                                    name="id_profile"
                                    value="<?php echo $data['id_profile']; ?>"
                                >

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

                                <div class="form-group">

                                    <label>Description</label>

                                    <textarea
                                        name="description"
                                        class="form-control"
                                        rows="4"
                                    ><?php echo htmlspecialchars($data['description']); ?></textarea>

                                </div>

                                <div class="form-group">

                                    <label>Address</label>

                                    <textarea
                                        name="address"
                                        class="form-control"
                                        rows="3"
                                    ><?php echo htmlspecialchars($data['address']); ?></textarea>

                                </div>

                                <div class="form-group">

                                    <label>Phone</label>

                                    <input
                                        type="text"
                                        name="phone"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($data['phone']); ?>"
                                    >

                                </div>

                                <div class="form-group">

                                    <label>Email</label>

                                    <input
                                        type="email"
                                        name="email"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($data['email']); ?>"
                                    >

                                </div>

                                <div class="form-group">

                                    <label>Instagram</label>

                                    <input
                                        type="text"
                                        name="instagram"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($data['instagram']); ?>"
                                    >

                                </div>

                                <div class="form-group">

                                    <label>Opening Hours</label>

                                    <input
                                        type="text"
                                        name="opening_hours"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($data['opening_hours']); ?>"
                                    >

                                </div>

                                <div class="form-group">

                                    <label>Logo Saat Ini</label>
                                    <br>

                                    <?php if (!empty($data['logo']) && file_exists("foto/" . $data['logo'])) : ?>

                                        <img
                                            src="foto/<?php echo htmlspecialchars($data['logo']); ?>"
                                            width="100"
                                            height="100"
                                            style="object-fit: cover;"
                                            class="rounded mb-3"
                                        >

                                    <?php else : ?>

                                        <p class="text-muted">
                                            Belum ada logo
                                        </p>

                                    <?php endif; ?>

                                </div>

                                <div class="form-group">

                                    <label>Ganti Logo</label>

                                    <input
                                        type="file"
                                        name="logo"
                                        class="form-control-file"
                                        accept=".jpg,.jpeg,.png,.webp"
                                    >

                                </div>

                                <hr>

                                <a
                                    href="tabel_profile.php"
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