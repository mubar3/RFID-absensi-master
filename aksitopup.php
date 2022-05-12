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
    if(empty($isi)){$isi=0;}


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

    $isi2 = enkripsiDekripsi($isi2, $kunciRahasia);
    $isi2=convertToRupiah($isi2);
    // print_r($isi2);
    // die();
   
  
        $formatTampilan = "<b>Saldo:</b> %s";
      

        echo sprintf($formatTampilan, $isi2);
    } else {
      $isi2 = strval(enkripsiDekripsi($isi, $kunciRahasia));
      $tambah = $qb->insert('saldo_rfid', [
          'id_rfid' => $id,
          'saldo' => $isi2
        ]);
      $isi2 = strval(enkripsiDekripsi($isi2, $kunciRahasia));
      $formatTampilan = "<b>Saldo:</b> %s";
      

        echo sprintf($formatTampilan, $isi2);
    }
}
