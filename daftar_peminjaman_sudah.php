<?php 
require "partials/head.php";
require "partials/sidebar.php"; ?>

                <!-- Begin Page Content -->
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


    if(isset($_GET['peminjaman'])){
            
            $aksi = $qb->RAW(
            "UPDATE peminjaman SET 
            status=1 where id_peminjaman=".$_GET['peminjaman'],[]);
            
            if($aksi){
                echo '<div class="col-lg-12 mb-4">
                    <div class="card bg-success text-white shadow">
                        <div class="card-body">
                            Berhasil
                            <div class="text-white-50 small">Buku Telah Dikembalikan</div>
                        </div>
                    </div>
                </div>';
            }else{
                echo '<div class="col-lg-12 mb-4">
                <div class="card bg-danger text-white shadow">
                    <div class="card-body">
                        Gagal
                        <div class="text-white-50 small">Harap Ulangi</div>
                    </div>
                 </div>
                </div>';
            }
          
        }

    $table='kelas';
    $data_buku = $qb->RAW(
    "SELECT * FROM peminjaman
    join siswa on siswa.norf=peminjaman.peminjam
    where peminjaman.status=1
    and peminjaman.user=?
    ",[$_SESSION['id_user']]);
    // print_r($data_kelas);
    // die();

    ?>

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Data Peminjaman Buku (Sudah Dikembalikan)</h1>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Peminjam</th>
                                            <th>Buku</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        foreach ($data_buku as $buku) {
                                            ?>
                                        <tr>
                                            <td><?php echo $buku->nama;?></td>
                                            <td>
                                            <ul>
                                            <?php 
                                            $data=explode(',',$buku->buku);
                                            $total_buku=count($data);
                                            // print_r($data);
                                            // die();
                                            for($x=0;$x<$total_buku;$x++){
                                                $nama_buku = $qb->RAW(
                                                    "SELECT * FROM buku where rfid=?
                                                    ",[$data[$x]]);
                                                echo '<li>'.$nama_buku[0]->judul_buku.'</li>';
                                            }
                                            ?>  
                                            </ul>  
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        
                                    </tbody>
                                </table>
                            </div>
    
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->


            
     <?php require "partials/footer.php"; ?>