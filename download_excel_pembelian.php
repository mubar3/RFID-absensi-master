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
$fileName = "Excel-Pembelian-".date('d-m-Y').".xls";

//Set header information to export data in excel format
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename='.$fileName);

$heading = false;

$id_user="user='".$_SESSION['id_user']."'";
if($_SESSION['role'] == 3){$id_user="subuser='".$_SESSION['sub_user']."'";}
$saldo_log = $qb->RAW("select id,jumlah as tagihan, dibayar as terbayar, date(waktu) as tgl_pembelian, pembayaran as tgl_pembayaran, b_lainnya as biaya_lainnya, b_ket as keterangan from pembelian
							where ".$id_user." and DATE(waktu) between '".$_GET['awal']."' and '".$_GET['akhir']."'",[]);

$total=0;
$total_terbayar=0;
$total_b_lainnya=0;
foreach ($saldo_log as $value) {
  $value->tagihan=enkripsiDekripsi($value->tagihan, $kunciRahasia);
  $value->terbayar=enkripsiDekripsi($value->terbayar, $kunciRahasia);
  $value->biaya_lainnya=enkripsiDekripsi($value->biaya_lainnya, $kunciRahasia);
  $total_terbayar=$total_terbayar+$value->terbayar;
  $total=$total+$value->tagihan;
  $total_b_lainnya=$total_b_lainnya+$value->biaya_lainnya;

}
// echo '<pre>';print_r($saldo_log);echo '</pre>';die();

// $saldo_log[]=(object)[
//   'TOTAL3' =>$total,
//   'TOTAL2' =>$total_terbayar,
//   'TOTAL1' =>'TOTAL',
// ];            
//Add the MySQL table data to excel file
foreach($saldo_log as $item) {
  $items=(array)$item;
  unset($items['id']);
  // print_r((array)$item);die();
  if(!$heading) {
    // echo implode("\t", array_keys($items)) . "\r\n";
    echo implode("\t", array_keys($items));
    echo  "\t Barang";
    echo  "\t Banyak";
    echo  "\r\n";
    $heading = true;
  }

  $detail = $qb->RAW("select 
								pembelian_detail.*, 
								toko_menu.nama as nama_barang,
								satuan.nama as satuan_konversi,
								s2.nama as satuan_asli
							from pembelian_detail
							join toko_menu on toko_menu.id = pembelian_detail.barang
							join satuan on satuan.id = pembelian_detail.satuan
							join satuan s2 on s2.id = toko_menu.satuan
							where pembelian_detail.id_pembelian = ? ",[$item->id]);
              // echo '<pre>';print_r($detail);echo '</pre>';die();
    echo implode("\t", array_values($items));
    $i=1;
    foreach ($detail as $key) {
      if($i!=1){
        echo  "\t";
        echo  "\t";
        echo  "\t";
        echo  "\t";
        echo  "\t";
      }
      echo  "\t".$key->nama_barang;
      echo  "\t".$key->jumlah.' '.$key->satuan_asli;
      echo  "\r\n";
      $i++;
    }
    $heading = true;
  }
  echo  $total;
  echo  "\t".$total_terbayar;
  echo  "\t";
  echo  "\t";
  echo  "\t".$total_b_lainnya;
  echo  "\t";
  echo  "\t";
  echo  "\t TOTAL";
exit();
        
?>