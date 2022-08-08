<?php 
require "partials/head.php";
require "partials/sidebar.php"; ?>
<div class="container-fluid">
<?php

require "vendor/autoload.php";

use StelinDB\Database\QueryBuilder;
use Carbon\Carbon;

$dotenv = new \Dotenv\Dotenv(__DIR__);
            $dotenv->load();
$now = new Carbon;
$now->setTimezone('Asia/Jakarta');

$qb = new QueryBuilder(\StelinDB\Database\Connection::Connect());

        if(isset($_POST['simpan_user'])){
            $aksi = $qb->insert('user', [
              'username' => $_POST['username'],
              'pass' => md5($_POST['password']),
              'role' => 2

            ]);
            if($aksi){
                echo '<div class="col-lg-12 mb-4">
                    <div class="card bg-success text-white shadow">
                        <div class="card-body">
                            Berhasil
                            <div class="text-white-50 small">Data Tersimpan</div>
                        </div>
                    </div>
                </div>';
            }else{
                echo '<div class="col-lg-12 mb-4">
                <div class="card bg-danger text-white shadow">
                    <div class="card-body">
                        Gagal
                        <div class="text-white-50 small">Data Gagal Tersimpan</div>
                    </div>
                 </div>
                </div>';
            }
        }
        if(isset($_POST['update_user'])){
            // print_r("UPDATE user SET username='".$_POST['username']."',pass='".md5($_POST['password'])."' where id=".$_POST['id_user']);
            // die();
            $aksi = $qb->RAW(
            "UPDATE user SET username='".$_POST['username']."',pass='".md5($_POST['password'])."' where id_user=".$_POST['id_user'],[]);
            if($aksi){
                echo '<div class="col-lg-12 mb-4">
                    <div class="card bg-success text-white shadow">
                        <div class="card-body">
                            Berhasil
                            <div class="text-white-50 small">Data Tersimpan</div>
                        </div>
                    </div>
                </div>';
            }else{
                echo '<div class="col-lg-12 mb-4">
                <div class="card bg-danger text-white shadow">
                    <div class="card-body">
                        Gagal
                        <div class="text-white-50 small">Data Gagal Tersimpan</div>
                    </div>
                 </div>
                </div>';
            }
        }
        if(isset($_GET['hapus_user'])){
            $aksi = $qb->RAW(
            "DELETE from user where id_user=".$_GET['hapus_user'],[]);
            if($aksi){
                echo '<div class="col-lg-12 mb-4">
                    <div class="card bg-success text-white shadow">
                        <div class="card-body">
                            Berhasil
                            <div class="text-white-50 small">Data Terhapus</div>
                        </div>
                    </div>
                </div>';
            }else{
                echo '<div class="col-lg-12 mb-4">
                <div class="card bg-danger text-white shadow">
                    <div class="card-body">
                        Gagal
                        <div class="text-white-50 small">Data Gagal Terhapus</div>
                    </div>
                 </div>
                </div>';
            }
        }


    $data_user = $qb->RAW(
    "SELECT * FROM user",[]);
    if(isset($_POST['cari_user'])){
        $data_siswa = $qb->RAW("SELECT *,kelas.kelas as nama_kelas FROM siswa 
            join kelas on kelas.id_kelas=siswa.kelas
            where siswa.cetak=0 and siswa.user_input=".$_POST['data_user'],[]);  
    }else{
        $data_siswa = $qb->RAW("SELECT *,kelas.kelas as nama_kelas FROM siswa 
            join kelas on kelas.id_kelas=siswa.kelas
            where siswa.cetak=0 ",[]);
    }

    ?>

     <form  role="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
        <div class="col-lg-12 mb-2">
            <div class="input-group">
                <select class="form-control" name="data_user">
                    <option value="">User</option>
                    <?php
                    foreach ($data_user as $user) {
                        if($user->id_user == $_POST['data_user']){
                        echo '<option value="'.$user->id_user.'" selected>'.$user->username.'</option>';}
                        else{
                        echo '<option value="'.$user->id_user.'">'.$user->username.'</option>';}
                    }
                    ?>
                </select>
              <div class="input-group-prepend">
               <button type="submit" name="cari_user" class="input-group-text"><span  id="">Cari</span></button>
              </div>

            </div>
        </div>
    </form>


    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Daftar Belum Cetak</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">

  
            <div class="table-responsive">
                <form  role="form" action="print.php" method="post" autocomplete="off" enctype="multipart/form-data" >
                    <button type="submit" name="simpan_user" class="input-group-text"><span  id="">Cetak</span></button>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>NISN</th>
                            <th><center>Tandai</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach ($data_siswa as $siswa) {
                            ?>
                        <tr>
                            <td><img style="display: block; margin-left: auto;  margin-right: auto;   width: 70px;" class="img-responsive img" src="asset/foto/<?php echo $siswa->foto;?>"></td>
                            <td><?php echo $siswa->nama;?></td>
                             <td><?php echo $siswa->nama_kelas;?></td>
                             <td><?php echo $siswa->nisn;?></td>
                             <td>
                                <center>
                                  <input name='selector[]' type='checkbox' name='tandai' class='minimal flat' value='<?php echo $siswa->id; ?>'>
                                </center>
                            </td>
                        </tr>
                        <?php } ?>
                            <!-- <button type="submit" class="btn btn-primary"><i class="fa fa-print"></i> CETAK</button> -->
                        
                    </tbody>
                </table>
                        </form>
            </div>
        </div>
    </div>
</div>
<?php require "partials/footer.php"; ?>