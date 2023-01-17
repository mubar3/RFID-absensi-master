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

$jumlah=enkripsiDekripsi(strval($_POST['jumlah_dibayar']), $kunciRahasia);
$id=$_POST['id_dibayar'];

$data = $qb->RAW( "UPDATE pembelian SET dibayar=? where id =?",[$jumlah,$id]);

if($data){
  echo "<div class='p-3 mb-2 bg-success'>Perubahan sudah dibayar berhasil</div>";
}else{
  echo "<div class='p-3 mb-2 bg-danger'>Perubahan sudah dibayar gagal<div>";
}

  

