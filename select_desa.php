<?php
require "vendor/autoload.php";

use StelinDB\Database\QueryBuilder;
use Carbon\Carbon;

$dotenv = new \Dotenv\Dotenv(__DIR__);
            $dotenv->load();
$now = new Carbon;
$now->setTimezone('Asia/Jakarta');

$qb = new QueryBuilder(\StelinDB\Database\Connection::Connect());


$kecamatan=$_POST['kecamatan'];

// $tampil = mysqli_query($koneksi, "SELECT * FROM desa where id_mwc='$kecamatan' ORDER BY nama_desa ");

$data= $qb->RAW("SELECT * FROM desa where id_kec='".$kecamatan."' ORDER BY nama_desa",[]);
// $data= $qb->RAW("SELECT * FROM desa ORDER BY nama_desa",[]);
// print_r($data);
foreach ($data as $data){

   $result[] = array(
  'id_desa' => $data->id_desa,
  'nama_desa' => $data->nama_desa
);
}
echo json_encode($result);

?>