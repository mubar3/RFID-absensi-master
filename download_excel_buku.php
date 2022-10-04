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

// print_r($items);die();
$fileName = "RFID-".$judul[0]->judul_buku."-".date('d-m-Y').".xls";

//Set header information to export data in excel format
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename='.$fileName);

$heading = false;

//Add the MySQL table data to excel file
foreach($items as $item) {
  $item=(array)$item;
  // print_r((array)$item);die();
  if(!$heading) {
    echo implode("\t", array_keys($item)) . "\r\n";
    $heading = true;
  }
  echo implode("\t", array_values($item)) . "\r\n";
  $heading = true;
}
exit();
        
?>