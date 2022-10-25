
 <?php if(empty($_SESSION['id_user'])){
      header("Location: index.php");
  } 
  date_default_timezone_set("Asia/Jakarta");
  ?>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

                <?php if($_SESSION['role'] == 3){?>
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="">
                <div class="sidebar-brand-icon rotate-n-15">
                    <!-- <i class="fas fa-laugh-wink"></i> -->
                    <!-- <i class="fa-brands fa-nfc-directional"></i> -->
                </div>
                <div class="sidebar-brand-text mx-3"><?php echo $_SESSION['lembaga_pst'];?></div>
                <br>
            </a>
                <?php } ?>
            <hr class="sidebar-divider">            
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="">
                <?php if(!empty($_SESSION['lembaga'])){?>
                <div class="sidebar-brand-text mx-3"><?php echo $_SESSION['lembaga'];?></div>
                <?php }else{?>
                <div class="sidebar-brand-text mx-3">KlikTap <sup>NFC</sup></div>
                <?php } ?>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">
            <?php 
            $link = $_SERVER['PHP_SELF'];
            $link_array = explode('/',$link);
            $page = end($link_array);


            $setting=1;
            $absen=1;
            $perpustakaan=1;
            $perpus_online=1;
            $data_siswa=1;
            $nilai=1;
            $topup=1;
            $merchan=1;
            $spp=1;
            $akses_parkir=1;
            $akses_gerbang=1;
            $tracking=1;
            $izin=1;

            if($_SESSION['role'] == 3){
                $menu=explode(",",$_SESSION['sidebar_data']);
                $status=explode(",",$_SESSION['sidebar_status']);
                // print_r($menu);print_r($status);die();
                for ($i=0; $i < count($menu); $i++) { 
                    // code...
                    $name=$menu[$i];
                    $$name=$status[$i];
                }
            }

            // setting
            if($page == 'setting_raport.php' && $setting == 0){header("Location: home.php");}
            else if($page == 'setting_absen.php' && $setting == 0){header("Location: home.php");}
            else if($page == 'toko_setting.php' && $setting == 0){header("Location: home.php");}
            else if($page == 'user_cetak.php' && $setting == 0){header("Location: home.php");}
            else if($page == 'users.php' && $setting == 0){header("Location: home.php");}
            else if($page == 'subusers.php' && $setting == 0){header("Location: home.php");}
            // absen
            else if($page == 'absen.php' && $absen == 0){header("Location: home.php");}
            else if($page == 'rekap.php' && $absen == 0){header("Location: home.php");}
            else if($page == 'izin_siswa.php' && $izin == 0){header("Location: home.php");}
            // perpus
            else if($page == 'perpus.php' && $perpustakaan == 0){header("Location: home.php");}
            else if($page == 'daftar_peminjaman.php' && $perpustakaan == 0){header("Location: home.php");}
            else if($page == 'daftar_peminjaman_sudah.php' && $perpustakaan == 0){header("Location: home.php");}
            else if($page == 'daftar_buku.php' && $perpustakaan == 0){header("Location: home.php");}
            else if($page == 'input_buku.php' && $perpustakaan == 0){header("Location: home.php");}
            // data siswa
            else if($page == 'input_siswa.php' && $data_siswa == 0){header("Location: home.php");}
            else if($page == 'data_siswa.php' && $data_siswa == 0){header("Location: home.php");}
            // nilai
            else if($page == 'input_nilai.php' && $nilai == 0){header("Location: home.php");}
            // transaksi
            else if($page == 'topup.php' && $topup == 0){header("Location: home.php");}
            else if($page == 'merchan.php' && $merchan == 0){header("Location: home.php");}
            // spp
            else if($page == 'spp.php' && $spp == 0){header("Location: home.php");}
            else if($page == 'akses_gerbang.php' && $akses_gerbang == 0){header("Location: home.php");}
            else if($page == 'akses_parkir.php' && $akses_parkir == 0){header("Location: home.php");}
            else if($page == 'maps.php' && $tracking == 0){header("Location: home.php");}
            else if($page == 'cetak.php' && $_SESSION['role'] != 1){header("Location: home.php");}
            else if($page == 'sudah_cetak.php' && $_SESSION['role'] != 1){header("Location: home.php");}
            else if($page == 'users.php' && $_SESSION['role'] != 1){header("Location: home.php");}
            else if($page == 'subusers.php' && $_SESSION['role'] == 3){header("Location: home.php");}
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
            if($setting == 1){
            if($page=='setting_raport.php' || $page=='users.php'|| $page=='subusers.php' || $page=='setting_absen.php' || $page=='toko_setting.php' || $page=='user_cetak.php'){
                echo '<li class="nav-item active">';
            }else{
                echo '<li class="nav-item">';}
            ?>
            <!-- <li class="nav-item"> -->
                <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseUtilitiessetting"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Setting</span>
                </a>
                <div id="collapseUtilitiessetting" 
                <?php 
                if($page=='setting_raport.php' || $page=='setting_absen.php' || $page=='users.php'|| $page=='subusers.php' || $page=='toko_setting.php' || $page=='user_cetak.php'){
                    echo 'class="collapse show"';
                }else{
                    echo 'class="collapse"';}
                ?>
                aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Components:</h6>
                        <a
                        <?php 
                        if($page=='setting_raport.php'){
                            echo 'class="collapse-item active"';
                        }else{
                            echo 'class="collapse-item"';}
                        ?> 
                        href="setting_raport.php">Setting Raport</a>
                        <a 
                        <?php 
                        if($page=='setting_absen.php'){
                            echo 'class="collapse-item active"';
                        }else{
                            echo 'class="collapse-item"';}
                        ?> 
                        href="setting_absen.php">Setting Absensi & SK</a>
                        <a 
                        <?php 
                        if($page=='toko_setting.php'){
                            echo 'class="collapse-item active"';
                        }else{
                            echo 'class="collapse-item"';}
                        ?> 
                        href="toko_setting.php">Setting Toko</a>
                        <a 
                        <?php 
                        if($page=='user_cetak.php'){
                            echo 'class="collapse-item active"';
                        }else{
                            echo 'class="collapse-item"';}
                        ?> 
                        href="user_cetak.php">Setting Kartu</a>
                    <?php if($_SESSION['role'] == 1){?>
                        <a 
                        <?php 
                        if($page=='users.php'){
                            echo 'class="collapse-item active"';
                        }else{
                            echo 'class="collapse-item"';}
                        ?> 
                        href="users.php">Data Users</a>
                    <?php } if($_SESSION['role'] != 3){?>
                        <a 
                        <?php 
                        if($page=='subusers.php'){
                            echo 'class="collapse-item active"';
                        }else{
                            echo 'class="collapse-item"';}
                        ?> 
                        href="subusers.php">Data subUsers</a>
                    <?php } ?>
                    </div>
                </div>
            </li>

            <?php
            }if($absen == 1){ 
            if($page=='absen.php' || $page=='rekap.php' || $page=='izin_siswa.php'){
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
                if($page=='absen.php' || $page=='rekap.php' || $page=='izin_siswa.php'){
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
                        <?php if($izin == 1){ ?>
                        <a 
                        <?php 
                        if($page=='izin_siswa.php'){
                            echo 'class="collapse-item active"';
                        }else{
                            echo 'class="collapse-item"';}
                        ?> 
                        href="izin_siswa.php">Izin dan Telat</a>
                        <?php  } ?>
                    </div>
                </div>
            </li>

             <?php 
            }if($perpustakaan == 1){  
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
                        href="perpus.php">Sirkulasi</a>
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
                        href="daftar_peminjaman_sudah.php">Daftar Pengembalian</a>
                        <a 
                        <?php 
                        if($page=='daftar_buku.php'){
                            echo 'class="collapse-item active"';
                        }else{
                            echo 'class="collapse-item"';}
                        ?> 
                        href="daftar_buku.php">Data Barang</a>
                        <a 
                        <?php 
                        if($page=='input_buku.php'){
                            echo 'class="collapse-item active"';
                        }else{
                            echo 'class="collapse-item"';}
                        ?> 
                        href="input_buku.php">Input Barang</a>
                    </div>
                </div>
            </li>
            <?php
        }if($perpus_online ==1 ){ ?>
            <li class="nav-item">
                <a class="nav-link" target="_blank" href="https://www.perpusnas.go.id" >
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Perpustakaan Online</span>
                </a>

            </li>

            <?php 
        }if($data_siswa ==1 ){
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
        }if($nilai == 1 ){
            if($page=='input_nilai.php'){
                echo '<li class="nav-item active">';
            }else{
                echo '<li class="nav-item">';}
            ?>
            <!-- <li class="nav-item"> -->
                <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseUtilitiesnilai"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Nilai</span>
                </a>
                <div id="collapseUtilitiesnilai" 
                <?php 
                if($page=='input_nilai.php'){
                    echo 'class="collapse show"';
                }else{
                    echo 'class="collapse"';}
                ?>
                aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Components:</h6>
                        <a
                        <?php 
                        if($page=='input_nilai.php'){
                            echo 'class="collapse-item active"';
                        }else{
                            echo 'class="collapse-item"';}
                        ?> 
                        href="input_nilai.php">Input Nilai</a>
                    </div>
                </div>
            </li>

            <?php 
        }
            if($topup == 1 || $merchan == 1 ){
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
                        <?php if($topup == 1 ){ ?>
                        <a
                        <?php 
                        if($page=='topup.php'){
                            echo 'class="collapse-item active"';
                        }else{
                            echo 'class="collapse-item"';}
                        ?> 
                        href="topup.php">Topup</a>
                        <?php }if($merchan == 1 ){ ?>
                        <a 
                        <?php 
                        if($page=='merchan.php'){
                            echo 'class="collapse-item active"';
                        }else{
                            echo 'class="collapse-item"';}
                        ?> 
                        href="merchan.php">Toko</a>
                        <?php } ?>
                    </div>
                </div>
            </li>
            <?php 
        }
        if($spp == 1 ){
            if($page=='spp.php'){
                echo '<li class="nav-item active">';
            }else{
                echo '<li class="nav-item">';}
            ?>
                <a class="nav-link"  href="spp.php" >
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Sumbangan Komite</span>
                </a>
            </li>

            <?php
        }if($akses_parkir == 1 ){ 
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
        }if($akses_gerbang == 1 ){
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
            if($page=='cetak.php' || $page=='sudah_cetak.php'){
                echo '<li class="nav-item active">';
            }else{
                echo '<li class="nav-item">';}
            ?>
            <!-- <li class="nav-item"> -->
                <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapse_cetak"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Cetak</span>
                </a>
                <div id="collapse_cetak" 
                <?php 
                if($page=='cetak.php' || $page=='sudah_cetak.php'){
                    echo 'class="collapse show"';
                }else{
                    echo 'class="collapse"';}
                ?>
                aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Components:</h6>
                        <a
                        <?php 
                        if($page=='cetak.php'){
                            echo 'class="collapse-item active"';
                        }else{
                            echo 'class="collapse-item"';}
                        ?> 
                        href="cetak.php">Belum Cetak</a>
                        <a
                        <?php 
                        if($page=='sudah_cetak.php'){
                            echo 'class="collapse-item active"';
                        }else{
                            echo 'class="collapse-item"';}
                        ?> 
                        href="sudah_cetak.php">Sudah Cetak</a>
                    </div>
                </div>
            </li>
            <?php } ?>

             <?php 
        }if($tracking == 1 ){
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
        <?php }?>


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