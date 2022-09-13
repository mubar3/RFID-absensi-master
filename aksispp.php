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

     $cek_bayar = $qb->RAW(
    "SELECT * from saldo_log where id_rfid =".$id." and ket='Bayar SPP' order by waktu desc",
     []);

     if (array_key_exists(0, $cek_bayar)) {

        $old_time = date('Y-m-d H:i',strtotime($cek_bayar[0]->waktu));
        $new_time = date('Y-m-d H:i',strtotime('+23 hour +30 minutes',strtotime($cek_bayar[0]->waktu)));
        date_default_timezone_set('Asia/Jakarta');
        $now_time = date("Y-m-d H:i");
        if($now_time<$new_time){echo "<div class='p-3 mb-2 bg-danger'>Kartu telah Bayar SPP (tunggu selama 1 hari lagi)</div>";die();}
      }


    $data = $qb->RAW(
    "SELECT * from saldo_rfid where id_rfid = ?",
     [$id]);

    $data_siswa = $qb->RAW(
    "SELECT * from siswa where norf = ?",
     [$id]);

    $data_siswa = $qb->RAW(
    "SELECT * from siswa where norf=".$id." and user_input=".$_SESSION['id_user'],
     []);
    if (!array_key_exists(0, $data_siswa)) {echo "<div class='p-3 mb-2 bg-danger'>Siswa tidak terdaftar</div>";die();}
    $data_siswa=$data_siswa[0];
    
    $spp = $qb->RAW(
    "SELECT * from kelas where id_kelas=".$data_siswa->kelas,
     []);
    $spp=$spp[0];

    $isi = $spp->spp;
    // echo "<div class='p-3 mb-2 bg-danger'>".$isi."<div>";die();
    $keperluan = 'Bayar SPP';
    $merchan=$isi;

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
    $qb->insert('saldo_log', [
        'id_rfid' => $id,
        'banyak' => enkripsiDekripsi(strval($merchan), $kunciRahasia),
        'ket' => $keperluan,
        'jenis' => 'keluar',
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
