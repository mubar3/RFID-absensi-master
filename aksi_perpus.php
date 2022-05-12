<?php

require "vendor/autoload.php";
require "partial/head.php";
use StelinDB\Database\QueryBuilder;
use Carbon\Carbon;

$dotenv = new \Dotenv\Dotenv(__DIR__);
            $dotenv->load();
$now = new Carbon;
$now->setTimezone('Asia/Jakarta');

$qb = new QueryBuilder(\StelinDB\Database\Connection::Connect());

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $isi = $_POST['isi'];
    // echo $isi;
    // die();
    if(empty($isi)){echo "buku kosong"; die();}
    else{

      $data_buku=explode(',', $isi);
      $total_buku=count($data_buku);
      // print_r($total);
      // die();
            for($x=0;$x<$total_buku;$x++) {
              $status=0;
              $data_rfid_buku = $qb->RAW(
              "SELECT * FROM buku",[]);
              foreach ($data_rfid_buku as $data_rfid_buku) {
                if($data_buku[$x]==($data_rfid_buku->rfid)){
                  $status=1;
                }
              }
              if($status==0){echo 'RFID buku ada yang tidak terdaftar';die();}  
            }

    $user = $qb->RAW(
    "SELECT * FROM siswa WHERE norf=?",[$id]);
  if (array_key_exists(0, $user)) {

    $rekapAbsen = $qb->insert('peminjaman', [
          'peminjam' => $id,
          'buku' => $isi
        ]);
    if($rekapAbsen){
      echo 'Berhasil';
    }else{
      echo 'Tidak berhasil, coba ulangi lagi';
    }
  }else{
    echo 'RFID Siswa tidak terdaftar';die();
  }

  }
}
