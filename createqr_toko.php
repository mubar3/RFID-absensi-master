<?php 
require "asset/phpqrcode/qrlib.php"; 

require "vendor/autoload.php";

use StelinDB\Database\QueryBuilder;
use Carbon\Carbon;

$dotenv = new \Dotenv\Dotenv(__DIR__);
            $dotenv->load();
$now = new Carbon;
$now->setTimezone('Asia/Jakarta');

$qb = new QueryBuilder(\StelinDB\Database\Connection::Connect());

$data_menu = $qb->RAW("SELECT * FROM toko_menu",[]);

foreach ($data_menu as $value) {
    $nameqrcode    = $value->id.'.png';              
    $tempdir        = "asset/qrcode_toko/"; 
    $isiqrcode     = $value->id;
    $quality        = 'H';
    $Ukuran         = 10;
    $padding        = 0;
    
    QRCode::png($isiqrcode,$tempdir.$nameqrcode,$quality,$Ukuran,$padding);
}
echo "sukses";
?>