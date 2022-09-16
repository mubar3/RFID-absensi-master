<?php
session_start();
require "vendor/autoload.php";

use StelinDB\Database\QueryBuilder;
use Carbon\Carbon;

$dotenv = new \Dotenv\Dotenv(__DIR__);
            $dotenv->load();
$now = new Carbon;
$now->setTimezone('Asia/Jakarta');

$qb = new QueryBuilder(\StelinDB\Database\Connection::Connect());

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    // echo $id;
    // die();
    $siswa = $qb->RAW(
    "SELECT kelas.kelas as nama_kelas ,nama, last_update, NOW()
    AS absen,siswa.norf as norf from siswa 
    left join kelas on kelas.id_kelas=siswa.kelas
    where siswa.user_input=".$_SESSION['id_user']." and siswa.norf = ?",
     [$id]);
    // print_r($siswa[0]->nama_kelas);
    // die();
    if(!array_key_exists(0, $siswa)){echo "Siswa tidak terdaftar";die();}

    $siswa=$siswa[0];

        $simpan = $qb->insert('rekap_parkir', [
          'norf' => $siswa->norf
        ]);
        if($simpan){
            $formatTampilan = "<b>Nama:</b> %s, <b>Kelas:</b> %s";
            echo sprintf($formatTampilan, $siswa->nama, $siswa->nama_kelas);
        }else{
            echo "Gagal";   
        }
    }else{echo "Gagal";}
    

