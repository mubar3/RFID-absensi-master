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

$data = $qb->RAW( "UPDATE pembelian SET waktu=? where id =?",[$_POST['tgl_beli_ajax'],$_POST['id_tgl_beli_ajax']]);

if($data){
  echo "<div class='p-3 mb-2 bg-success'>Perubahan sudah tanggal beli berhasil</div>";
}else{
  echo "<div class='p-3 mb-2 bg-danger'>Perubahan sudah tanggal beli gagal<div>";
}

  

