<?php
// print_r('a');die();
session_start();

require "vendor/autoload.php";
// require "partial/head.php";
use StelinDB\Database\QueryBuilder;
use Carbon\Carbon;

$dotenv = new \Dotenv\Dotenv(__DIR__);
            $dotenv->load();
$now = new Carbon;
$now->setTimezone('Asia/Jakarta');

$qb = new QueryBuilder(\StelinDB\Database\Connection::Connect());

$items = $qb->RAW(
    "SELECT
        siswa.nama,  
        concat('`',siswa.nim) as nik,  
        siswa.nis as nis,  
        siswa.nisn,  
        kelas.kelas,  
        tb_jk.jk,  
        provinsi.nama_provinsi,  
        kabupaten.nama_kabupaten,  
        kecamatan.nama_kecamatan,  
        desa.nama_desa,  
        siswa.alamat,  
        siswa.umur,  
        concat(siswa.tmp_lhr,',',tgl_lhr) as TTL,  
        tb_agama.agama,  
        siswa.s_asal as sekolah_asal,  
        siswa.anak_ke,  
        siswa.j_saudara as jumlah_saudara,  
        siswa.cita_cita,  
        siswa.no_hp,  
        siswa.email,  
        siswa.hobi,  
        siswa.no_kk,  
        siswa.nama_kk as nama_kepala_keluarga,  
        siswa.nm_ayah as nama_ayah,  
        concat('`',siswa.nik_ayah) as nik_ayah,  
        concat(siswa.tmp_ayah,',',siswa.tgl_ayah) as TTL_Ayah,  
        (select pendidikan from tb_pendidikan where id=siswa.pend_ayah) as pendidikan_ayah,  
        (select pekerjaan from tb_pekerjaan where id=siswa.kerj_ayah) as pekerjaan_ayah,  
        siswa.no_ayah,   
        siswa.nm_ibu as nama_ibu,  
        concat('`',siswa.nik_ibu) as nik_ibu,  
        concat(siswa.tmp_ibu,',',siswa.tgl_ibu) as TTL_ibu,  
        (select pendidikan from tb_pendidikan where id=siswa.pend_ibu) as pendidikan_ibu,  
        (select pekerjaan from tb_pekerjaan where id=siswa.kerj_ibu) as pekerjaan_ibu,  
        siswa.no_ibu
    from siswa
    left join kelas on kelas.id_kelas=siswa.kelas 
    left join tb_jk on tb_jk.id=siswa.jk 
    left join tb_agama on tb_agama.id=siswa.agama 
    left join provinsi on provinsi.id_prov=siswa.provinsi 
    left join kabupaten on kabupaten.id_kab=siswa.kabupaten 
    left join kecamatan on kecamatan.id_kec=siswa.kecamatan 
    left join desa on desa.id=siswa.desa 
    where user_input = ?",
     [$_SESSION['id_user']]);

// print_r($items);die();
$fileName = "siswa_".$_SESSION['nama_user']."-".date('d-m-Y').".xls";

//Set header information to export data in excel format
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename='.$fileName);

$heading = false;

//Add the MySQL table data to excel file
foreach($items as $item) {
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