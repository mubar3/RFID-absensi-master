<?php

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
    "SELECT nama, last_update, NOW()
    AS absen from siswa where norf = ?",
     [$id]);

    $HARI = [
       0 => "Minggu",
       1 => "Senin",
       2 => "Selasa",
       3 => "Rabu",
       4 => "Kamis",
       5 => "Jumat",
       6 => "Sabtu"
     ];
    $siswa_check= $qb->RAW(
            "SELECT * FROM rekap_absen where tanggal_absen >= DATE(NOW()) and norf=?",[$id]);
    // print_r($siswa_check[0]->norf);
    // die();

    if (array_key_exists(0, $siswa_check)) {

        $old_time = date('Y-m-d H:i',strtotime($siswa_check[0]->tanggal_absen));
        $new_time = date('Y-m-d H:i',strtotime('+1 hour +30 minutes',strtotime($siswa_check[0]->tanggal_absen)));
        date_default_timezone_set('Asia/Jakarta');
        $now_time = date("Y-m-d H:i");
        // if($now_time<$new_time){
        // print_r($now_time);
        // }
        // die();
        if($now_time<$new_time){echo "Kartu telah absen";die();}
    }
     //Jika variable siswa adalah array, dan indeks pertama ada(exist)
    if (array_key_exists(0, $siswa)) {
        $siswa = $siswa[0]; //Mengambil indeks pertama

        //parsing jam absen siswa ke dalam timezone asia/jakarta via Carbon
        //default Carbon timezone is Berlin
        $date = Carbon::parse($siswa->absen, 'Asia/Jakarta');

        $hari=$HARI[$date->dayOfWeek];

        $cariMakulabsen = $qb->RAW("SELECT * FROM jadwal where hari = ?", [$hari]);

        foreach ($cariMakulabsen as $index => $value) {
            $mulai = Carbon::parse($value->jam_mulai, 'Asia/Jakarta')->hour;
            $akhir = Carbon::parse($value->jam_akhir, 'Asia/Jakarta')->hour;

            //Mendapatkan jam sekarang
            $sekarang = Carbon::now('Asia/Jakarta')->hour ;

            if ($sekarang > $mulai && $sekarang < $akhir) { //10 > 8 && 10 < 12
                $makul = $value->makul;
                break;
            } else {
                // $makul = "Tidak ada kelas";
                // $makul = $value->makul;
            }
        }
        if(isset($makul)){
        //Yang akan ditampilkan
        $formatTampilan = "<b>Nama:</b> %s, <b>Jam Absen:</b> %s, %s";
        $rekapAbsen = $qb->insert('rekap_absen', [
          'id' => '',
          'norf' => $id,
          'makul_absen' => $makul
        ]);

        echo sprintf($formatTampilan, $siswa->nama, $date, $date->diffForHumans(), $makul);
        }else{echo "Tidak ada Kelas Hari Ini";}
        }else{echo "Kartu tidak terdaftar";}
    } else {
        echo "Kartu tidak terdaftar";
    }

