<?php

require "partials/head.php";
require "asset/phpqrcode/qrlib.php";
require "vendor/autoload.php";
require "partial/head.php";
use StelinDB\Database\QueryBuilder;
use Carbon\Carbon;

$dotenv = new \Dotenv\Dotenv(__DIR__);
            $dotenv->load();
$now = new Carbon;
$now->setTimezone('Asia/Jakarta');

$qb = new QueryBuilder(\StelinDB\Database\Connection::Connect());

$data_siswa = $qb->RAW(
            "SELECT * FROM siswa",[]);
            foreach ($data_siswa as $siswa) {

                $nameqrcode    = $siswa->nisn.'.png';              
                $tempdir        = "asset/qrcode/"; 
                $isiqrcode     = $server."data?id=".$siswa->nisn;
                $quality        = 'H';
                $Ukuran         = 10;
                $padding        = 0;

                QRCode::png($isiqrcode,$tempdir.$nameqrcode,$quality,$Ukuran,$padding);
                
                }
echo 'done';


?>