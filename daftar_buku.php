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


    if(isset($_POST['simpan_data'])){
            $rfid_lama=$_POST['rfid_lama'];
            $rfid_baru=$_POST['rfid'];
            $lanjut=0;
            $data_rfid_buku = $qb->RAW(
            "SELECT * FROM buku where not rfid=?",[$rfid_lama]);
            foreach ($data_rfid_buku as $data_rfid_buku) {
                if($rfid_baru==($data_rfid_buku->rfid)){
                    echo '<div class="col-lg-12 mb-4">
                    <div class="card bg-danger text-white shadow">
                        <div class="card-body">
                            Gagal
                            <div class="text-white-50 small">Data RFID Sudah Ada</div>
                        </div>
                     </div>
                    </div>';
                $lanjut=1;
                }
            }
        if($lanjut==0){
            $aksi = $qb->RAW(
            "UPDATE buku SET 
            judul_buku='".$_POST['judul'].
            "',tahun_terbit='".$_POST['tahun'].
            "',penerbit='".$_POST['penerbit'].
            "',rfid='".$rfid_baru.
            "',penulis='".$_POST['penulis'].
            "' where id_buku=".$_POST['id_buku'],[]);
            
            if($aksi){
                echo '<div class="col-lg-12 mb-4">
                    <div class="card bg-success text-white shadow">
                        <div class="card-body">
                            Berhasil
                            <div class="text-white-50 small">Data Tersimpan</div>
                        </div>
                    </div>
                </div>';
            }else{
                echo '<div class="col-lg-12 mb-4">
                <div class="card bg-danger text-white shadow">
                    <div class="card-body">
                        Gagal
                        <div class="text-white-50 small">Data Gagal Tersimpan</div>
                    </div>
                 </div>
                </div>';
            }
          }
        }
        if(isset($_GET['hapus_buku'])){
            $aksi = $qb->RAW(
            "DELETE from buku where id_buku=".$_GET['hapus_buku'],[]);
            if($aksi){
                echo '<div class="col-lg-12 mb-4">
                    <div class="card bg-success text-white shadow">
                        <div class="card-body">
                            Berhasil
                            <div class="text-white-50 small">Data Terhapus</div>
                        </div>
                    </div>
                </div>';
            }else{
                echo '<div class="col-lg-12 mb-4">
                <div class="card bg-danger text-white shadow">
                    <div class="card-body">
                        Gagal
                        <div class="text-white-50 small">Data Gagal Terhapus</div>
                    </div>
                 </div>
                </div>';
            }
        }

    $table='kelas';
    $data_buku = $qb->RAW(
    "SELECT * FROM buku",[]);
    // print_r($data_kelas);
    // die();

    ?>

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Data Buku</h1>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Judul Buku</th>
                                            <th>Tahun Terbit</th>
                                            <th>Penulis</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        foreach ($data_buku as $buku) {
                                            ?>
                                        <tr>
                                            <td><?php echo $buku->judul_buku;?></td>
                                            <td><?php echo $buku->tahun_terbit;?></td>
                                            <td><?php echo $buku->penulis;?></td>
                                            <td>
                                            <center>
                                                <a href="daftar_buku.php?edit_buku=<?php echo $buku->id_buku;?>"><i class="fa-solid fa-pen-to-square"></i></a>
                                                &nbsp
                                                <a href="daftar_buku.php?hapus_buku=<?php echo $buku->id_buku;?>"><i class="fa-solid fa-trash-can"></i></a>
                                            </center>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        
                                    </tbody>
                                </table>
                            </div>
    <?php if(isset($_GET['edit_buku'])){
        $data_edit_buku = $qb->RAW(
        "SELECT * FROM buku where id_buku=?",[$_GET['edit_buku']]);
        ?>
    <br><br><br>
    <h1 class="h3 mb-2 text-gray-800">Edit Data</h1>
    <?php foreach ($data_edit_buku as $data_edit_buku) {?>
        <form  role="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
    <input name="id_buku" value="<?php echo $data_edit_buku->id_buku;?>" type="hidden" >
    <input name="rfid_lama" value="<?php echo $data_edit_buku->rfid;?>" type="hidden" >
    <div class="mb-3"><label for="exampleFormControlInput1">Judul Buku</label><input class="form-control" name="judul" value="<?php echo $data_edit_buku->judul_buku;?>" type="text" placeholder="Judul" required></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Tahun Terbit</label><input class="form-control" name="tahun" value="<?php echo $data_edit_buku->tahun_terbit;?>"type="text" placeholder="Tahun" required></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Penerbit</label><input class="form-control" name="penerbit" value="<?php echo $data_edit_buku->penerbit;?>" type="text" placeholder="Penerbit" required></div>
    <div class="mb-3"><label for="exampleFormControlInput1">RFID</label><input class="form-control" name="rfid" value="<?php echo $data_edit_buku->rfid;?>" type="text" placeholder="RFID" required></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Penulis</label><input class="form-control" name="penulis" value="<?php echo $data_edit_buku->penulis;?>" type="text" placeholder="Penulis" required></div>
    <button name="simpan_data" type='submit' class="btn btn-primary btn-user btn-block">Simpan</button>
    </form>
    <?php }} ?>

                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->


            
     <?php require "partials/footer.php"; ?>