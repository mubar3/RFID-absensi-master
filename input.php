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
    AS absen from siswa 
    left join kelas on kelas.id_kelas=siswa.kelas
    where siswa.user_input=".$_SESSION['id_user']." and siswa.norf = ?",
     [$id]);
    // print_r($siswa[0]->nama_kelas);
    // die();
    if(!array_key_exists(0, $siswa)){echo "Siswa tidak terdaftar";die();}

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
            "SELECT * FROM rekap_absen where date(tanggal_absen) = DATE(NOW()) and norf=?",[$id]);

        
    // print_r($siswa_check[0]->norf);
    // die();

    if (array_key_exists(0, $siswa_check)) {

        if($siswa_check[0]->ket == ''){
            $old_time = date('Y-m-d H:i',strtotime($siswa_check[0]->tanggal_absen));
            $new_time = date('Y-m-d H:i',strtotime('+1 hour +30 minutes',strtotime($siswa_check[0]->tanggal_absen)));
            date_default_timezone_set('Asia/Jakarta');
            $now_time = date("Y-m-d H:i");
            if($now_time<$new_time){echo "Kartu telah absen";die();}
        }
    }
    if (array_key_exists(1, $siswa_check)) {
        echo "Kartu telah absen pulang";die();
    }
    $status=0;
    if (array_key_exists(0, $siswa)) {

        $date = Carbon::parse($siswa[0]->absen, 'Asia/Jakarta');

        $hari=$HARI[$date->dayOfWeek];

        $cariMakulabsen = $qb->RAW("SELECT * FROM jadwal where id_user=? and hari = ?", [$_SESSION['id_user'],$hari]);
        // $makul='';
        if (!array_key_exists(0, $cariMakulabsen)) {echo "Tidak ada Kelas Hari Ini";die();}
        foreach ($cariMakulabsen as $index => $value) {
            $sekarang = Carbon::now('Asia/Jakarta');

            $mulai = Carbon::parse($value->jam_mulai, 'Asia/Jakarta');
            // $mulai_add = Carbon::parse($value->jam_mulai, 'Asia/Jakarta')->addHour();
            $mulai_sub = Carbon::parse($value->jam_mulai, 'Asia/Jakarta')->subHour();
            
            $akhir = Carbon::parse($value->jam_akhir, 'Asia/Jakarta');
            $akhir_add = Carbon::parse($value->jam_akhir, 'Asia/Jakarta')->addHour();
            // $akhir_sub = Carbon::parse($value->jam_akhir, 'Asia/Jakarta')->subHour();
            
        if(!isset($_POST['izin'])){
            if (!array_key_exists(0, $siswa_check)) {    
                if($mulai_sub < $sekarang && $sekarang < $mulai){
                    $status=1;
                }else{
                    echo "Harap absen sesuai jam masuk";die();
                }
            }
            if($akhir < $sekarang){
                if($akhir < $sekarang && $sekarang < $akhir_add){
                    $status=1;
                }else{
                    echo "Harap absen sesuai jam pulang";die();
                }
            }
          }else{
            $status=1;
          }

        }
        
    }
     //Jika variable siswa adalah array, dan indeks pertama ada(exist)
    if ($status == 1) {
        $siswa = $siswa[0]; //Mengambil indeks pertama

        //parsing jam absen siswa ke dalam timezone asia/jakarta via Carbon
        //default Carbon timezone is Berlin
        // $date = Carbon::parse($siswa->absen, 'Asia/Jakarta');

        // $hari=$HARI[$date->dayOfWeek];

        // $cariMakulabsen = $qb->RAW("SELECT * FROM jadwal where id_user=".$_SESSION['id_user']." and hari = ?", [$hari]);

        // foreach ($cariMakulabsen as $index => $value) {
            
            // $mulai = Carbon::parse($value->jam_mulai, 'Asia/Jakarta')->hour;
            // $akhir = Carbon::parse($value->jam_akhir, 'Asia/Jakarta')->hour;

            // //Mendapatkan jam sekarang
            // $sekarang = Carbon::now('Asia/Jakarta')->hour ;

            // if ($sekarang > $mulai && $sekarang < $akhir) { //10 > 8 && 10 < 12
            //     $makul = $value->makul;
            //     break;
            // } else {
            //     // $makul = "Tidak ada kelas";
            //     // $makul = $value->makul;
            // }
        // }
        // if(isset($makul)){
        //Yang akan ditampilkan
        $formatTampilan = "<b>Nama:</b> %s, <b>Jam Absen:</b> %s, <b>Kelas:</b> %s";
        $ket='';
        $telat='';
        if(isset($_POST['izin'])){
            $ket=$_POST['izin'];
            $telat=$_POST['telat'];
        }
        $rekapAbsen = $qb->insert('rekap_absen', [
        //   'id' => '',
          'norf' => $id,
        //   'makul_absen' => '',
          'ket' => $ket,
          'telat' => $telat
        ]);

        echo sprintf($formatTampilan, $siswa->nama, $date, 
            // $date->diffForHumans(),
             $siswa->nama_kelas);
        // }else{echo "Tidak ada Kelas Hari Ini";}
        }else{echo "Gagal";}
    } else {
        echo "Gagal";
    }

