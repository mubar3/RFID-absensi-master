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

    $data = $qb->RAW(
    "SELECT * from saldo_rfid where id_rfid = ?",
     [$id]);


   
    if (array_key_exists(0, $data)) {
    $data = $data[0];
        $isi_saldo=enkripsiDekripsi($data->saldo, $kunciRahasia);
        $isi_saldo=convertToRupiah($isi_saldo);
        //Yang akan ditampilkan
        $formatTampilan = "<b>Saldo:</b> %s";
    // print_r($isi_saldo);
    // die();
      

        echo sprintf($formatTampilan, $isi_saldo);
    } else {
        echo "err";
    }
}
