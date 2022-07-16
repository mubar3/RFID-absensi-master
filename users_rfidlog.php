<?php 
require "partials/head.php";
require "partials/sidebar.php"; ?>
<div class="container-fluid">
<?php

require "vendor/autoload.php";

use StelinDB\Database\QueryBuilder;
use Carbon\Carbon;

$dotenv = new \Dotenv\Dotenv(__DIR__);
            $dotenv->load();
$now = new Carbon;
$now->setTimezone('Asia/Jakarta');

$qb = new QueryBuilder(\StelinDB\Database\Connection::Connect());

    $data_user = $qb->RAW(
    "SELECT * FROM saldo_log where id_rfid=?",[$_GET['norf']]);
    $data_siswa = $qb->RAW(
    "SELECT *,kelas.kelas as nama_kelas FROM siswa 
    join kelas on kelas.id_kelas=siswa.kelas
    where siswa.norf=?",[$_GET['norf']]);
     // print_r($data_siswa);
     // die();
    require "partial/head.php";
    ?>


    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Daftar Penggunaan</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
        	<div class="card" style="width: 18rem;">
			<div class="card-body">
				<h5 class="card-title">Nama : <?php echo $data_siswa[0]->nama; ?></h5>
				<a href="#" class="btn btn-primary">Kelas <?php echo $data_siswa[0]->nama_kelas; ?></a>
			</div>
			</div><br>
        	<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Jenis</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                foreach ($data_user as $user) {
                    ?>
                <tr>
                    <td><?php echo $user->jenis;?></td>
                    <td><?php 
                    if($user->banyak != ''){
					echo convertToRupiah(enkripsiDekripsi(strval($user->banyak), $kunciRahasia));}
					?></td>
                    <td><?php echo $user->ket;?></td>
                </tr>
                <?php } ?>
                
            </tbody>
        </table>

        </div>
    </div>
</div>
<?php require "partials/footer.php"; ?>