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

$data_menu = $qb->RAW("SELECT * FROM toko_menu where id_user=? and (id = ? or qr = ?)",[$_SESSION['id_user'],$_GET['id'],$_GET['id']]);

if (array_key_exists(0, $data_menu)) {
    $data_menu=$data_menu[0];
    echo $data_menu->nama.','.$data_menu->id;
}else{
echo '';
}
?>