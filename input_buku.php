<?php 
require "partials/head.php";
require "partials/sidebar.php"; 
require "asset/phpqrcode/qrlib.php"; ?>

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

  // $norfid = $_POST['rfid'];
  $judul = $_POST['judul'];
  $tahun = $_POST['tahun'];
  $penerbit = $_POST['penerbit'];
  $penulis = $_POST['penulis'];
  $penulis = $_POST['penulis'];
  $jumlah = $_POST['jumlah'];
  $induk = $_POST['induk'];
  $kategori = $_POST['kategori'];
  $t_pengadaan = $_POST['t_pengadaan'];

// $user = $qb->RAW(
    // "SELECT * FROM buku WHERE rfid='".$norfid."'",[]);
$user = $qb->RAW(
    "SELECT * FROM buku WHERE induk>=".$induk." and induk<=".($induk+$jumlah)." and user='".$_SESSION['id_user']."'",[]);


// print_r("SELECT * FROM buku WHERE induk>=".$induk." and induk<=".($induk+$jumlah)." and user='".$_SESSION['id_user']."'");
// die();
  if (array_key_exists(0, $user)) {
    echo'
    <div class="col-lg-12 mb-4">
        <div class="card bg-danger text-white shadow">
            <div class="card-body">
                Gagal
                <div class="text-white-50 small">No Induk buku Sudah Ada</div>
            </div>
        </div>
    </div>
    ';
  }else{


// $rekapAbsen = $qb->insert('buku', [
//           'rfid' => $norfid,
//           'judul_buku' => $judul,
//           'tahun_terbit' => $tahun,
//           'penerbit' => $penerbit,
//           'penulis' => $penulis,
//           'user' => $_SESSION['id_user']
        // ]);
$buku = $qb->insert('master_buku', [
          'user' => $_SESSION['id_user'],
          'jumlah' => $jumlah,
          't_pengadaan' => $t_pengadaan
        ]);
for ($i=0; $i < $jumlah; $i++) { 
    $id = $qb->RAW("SELECT count(id_buku) as id_buku FROM buku where user=?",[$_SESSION['id_user']]); 
    if (array_key_exists(0, $id)) {
        $id=$id[0];
        $id=$id->id_buku + 1;
        $id = sprintf("%04s", $id);
    }else{
        $id=1;
        $id = sprintf("%04s", $id);
    }
    $norfid=$kategori.'.'.$id.'.'.$induk;
    $qb->insert('buku', [
              'rfid' => $norfid,
              'judul_buku' => $judul,
              'tahun_terbit' => $tahun,
              'penerbit' => $penerbit,
              'penulis' => $penulis,
              'user' => $_SESSION['id_user'],
              'master' => $buku,
              'induk' => $induk,
              'kategori' => $kategori
            ]);

            $nameqrcode    = $norfid.'.png';              
            $tempdir        = "asset/qrcode_buku/"; 
            $isiqrcode     = $norfid;
            $quality        = 'H';
            $Ukuran         = 10;
            $padding        = 0;

            QRCode::png($isiqrcode,$tempdir.$nameqrcode,$quality,$Ukuran,$padding);

    $induk++;
}


if($buku){
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
    <div class="mb-3"><label for="exampleFormControlInput1">Tanggal Pengadaan<sup style="color:brown;">*wajib</sup></label><input class="form-control" name="t_pengadaan" type="date" placeholder="Tanggal" required></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Kategori<sup style="color:brown;">*wajib</sup></label>
    <select class="form-control" name="kategori" required>
        <option value="">Pilih</option>
        <?php $k_buku = $qb->RAW("SELECT * FROM k_buku",[]); 
        foreach ($k_buku as $kategori) {
            echo '<option value="'.$kategori->id.'">'.$kategori->nama.'</option>';
        }
        ?>
    </select>
    </div>
    <div class="mb-3"><label for="exampleFormControlInput1">Judul Buku<sup style="color:brown;">*wajib</sup></label><input class="form-control" name="judul" type="text" placeholder="Judul Buku" required></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Tahun Terbit<sup style="color:brown;">*wajib</sup></label><input class="form-control" name="tahun" type="text" placeholder="Tahun" required></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Penerbit<sup style="color:brown;">*wajib</sup></label><input class="form-control" name="penerbit" type="text" placeholder="Penerbit" required></div>
    <!-- <div class="mb-3"><label for="exampleFormControlInput1">RFID</label><input class="form-control" name="rfid" type="text" placeholder="RFID" ></div>        -->
    <div class="mb-3"><label for="exampleFormControlInput1">Penulis<sup style="color:brown;">*wajib</sup></label><input class="form-control" name="penulis" type="text" placeholder="Penulis" required></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Jumlah Exemplar<sup style="color:brown;">*wajib</sup></label><input class="form-control" name="jumlah" type="number" placeholder="jumlah" required></div>
    <div class="mb-3"><label for="exampleFormControlInput1">No Induk<sup style="color:brown;">*wajib</sup></label><input class="form-control" name="induk" type="number" placeholder="jumlah" required></div>
    <button name="simpan_data" type='submit' class="btn btn-primary btn-user btn-block">Simpan</button>
</form>
</div>
 <?php require "partials/footer.php"; ?>