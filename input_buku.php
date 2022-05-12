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


    $table='kelas';
    $data_kelas = $qb->RAW(
    "SELECT * FROM kelas",[]);
    // print_r($data_kelas);
    // die();
 if(isset($_POST['simpan_data'])){

  $norfid = $_POST['rfid'];
  $judul = $_POST['judul'];
  $tahun = $_POST['tahun'];
  $penerbit = $_POST['penerbit'];
  $penulis = $_POST['penulis'];

$user = $qb->RAW(
    "SELECT * FROM buku WHERE rfid='".$norfid."'",[]);


// print_r($user);
// die();
  if (array_key_exists(0, $user)) {
    echo'
    <div class="col-lg-12 mb-4">
        <div class="card bg-danger text-white shadow">
            <div class="card-body">
                Gagal
                <div class="text-white-50 small">Data RFID Sudah Ada</div>
            </div>
        </div>
    </div>
    ';
  }else{


$rekapAbsen = $qb->insert('buku', [
          'rfid' => $norfid,
          'judul_buku' => $judul,
          'tahun_terbit' => $tahun,
          'penerbit' => $penerbit,
          'penulis' => $penulis
        ]);


if($rekapAbsen){
   echo '
   <div class="col-lg-12 mb-4">
        <div class="card bg-success text-white shadow">
            <div class="card-body">
                Berhasil
                <div class="text-white-50 small">Data Tersimpan</div>
            </div>
        </div>
    </div>
    '; 
}else{
    echo'
    <div class="col-lg-12 mb-4">
        <div class="card bg-danger text-white shadow">
            <div class="card-body">
                Gagal
                <div class="text-white-50 small">Data Gagal Tersimpan</div>
            </div>
        </div>
    </div>
    ';
}
}
}

    ?>
    
<form  role="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
    <div class="mb-3"><label for="exampleFormControlInput1">Judul Buku</label><input class="form-control" name="judul" type="text" placeholder="Judul Buku" required></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Tahun Terbit</label><input class="form-control" name="tahun" type="text" placeholder="Tahun" required></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Penerbit</label><input class="form-control" name="penerbit" type="text" placeholder="Penerbit" required></div>
    <div class="mb-3"><label for="exampleFormControlInput1">RFID</label><input class="form-control" name="rfid" type="text" placeholder="RFID" required></div>       
    <div class="mb-3"><label for="exampleFormControlInput1">Penulis</label><input class="form-control" name="penulis" type="text" placeholder="Penulis" required></div>
    <button name="simpan_data" type='submit' class="btn btn-primary btn-user btn-block">Simpan</button>
</form>
</div>
 <?php require "partials/footer.php"; ?>