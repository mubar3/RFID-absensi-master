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
    $merchan=$isi;


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
   
  
        $formatTampilan = "<div class='p-3 mb-2 bg-success'><b>Saldo:</b> %s</div>";
      

        echo sprintf($formatTampilan, $isi2);
      }else{
        echo "<div class='p-3 mb-2 bg-danger'>Saldo Kurang<div>";
      }
    } else {
      echo "<div class='p-3 mb-2 bg-danger'>Saldo Kurang<div>";
    }
}
