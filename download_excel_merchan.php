<?php
// print_r('a');die();
session_start();

require "vendor/autoload.php";
require "partial/fun.php";
use StelinDB\Database\QueryBuilder;
use Carbon\Carbon;

$dotenv = new \Dotenv\Dotenv(__DIR__);
            $dotenv->load();
$now = new Carbon;
$now->setTimezone('Asia/Jakarta');

$qb = new QueryBuilder(\StelinDB\Database\Connection::Connect());


// print_r($items);die();
$fileName = "Excel-Penjualan-".date('d-m-Y').".xls";

//Set header information to export data in excel format
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename='.$fileName);

$heading = false;

$id_user="user='".$_SESSION['id_user']."'";
if($_SESSION['role'] == 3){$id_user="subuser='".$_SESSION['sub_user']."'";}
$saldo_log =  $qb->RAW("select 
  t_toko.jumlah,
  siswa.nama as pembeli,
  t_toko.waktu as waktu_penjualan
from t_toko
left join siswa on siswa.norf=t_toko.rfid
where t_toko.".$id_user." and DATE(t_toko.waktu) between '".$_GET['awal']."' and '".$_GET['akhir']."'",[]);

$total=0;
foreach ($saldo_log as $value) {
  $value->jumlah=enkripsiDekripsi($value->jumlah, $kunciRahasia);
  $total=$total+$value->jumlah;

}

$saldo_log[]=(object)[
  'TOTAL3' =>$total,
  'TOTAL2' =>'',
  'TOTAL1' =>'TOTAL',
];            
//Add the MySQL table data to excel file
foreach($saldo_log as $item) {
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