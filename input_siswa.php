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

  $nama = $_POST['nama'];
  $norfid = $_POST['norfid'];
  $nim = $_POST['nim'];
  $kelas = $_POST['kelas'];

$user = $qb->RAW(
    "SELECT * FROM siswa WHERE norf='".$norfid."'",[]);


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
$filename=$_FILES['file_kirim']['name'];
// echo $filename;
// die();
$upload=move_uploaded_file($_FILES['file_kirim']['tmp_name'],  "asset/foto/".$filename);

 for($x=0;$x<20;$x++){
if(filesize("asset/foto/".$filename)>50000)
{resizer("asset/foto/".$filename, "asset/foto/".$filename, 70);}else{ break;}
clearstatcache();
}
if($upload){
$rekapAbsen = $qb->insert('siswa', [
          'nama' => $nama,
          'nim' => $nim,
          'norf' => $norfid,
          'kelas' => $kelas,
          'foto' => $filename
        ]);
}

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
    <div class="mb-3"><label for="exampleFormControlInput1">Nama</label><input class="form-control" name="nama" type="text" placeholder="Masukkan Nama" required></div>
    <div class="mb-3"><label for="exampleFormControlInput1">No RFID</label><input class="form-control" name="norfid" type="text" placeholder="RFID" required></div>
    <div class="mb-3"><label for="exampleFormControlInput1">NIM</label><input class="form-control" name="nim" type="text" placeholder="RFID" required></div>
    <div class="mb-3">
        <label for="exampleFormControlSelect1">Kelas</label>
        <select name="kelas" class="form-control" id="exampleFormControlSelect1" required>
            <option value="">Pilih Kelas</option>
            <?php foreach ($data_kelas as $data_kelas) {
                echo '<option value="'.$data_kelas->id_kelas.'">'.$data_kelas->kelas.'</option>';
            }?>
            <!-- <option>1</option>
            <option>2</option>
            <option>3</option>
            <option>4</option>
            <option>5</option> -->
        </select>
    </div>
    <div class="mb-3"><label for="exampleFormControlInput1">Foto</label><input class="form-control" name="file_kirim" type="file" placeholder="RFID" required></div>
    <button name="simpan_data" type='submit' class="btn btn-primary btn-user btn-block">Simpan</button>
</form>
</div>
 <?php require "partials/footer.php"; ?>