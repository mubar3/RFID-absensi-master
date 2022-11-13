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
  // $_POST['id2']
  // $_POST['id_isi']
  // $_POST['id_isi']=str_replace("on", "", $_POST['id_isi']);
  // print_r($_POST['id_isi']);die();
  if(empty($_POST['id_isi'])){echo "<div class='p-3 mb-2 bg-danger'>Pembelian Kosong<div>"; die();}
    else{
      $data_menu=explode(',', $_POST['id_isi']);
      $total_menu=count($data_menu);
      $total=0;
            for($x=0;$x<$total_menu;$x++) {
              $data_harga_menu= $qb->RAW(
              "SELECT * FROM toko_menu where id=".$data_menu[$x],[]);
              $data_harga_menu=$data_harga_menu[0];
              // print_r($data_harga_menu[0]);die();
              $total=$total+$data_harga_menu->harga;
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
                  'subuser' => $subuser
                ]);
              $transaksi=$qb->pdo->lastInsertId();
        $jumlah=0;
        for($x=0;$x<$total_menu;$x++) {
              $data_harga_menu = $qb->RAW(
              "SELECT * FROM toko_menu where id=".$data_menu[$x],[]);
              $data_harga_menu=$data_harga_menu[0];
              $qb->insert('saldo_log', [
                  'id_rfid' => $_POST['id2'],
                  'banyak' => enkripsiDekripsi(strval($data_harga_menu->harga), $kunciRahasia),
                  'ket' => $data_harga_menu->nama,
                  'jenis' => 'keluar',
                  'user' => $_SESSION['id_user'],
                  'subuser' => $subuser,
                  'id_transaksi' => $transaksi
                ]);     
              $jumlah=$jumlah+$data_harga_menu->harga;
            }
        $qb->RAW("UPDATE t_toko set jumlah=? where id = ?",[enkripsiDekripsi(strval($jumlah), $kunciRahasia),$transaksi]);
        // input log

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
            where siswa.user_input=".$_SESSION['id_user']." and siswa.norf =".$_POST['id2'],
             []);
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
