
 <?php if(empty($_SESSION['id_user'])){
      header("Location: index.php");
  } ?>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="">
                <div class="sidebar-brand-icon rotate-n-15">
                    <!-- <i class="fas fa-laugh-wink"></i> -->
                    <i class="fa-brands fa-nfc-directional"></i>
                </div>
                <div class="sidebar-brand-text mx-3">KlikTap <sup>NFC</sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">
            <?php 
            $link = $_SERVER['PHP_SELF'];
            $link_array = explode('/',$link);
            $page = end($link_array);
            ?>

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="home.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Interface
            </div>
            <!-- <li class="nav-item">
                <a class="nav-link" href="absen.php">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Absensi</span></a>
            </li> -->
            <!-- Nav Item - Pages Collapse Menu -->
            <?php 
            if($page=='absen.php' || $page=='rekap.php' || $page=='setting_absen.php'){
                echo '<li class="nav-item active">';
            }else{
                echo '<li class="nav-item">';}
            ?>
            <!-- <li class="nav-item"> -->
                <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Absensi</span>
                </a>
                <div id="collapseUtilities" 
                <?php 
                if($page=='absen.php' || $page=='rekap.php' || $page=='setting_absen.php' || $page=='users.php'){
                    echo 'class="collapse show"';
                }else{
                    echo 'class="collapse"';}
                ?>
                aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Components:</h6>
                        <a
                        <?php 
                        if($page=='absen.php'){
                            echo 'class="collapse-item active"';
                        }else{
                            echo 'class="collapse-item"';}
                        ?> 
                        href="absen.php">Absensi</a>
                        <a 
                        <?php 
                        if($page=='rekap.php'){
                            echo 'class="collapse-item active"';
                        }else{
                            echo 'class="collapse-item"';}
                        ?> 
                        href="rekap.php">Rekap Absen (Hari Ini)</a>
                        <a 
                        <?php 
                        if($page=='setting_absen.php'){
                            echo 'class="collapse-item active"';
                        }else{
                            echo 'class="collapse-item"';}
                        ?> 
                        href="setting_absen.php">Setting Absen</a>
                    <?php if($_SESSION['role'] == 1){?>
                        <a 
                        <?php 
                        if($page=='users.php'){
                            echo 'class="collapse-item active"';
                        }else{
                            echo 'class="collapse-item"';}
                        ?> 
                        href="users.php">Data Users</a>
                    <?php } ?>
                    </div>
                </div>
            </li>

             <?php 
            if($page=='perpus.php' || $page=='daftar_buku.php' || $page=='input_buku.php' || $page=='daftar_peminjaman.php' || $page=='daftar_peminjaman_sudah.php'){
                echo '<li class="nav-item active">';
            }else{
                echo '<li class="nav-item">';}
            ?>
            <!-- <li class="nav-item"> -->
                <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseUtilities1"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Perpustakaan</span>
                </a>
                <div id="collapseUtilities1" 
                <?php 
                if($page=='perpus.php' || $page=='daftar_buku.php' || $page=='input_buku.php' || $page=='daftar_peminjaman.php' || $page=='daftar_peminjaman_sudah.php'){
                    echo 'class="collapse show"';
                }else{
                    echo 'class="collapse"';}
                ?>
                aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Components:</h6>
                        <a
                        <?php 
                        if($page=='perpus.php'){
                            echo 'class="collapse-item active"';
                        }else{
                            echo 'class="collapse-item"';}
                        ?> 
                        href="perpus.php">Pinjam</a>
                        <a
                        <?php 
                        if($page=='daftar_peminjaman.php'){
                            echo 'class="collapse-item active"';
                        }else{
                            echo 'class="collapse-item"';}
                        ?> 
                        href="daftar_peminjaman.php">Daftar belum</a>
                        <a
                        <?php 
                        if($page=='daftar_peminjaman_sudah.php'){
                            echo 'class="collapse-item active"';
                        }else{
                            echo 'class="collapse-item"';}
                        ?> 
                        href="daftar_peminjaman_sudah.php">Daftar sudah</a>
                        <a 
                        <?php 
                        if($page=='daftar_buku.php'){
                            echo 'class="collapse-item active"';
                        }else{
                            echo 'class="collapse-item"';}
                        ?> 
                        href="daftar_buku.php">Daftar Buku</a>
                        <a 
                        <?php 
                        if($page=='input_buku.php'){
                            echo 'class="collapse-item active"';
                        }else{
                            echo 'class="collapse-item"';}
                        ?> 
                        href="input_buku.php">Input Buku</a>
                    </div>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="https://www.perpusnas.go.id" >
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Perpustakaan Online</span>
                </a>

            </li>

            <?php 
            if($page=='input_siswa.php' || $page=='data_siswa.php'){
                echo '<li class="nav-item active">';
            }else{
                echo '<li class="nav-item">';}
            ?>
            <!-- <li class="nav-item"> -->
                <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapse2"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Data Siswa</span>
                </a>
                <div id="collapse2" 
                <?php 
                if($page=='input_siswa.php' || $page=='data_siswa.php'){
                    echo 'class="collapse show"';
                }else{
                    echo 'class="collapse"';}
                ?>
                aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Components:</h6>
                        <a
                        <?php 
                        if($page=='input_siswa.php'){
                            echo 'class="collapse-item active"';
                        }else{
                            echo 'class="collapse-item"';}
                        ?> 
                        href="input_siswa.php">Input Data Siswa</a>
                        <a
                        <?php 
                        if($page=='data_siswa.php'){
                            echo 'class="collapse-item active"';
                        }else{
                            echo 'class="collapse-item"';}
                        ?> 
                        href="data_siswa.php">Data Siswa</a>
                    </div>
                </div>
            </li>

            <?php 
            if($page=='topup.php' || $page=='merchan.php'){
                echo '<li class="nav-item active">';
            }else{
                echo '<li class="nav-item">';}
            ?>
            <!-- <li class="nav-item active"> -->
                <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Transaksi</span>
                </a>
                <div id="collapseTwo" 
                <?php 
                if($page=='topup.php' || $page=='merchan.php'){
                    echo 'class="collapse show"';
                }else{
                    echo 'class="collapse"';}
                ?>
                aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Components:</h6>
                        <a
                        <?php 
                        if($page=='topup.php'){
                            echo 'class="collapse-item active"';
                        }else{
                            echo 'class="collapse-item"';}
                        ?> 
                        href="topup.php">Topup</a>
                        <a 
                        <?php 
                        if($page=='merchan.php'){
                            echo 'class="collapse-item active"';
                        }else{
                            echo 'class="collapse-item"';}
                        ?> 
                        href="merchan.php">Merchant</a>
                    </div>
                </div>
            </li>
            <?php 
            if($page=='spp.php'){
                echo '<li class="nav-item active">';
            }else{
                echo '<li class="nav-item">';}
            ?>
                <a class="nav-link"  href="spp.php" >
                    <i class="fas fa-fw fa-cog"></i>
                    <span>SPP</span>
                </a>
            </li>

            <?php 
            if($page=='akses_parkir.php'){
                echo '<li class="nav-item active">';
            }else{
                echo '<li class="nav-item">';}
            ?>
                <a class="nav-link" href="akses_parkir.php" >
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Akses Parkir</span>
                </a>
            </li>

            <?php 
            if($page=='akses_gerbang.php' ){
                echo '<li class="nav-item active">';
            }else{
                echo '<li class="nav-item">';}
            ?>
                <a class="nav-link" href="akses_gerbang.php" >
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Akses Gerbang</span>
                </a>

            </li>

             <?php 
            if($_SESSION['role'] == 1){
            if($page=='cetak.php' ){
                echo '<li class="nav-item active">';
            }else{
                echo '<li class="nav-item">';}
            ?>
                <a class="nav-link" href="cetak.php" >
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Cetak</span>
                </a>

            </li>
            <?php } ?>

             <?php 
            if($page=='maps.php' ){
                echo '<li class="nav-item active">';
            }else{
                echo '<li class="nav-item">';}
            ?>
                <a class="nav-link" href="maps.php" >
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Tracking</span>
                </a>

            </li>


            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>


        </ul>
        <!-- End of Sidebar -->
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>


                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        
                            

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['nama_user'];?></span>
                                <img class="img-profile rounded-circle"
                                    src="asset/img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <!-- <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div> -->
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

<!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Peringatan</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Apa anda yakin untuk keluar?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>