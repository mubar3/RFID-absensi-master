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

$data_menu = $qb->RAW("SELECT toko_menu.*, satuan.nama as nama_satuan FROM toko_menu 
    left join satuan on satuan.id=toko_menu.satuan
    where toko_menu.id_user=? and (toko_menu.id = ? or toko_menu.qr = ?)",[$_SESSION['id_user'],$_GET['id'],$_GET['id']]);
$result=[];
if (array_key_exists(0, $data_menu)) {
    $data_menu=$data_menu[0];

    $data_konversi = $qb->RAW("SELECT konversi.*,satuan.nama as nama_satuan FROM konversi
        join satuan on satuan.id=konversi.konversi
        where konversi.barang=?",[$data_menu->id]);
    // $data=$data_menu->nama.','.$data_menu->id;
    
    $result[] = array(
        'nama' => $data_menu->nama,
        'id' => $data_menu->id,
        'satuan' => $data_menu->satuan,
        'nama_satuan' => $data_menu->nama_satuan,
        'harga' => $data_menu->harga,
        'stok' => $data_menu->stok,
        'konversi' => $data_konversi,
    );
}
echo json_encode($result);
?>