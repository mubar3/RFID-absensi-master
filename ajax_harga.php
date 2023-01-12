<?php
require "vendor/autoload.php";
session_start();

use StelinDB\Database\QueryBuilder;
use Carbon\Carbon;

$dotenv = new \Dotenv\Dotenv(__DIR__);
            $dotenv->load();
$now = new Carbon;
$now->setTimezone('Asia/Jakarta');

$qb = new QueryBuilder(\StelinDB\Database\Connection::Connect());

$data_konversi = $qb->RAW("SELECT * from konversi where barang=? and konversi=?",[$_GET['barang'],$_GET['id']]);
$result=[];
if (array_key_exists(0, $data_konversi)) {
    $data_konversi=$data_konversi[0];
    
    $result[] = array(
        'harga' => $data_konversi->harga,
    );
}else{
    $data_konversi = $qb->RAW("SELECT * from toko_menu where id=? and satuan=?",[$_GET['barang'],$_GET['id']]);
    $data_konversi2=$data_konversi[0];
    // if (array_key_exists(0, $data_konversi2)) {
        $result[] = array(
            'harga' => $data_konversi2->harga,
        );
    // }
}
echo json_encode($result);
?>