<?php

session_start();

require "vendor/autoload.php";
require "partial/head.php";
use StelinDB\Database\QueryBuilder;
use Carbon\Carbon;

$dotenv = new \Dotenv\Dotenv(__DIR__);
            $dotenv->load();
$now = new Carbon;
$now->setTimezone('Asia/Jakarta');

$qb = new QueryBuilder(\StelinDB\Database\Connection::Connect());
// $data=$_POST['id'];
// if($data == ''){
// echo "<div class='p-3 mb-2 bg-danger'>kosong<div>"; die();

// }else{
// echo "<div class='p-3 mb-2 bg-danger'>tidak<div>"; die();

// }
if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $isi = $_POST['isi'];
    $keperluan = $_POST['keperluan'];
    $merchan=$isi;


    $data = $qb->RAW(
    "SELECT * from siswa where norf = ?",
     [$id]);

    if (!array_key_exists(0, $data)) {echo "<div class='p-3 mb-2 bg-danger'>Kartu Tidak Terdaftar<div>";die();}

    $data = $qb->RAW(
    "SELECT * from saldo_rfid where id_rfid = ?",
     [$id]);

    if (array_key_exists(0, $data)) {

    $data = $data[0];
    $saldo=enkripsiDekripsi($data->saldo, $kunciRahasia);
    $saldo=(int)$saldo;
    $isi=(int)$isi;
    $isi=$saldo-$isi;

    if($isi>=0){
    $isi=strval($isi);
    $isi2 = strval(enkripsiDekripsi($isi, $kunciRahasia));


    $data = $qb->RAW(
    "UPDATE saldo_rfid SET saldo='".$isi2."'where id_rfid = ?",
     [$id]);

    // input log
    if(!empty($merchan)){
      $subuser='';
      if($_SESSION['role'] == 3){$subuser=$_SESSION['sub_user'];}
    $transaksi = $qb->insert('t_toko', [
        'rfid' => $id,
        'user' => $_SESSION['id_user'],
        'subuser' => $subuser,
        'jumlah' => enkripsiDekripsi(strval($merchan), $kunciRahasia)
      ]);
    $transaksi=$qb->pdo->lastInsertId();
    $qb->insert('saldo_log', [
        'id_rfid' => $id,
        'banyak' => enkripsiDekripsi(strval($merchan), $kunciRahasia),
        'ket' => $keperluan,
        'jenis' => 'keluar',
        'user' => $_SESSION['id_user'],
        'subuser' => $subuser,
        'id_transaksi' => $transaksi
      ]);     
    }
    // input log

    //topup merhcan
    $id_user= $_SESSION['id_user'];
    $data2 = $qb->RAW(
    "SELECT * from user where id_user = ?",
     [$id_user]);

    $data2 = $data2[0];
    $saldo_user=enkripsiDekripsi($data2->saldo, $kunciRahasia);
    $saldo_user=(int)$saldo_user;
    $merchan=(int)$merchan;
    $merchan=strval($saldo_user+$merchan);

    $merchan=enkripsiDekripsi($merchan, $kunciRahasia);
    $data2 = $qb->RAW(
    "UPDATE user SET saldo='".$merchan."'where id_user = ?",
     [$id_user]);
    //topup merhcan

    $isi2 = enkripsiDekripsi($isi2, $kunciRahasia);
    $isi2=convertToRupiah($isi2);
    // print_r($isi2);
    // die();
   
    $siswa = $qb->RAW(
        "SELECT kelas.kelas as nama_kelas ,nama, last_update, NOW()
        AS absen from siswa 
        left join kelas on kelas.id_kelas=siswa.kelas
        where siswa.user_input=".$_SESSION['id_user']." and siswa.norf = ?",
         [$id]);
        $nama='';
        $kelas='';
        if(array_key_exists(0, $siswa)){
          $nama=$siswa[0]->nama;
          $kelas=$siswa[0]->nama_kelas;
        }
  
        $formatTampilan = "<div class='p-3 mb-2 bg-success'><b>Nama:</b> %s, <b>Kelas:</b> %s, <b>Saldo:</b> %s</div>";
      

        echo sprintf($formatTampilan, $nama, $kelas, $isi2);
      }else{
        echo "<div class='p-3 mb-2 bg-danger'>Saldo Kurang<div>";
      }
    } else {
      echo "<div class='p-3 mb-2 bg-danger'>Saldo Kurang<div>";
    }
}
else {
  if(empty($_POST['barang'])){echo "<div class='p-3 mb-2 bg-danger'>Pembelian Kosong<div>"; die();}
    else{
      $jumlah=$_POST['jumlah'];
      $satuan=$_POST['satuan'];
      $harga=$_POST['harga'];
      $barang=$_POST['barang'];
      $stok=array();
        // CARI TOTAL
        $total=0;
        for ($i=0; $i <  count($barang); $i++) { 
          $total=$total+($harga[$i]*$jumlah[$i]);
        }

        $data = $qb->RAW(
        "SELECT * from siswa where norf = ?",
         [$_POST['id2']]);

        if (!array_key_exists(0, $data)) {echo "<div class='p-3 mb-2 bg-danger'>Kartu Tidak Terdaftar<div>";die();}

         $data = $qb->RAW(
          "SELECT * from saldo_rfid where id_rfid =?",
           [$_POST['id2']]);
          if (!array_key_exists(0, $data)) {echo "<div class='p-3 mb-2 bg-danger'>Saldo Kartu Kosong<div>";die();}
        $data = $data[0];
        $saldo=enkripsiDekripsi($data->saldo, $kunciRahasia);
        $saldo=(int)$saldo;
        $isi=$saldo-$total;

        if($isi < 0){echo "<div class='p-3 mb-2 bg-danger'>Saldo Kurang<div>"; die();}
        
        // CEK BARANG DAN STOK
        for ($i=0; $i <  count($barang); $i++) { 
          $data = $qb->RAW("SELECT * from toko_menu where id =?",[$barang[$i]]);
          if (!array_key_exists(0, $data)) {echo "<div class='p-3 mb-2 bg-danger'>Barang tidak tersedia<div>";die();}
          $data = $data[0];
          if($data->satuan == $satuan[$i] && $data->satuan != ''){
            if($jumlah[$i] > $data->stok){
              echo "<div class='p-3 mb-2 bg-danger'>Stok ".$data->nama." kurang<div>";die();
            }
            array_push($stok,$jumlah[$i]);
          }else{
            $data2 = $qb->RAW("SELECT * from konversi where konversi =?",[$satuan[$i]]);
            if (!array_key_exists(0, $data2)) {echo "<div class='p-3 mb-2 bg-danger'>Satuan tidak valid<div>";die();}
            $data2 = $data2[0];
            $jumlah_p=$jumlah[$i]/$data2->nilai;
            array_push($stok,$jumlah_p);
            if($jumlah_p > $data->stok){
              echo "<div class='p-3 mb-2 bg-danger'>Stok ".$data->nama." kurang<div>";die();
            }        

          }
        
        }
        
        // KURANGI STOK
        for ($i=0; $i <  count($barang); $i++) { 
          $data = $qb->RAW("SELECT * from toko_menu where id =?",[$barang[$i]]);
          $data = $data[0];
          $stok_akhir=$data->stok - $stok[$i];
          $data = $qb->RAW( "UPDATE toko_menu SET stok=? where id =?",[$stok_akhir,$barang[$i]]);
        }

        $isi=strval($isi);
        $isi2 = strval(enkripsiDekripsi($isi, $kunciRahasia));


        $data = $qb->RAW(
        "UPDATE saldo_rfid SET saldo='".$isi2."'where id_rfid =?",
         [$_POST['id2']]);
        
        // input log
                $subuser='';
                if($_SESSION['role'] == 3){$subuser=$_SESSION['sub_user'];}
              $transaksi = $qb->insert('t_toko', [
                  'rfid' => $_POST['id2'],
                  'user' => $_SESSION['id_user'],
                  'jumlah' => enkripsiDekripsi(strval($total), $kunciRahasia),
                  'subuser' => $subuser
                ]);
              $transaksi=$qb->pdo->lastInsertId();

            for ($i=0; $i <  count($barang); $i++) { 
              $data_harga_menu = $qb->RAW(
              "SELECT * FROM toko_menu where id=?",[$barang[$i]]);
              $data_harga_menu=$data_harga_menu[0];
              for ($x=0; $x < $jumlah[$i]; $x++) { 
                $qb->insert('saldo_log', [
                    'id_rfid' => $_POST['id2'],
                    'banyak' => enkripsiDekripsi(strval($harga[$i]), $kunciRahasia),
                    'ket' => $data_harga_menu->nama,
                    'jenis' => 'keluar',
                    'user' => $_SESSION['id_user'],
                    'subuser' => $subuser,
                    'id_transaksi' => $transaksi,
                    'satuan' => $satuan[$i]
                  ]);     
              }
            }

        $merchan=$total;
        //topup merhcan
        $id_user= $_SESSION['id_user'];
        $data2 = $qb->RAW(
        "SELECT * from user where id_user = ?",
         [$id_user]);

        $data2 = $data2[0];
        $saldo_user=enkripsiDekripsi($data2->saldo, $kunciRahasia);
        $saldo_user=(int)$saldo_user;
        $merchan=(int)$merchan;
        $merchan=strval($saldo_user+$merchan);

        $merchan=enkripsiDekripsi($merchan, $kunciRahasia);
        $data2 = $qb->RAW(
        "UPDATE user SET saldo='".$merchan."'where id_user = ?",
         [$id_user]);
        //topup merhcan

        $isi2 = enkripsiDekripsi($isi2, $kunciRahasia);
        $isi2=convertToRupiah($isi2);
        // print_r($isi2);
        // die();
       
        $siswa = $qb->RAW(
            "SELECT kelas.kelas as nama_kelas ,nama, last_update, NOW()
            AS absen from siswa 
            left join kelas on kelas.id_kelas=siswa.kelas
            where siswa.user_input= ? and siswa.norf = ?",
             [$_SESSION['id_user'],$_POST['id2']]);
            $nama='';
            $kelas='';
            if(array_key_exists(0, $siswa)){
              $nama=$siswa[0]->nama;
              $kelas=$siswa[0]->nama_kelas;
            }
      
            $formatTampilan = "<div class='p-3 mb-2 bg-success'><b>Nama:</b> %s, <b>Kelas:</b> %s, <b>Saldo:</b> %s</div>";
          

            echo sprintf($formatTampilan, $nama, $kelas, $isi2);
        }
  }
