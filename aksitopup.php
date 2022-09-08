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

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $isi = $_POST['isi'];
    $total = $isi;
    if(empty($isi)){$isi=0;}
    if($isi > 1000000){echo "<div class='p-3 mb-2 bg-danger'>Isi Saldo Maksimum Rp1.000.000<div>"; die();}

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
    $isi=strval($saldo+$isi);
    $isi2 = strval(enkripsiDekripsi($isi, $kunciRahasia));


    $data = $qb->RAW(
    "UPDATE saldo_rfid SET saldo='".$isi2."'where id_rfid = ?",
     [$id]);

    // input log
    if(!empty($total)){
    $qb->insert('saldo_log', [
        'id_rfid' => $id,
        'banyak' => enkripsiDekripsi(strval($total), $kunciRahasia),
        'jenis' => 'masuk',
        'user' => $_SESSION['id_user']
      ]);     
    }
    // input log

    $isi2 = enkripsiDekripsi($isi2, $kunciRahasia);
    $isi2=convertToRupiah($isi2);
    // print_r($isi2);
    // die();
   
  
        $siswa = $qb->RAW(
        "SELECT kelas.kelas as nama_kelas ,nama, last_update, NOW()
        AS absen from siswa 
        join kelas on kelas.id_kelas=siswa.kelas
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
    } else {
      $isi2 = strval(enkripsiDekripsi($isi, $kunciRahasia));
      $tambah = $qb->insert('saldo_rfid', [
          'id_rfid' => $id,
          'saldo' => $isi2
        ]);
      $isi2 = strval(enkripsiDekripsi($isi2, $kunciRahasia));

       // input log
        if(!empty($total)){
        $qb->insert('saldo_log', [
            'id_rfid' => $id,
            'banyak' => enkripsiDekripsi(strval($total), $kunciRahasia),
            'jenis' => 'masuk',
            'user' => $_SESSION['id_user']
          ]);     
        }
        // input log

      $siswa = $qb->RAW(
        "SELECT kelas.kelas as nama_kelas ,nama, last_update, NOW()
        AS absen from siswa 
        join kelas on kelas.id_kelas=siswa.kelas
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
    }
}
