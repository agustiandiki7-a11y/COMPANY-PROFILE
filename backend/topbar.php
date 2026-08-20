<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop"
            class="btn btn-link d-md-none rounded-circle mr-3">

        <i class="fa fa-bars"></i>

    </button>


    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">


        <!-- Nav Item - Messages -->
        <li class="nav-item dropdown no-arrow mx-1">

            <a class="nav-link"
               href="tabel_messages.php"
               title="Messages">

                <i class="fas fa-envelope fa-fw"></i>

                <!-- Counter - Messages -->
                <span class="badge badge-danger badge-counter">
                    <?php

                    include "connection.php";

                    $query_messages = mysqli_query(
                        $koneksi,
                        "SELECT * FROM messages"
                    );

                    $jumlah_messages_topbar = mysqli_num_rows(
                        $query_messages
                    );

                    echo $jumlah_messages_topbar;

                    ?>
                </span>

            </a>

        </li>


        <!-- Divider -->
        <div class="topbar-divider d-none d-sm-block"></div>


        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">

            <a class="nav-link dropdown-toggle"
               href="#"
               id="userDropdown"
               role="button"
               data-toggle="dropdown"
               aria-haspopup="true"
               aria-expanded="false">

                <span class="mr-2 d-none d-lg-inline text-gray-600 small">

                    <?php

                    if (isset($_SESSION['username'])) {
                        echo htmlspecialchars($_SESSION['username']);
                    } else {
                        echo "Admin";
                    }

                    ?>

                </span>

                <i class="fas fa-user-circle fa-2x text-gray-400"></i>

            </a>


            <!-- Dropdown -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                 aria-labelledby="userDropdown">


                <!-- Profile -->
                <a class="dropdown-item"
                   href="tabel_profile.php">

                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>

                    Profile

                </a>


                <!-- Divider -->
                <div class="dropdown-divider"></div>


                <!-- Logout -->
                <a class="dropdown-item"
                   href="logout.php">

                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>

                    Logout

                </a>

            </div>

        </li>

    </ul>

</nav>
<!-- End of Topbar -->