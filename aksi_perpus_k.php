<?php
require "partials/head.php";
require "vendor/autoload.php";
require "partial/head.php";
use StelinDB\Database\QueryBuilder;
use Carbon\Carbon;

$dotenv = new \Dotenv\Dotenv(__DIR__);
            $dotenv->load();
$now = new Carbon;
$now->setTimezone('Asia/Jakarta');

$qb = new QueryBuilder(\StelinDB\Database\Connection::Connect());

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $user = $qb->RAW(
    "SELECT * FROM siswa WHERE norf=? and user_input=?",[$id,$_SESSION['id_user']]);
    // echo "SELECT * FROM siswa WHERE norf=".$id." and user_input=".$_SESSION['id_user'];
    // die();
  if (array_key_exists(0, $user)) {
    $sub=0;
    if($_SESSION['role'] == 3){$sub=$_SESSION['sub_user'];}
    $rekapAbsen = $qb->insert('kunjungan', [
          'siswa' => $id,
          'user' => $_SESSION['id_user'],
          'subuser' => $sub
        ]);
    if($rekapAbsen){
      echo 'Berhasil';
    }else{
      echo 'Tidak berhasil, coba ulangi lagi';
    }
  }else{
    echo 'RFID Siswa tidak terdaftar';die();
  }

  
}
