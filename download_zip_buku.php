<?php
// print_r('a');die();
session_start();

require "vendor/autoload.php";
// require "partial/head.php";
use StelinDB\Database\QueryBuilder;
use Carbon\Carbon;

$dotenv = new \Dotenv\Dotenv(__DIR__);
            $dotenv->load();
$now = new Carbon;
$now->setTimezone('Asia/Jakarta');

$qb = new QueryBuilder(\StelinDB\Database\Connection::Connect());

$items = $qb->RAW(
    "SELECT rfid from buku where master=?",
     [$_GET['id']]);
$judul = $qb->RAW(
    "SELECT judul_buku from buku where master=?",
     [$_GET['id']]);

$zipname = "RFID-".$judul[0]->judul_buku."-".date('d-m-Y').".zip";
header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename='.$zipname);

$zip = new ZipArchive;
$zip->open($zipname, ZipArchive::CREATE);

//Add the MySQL table data to excel file
foreach($items as $item) {
  // $zip->addFile($item->rfid);
  $zip->addFile('asset/qrcode_buku/'.$item->rfid.'.png',basename($item->rfid.'.png'));
}
$zip->close();

// print_r($zipname);die();
if(file_exists($zipname)){
// header('Content-disposition: attachment; 
// filename="sdawda"');
header('Content-Length: ' . filesize($zipname));
readfile($zipname);
unlink($zipname);
 
} else{
$error = "Proses mengkompresi file gagal  ";
}
        
?>