<?php
require "partials/head.php";
require "vendor/autoload.php";
require "partial/head.php";
use StelinDB\Database\QueryBuilder;
use Carbon\Carbon;

date_default_timezone_set("Asia/Jakarta");

$dotenv = new \Dotenv\Dotenv(__DIR__);
            $dotenv->load();
$now = new Carbon;
$now->setTimezone('Asia/Jakarta');

$qb = new QueryBuilder(\StelinDB\Database\Connection::Connect());
$data_buku='';
if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $isi = $_POST['isi'];
    // echo $isi;
    // die();
    if(empty($isi)){echo "buku kosong"; die();}
    else{
      $buku=array();
      $data_buku=explode(',', $isi);
      $total_buku=count($data_buku);
      // print_r($total);
      // die();
            for($x=0;$x<$total_buku;$x++) {
              $status=0;
              $data_rfid_buku = $qb->RAW(
              "SELECT * FROM buku where user=?",[$_SESSION['id_user']]);
              foreach ($data_rfid_buku as $data_rfid_buku) {
                if($data_buku[$x]==($data_rfid_buku->rfid)){
                  $status=1;
                  array_push($buku,'<li>'.$data_rfid_buku->judul_buku.'</li>');
                }
              }
              if($status==0){echo 'RFID buku ada yang tidak terdaftar';die();}  
            }
            for($x=0;$x<$total_buku;$x++) {
              $qb->RAW("UPDATE buku set pinjam=0 where rfid=?",[$data_buku[$x]]);
            }

    $user = $qb->RAW(
    "SELECT * FROM siswa WHERE norf=? and user_input=?",[$id,$_SESSION['id_user']]);
  if (array_key_exists(0, $user)) {
    $buku = $qb->RAW("SELECT *,date(tanggal) as tanggal FROM peminjaman WHERE user=?",[$_SESSION['id_user']]);
    if (array_key_exists(0, $user)) {
        $status=0;
        $id='';
        foreach ($buku as $data_bukuid) {
          $id_buku=explode(',', $data_bukuid->buku);
          $result=array_diff($data_buku,$id_buku);
          if(count($result)==0 && count($id_buku)==count($data_buku)){
            $status=1;
            $id=$data_bukuid->id_peminjaman;
          }
        }
        // echo 1;die();
        if($status == 1){
            $data_user = $qb->RAW("SELECT * FROM user where id_user=?",[$_SESSION['id_user']]);

            $date1=date_create($buku[0]->tanggal);
            $date2=date_create(date("Y-m-d"));
            $diff=date_diff($date1,$date2);
            $hari=$diff->format("%a");
            // echo $hari;

            if($hari > $data_user[0]->pem_max){
              echo 'Peminjaman melebihi batas waktu yang telah ditentukan';
            }else{
              $save=$qb->RAW("UPDATE peminjaman set status=1,pengembalian=? WHERE id_peminjaman=?",[date("Y-m-d H:i:s"),$id]);
              echo 'Pengembalian Berhasil';
            }
            
        }else{
            echo 'Jumlah dan jenis id buku tidak sesuai';
        }
    }else{
    echo 'Data peminjaman kosong';die();
    }


    // $sub=0;
    // if($_SESSION['role'] == 3){$sub=$_SESSION['sub_user'];}
    // $rekapAbsen = $qb->insert('peminjaman', [
    //       'peminjam' => $id,
    //       'buku' => $isi,
    //       'user' => $_SESSION['id_user'],
    //       'subuser' => $sub
    //     ]);
    // if($rekapAbsen){
    //   echo 'Buku Dengan Judul'.implode('',$buku).'Berhasil Dipinjam';
    // }else{
    //   echo 'Tidak berhasil, coba ulangi lagi';
    // }

  }else{
    echo 'RFID Siswa tidak terdaftar';die();
  }

  }
}
