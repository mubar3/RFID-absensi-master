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
    "select siswa.nama,siswa.nis,nilai.kkm,nilai.nilai,nilai.kelakuan
    from nilai 
    left join siswa on nilai.id_siswa=siswa.id
     where 
     nilai.id_pelajaran = ? and
     nilai.kelas = ? and
     nilai.id_ujian = ?",
     [$_GET['plj'],$_GET['kls'],$_GET['uji']]);

$kelas=$qb->RAW(
    "select * from kelas where id_kelas=?",
     [$_GET['kls']]);
$kelas=$kelas[0];
$kelas=$kelas->kelas;

$pelajaran=$qb->RAW(
    "select * from m_pelajaran where id=?",
     [$_GET['plj']]);
$pelajaran=$pelajaran[0];
$pelajaran=$pelajaran->nama;

$ujian=$qb->RAW(
    "select * from j_ujian where id=?",
     [$_GET['plj']]);
$ujian=$ujian[0];
$ujian=$ujian->nama;


// print_r($items);die();
$fileName = $kelas."-".$pelajaran."-".$ujian.".xls";

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