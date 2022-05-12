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
            $rfid_baru=$_POST['norfid'];
            $lanjut=0;
            $data_rfid = $qb->RAW(
            "SELECT * FROM siswa where not norf=?",[$rfid_lama]);
            foreach ($data_rfid as $data_rfid) {
                if($rfid_baru==($data_rfid->norf)){
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
            $filename=$_FILES['file_kirim']['name'];
            if(!empty($filename)){
            unlink('asset/foto/'.$_POST['foto_lama']);
            $upload=move_uploaded_file($_FILES['file_kirim']['tmp_name'],  "asset/foto/".$filename);
            for($x=0;$x<20;$x++){
            if(filesize("asset/foto/".$filename)>50000)
            {resizer("asset/foto/".$filename, "asset/foto/".$filename, 70);}else{ break;}
            clearstatcache();
            }
            $aksi = $qb->RAW(
            "UPDATE siswa SET 
            nama='".$_POST['nama'].
            "',nim='".$_POST['nim'].
            "',kelas='".$_POST['kelas'].
            "',norf='".$rfid_baru.
            "',foto='".$filename.
            "' where id=".$_POST['id_siswa'],[]);
            }else{
            $aksi = $qb->RAW(
            "UPDATE siswa SET 
            nama='".$_POST['nama'].
            "',nim='".$_POST['nim'].
            "',kelas='".$_POST['kelas'].
            "',norf='".$rfid_baru.
            "' where id=".$_POST['id_siswa'],[]);
            }
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
        if(isset($_GET['hapus_siswa'])){
            $aksi = $qb->RAW(
            "DELETE from siswa where id=".$_GET['hapus_siswa'],[]);
            if($aksi){
                unlink('asset/foto/'.$_GET['foto_siswa']);
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
    $data_siswa = $qb->RAW(
    "SELECT *,kelas.kelas as nama_kelas FROM siswa
    join kelas on kelas.id_kelas=siswa.kelas
    ",[]);
    // print_r($data_kelas);
    // die();

    ?>

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Data Siswa</h1>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Nama Siswa</th>
                                            <th>NIM</th>
                                            <th>Kelas</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        foreach ($data_siswa as $siswa) {
                                            ?>
                                        <tr>
                                            <td><?php echo $siswa->nama;?></td>
                                            <td><?php echo $siswa->nim;?></td>
                                            <td><?php echo $siswa->nama_kelas;?></td>
                                            <td>
                                            <center>
                                                <a href="data_siswa.php?edit_siswa=<?php echo $siswa->id;?>"><i class="fa-solid fa-pen-to-square"></i></a>
                                                &nbsp
                                                <a href="data_siswa.php?hapus_siswa=<?php echo $siswa->id;?>&&foto_siswa=<?php echo $siswa->foto;?>"><i class="fa-solid fa-trash-can"></i></a>
                                            </center>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        
                                    </tbody>
                                </table>
                            </div>
    <?php if(isset($_GET['edit_siswa'])){
        $data_edit_siswa = $qb->RAW(
        "SELECT * FROM siswa where id=?",[$_GET['edit_siswa']]);
        $data_kelas = $qb->RAW(
        "SELECT * FROM kelas",[]);
        ?>
    <br><br><br>
    <h1 class="h3 mb-2 text-gray-800">Edit Data</h1>
    <?php foreach ($data_edit_siswa as $data_edit_siswa) {?>
        <form  role="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
    <input name="id_siswa" value="<?php echo $data_edit_siswa->id;?>" type="hidden" >
    <input name="rfid_lama" value="<?php echo $data_edit_siswa->norf;?>" type="hidden" >
    <input name="foto_lama" value="<?php echo $data_edit_siswa->foto;?>" type="hidden" >
    <div class="mb-3"><label for="exampleFormControlInput1">Nama</label><input class="form-control" name="nama" value="<?php echo $data_edit_siswa->nama;?>" type="text" placeholder="Masukkan Nama" required></div>
    <div class="mb-3"><label for="exampleFormControlInput1">No RFID</label><input class="form-control" name="norfid" value="<?php echo $data_edit_siswa->norf;?>"type="text" placeholder="RFID" required></div>
    <div class="mb-3"><label for="exampleFormControlInput1">NIM</label><input class="form-control" name="nim" value="<?php echo $data_edit_siswa->nim;?>" type="text" placeholder="RFID" required></div>
    <div class="mb-3">
        <label for="exampleFormControlSelect1">Kelas</label>
        <select name="kelas" class="form-control" id="exampleFormControlSelect1" required>
            <option value="">Pilih Kelas</option>
            <?php foreach ($data_kelas as $data_kelas) {
                if(($data_kelas->id_kelas)==($data_edit_siswa->kelas)){
                echo '<option value="'.$data_kelas->id_kelas.'" selected>'.$data_kelas->kelas.'</option>';
                }else{
                echo '<option value="'.$data_kelas->id_kelas.'">'.$data_kelas->kelas.'</option>';
                }
            }?>
            <!-- <option>1</option>
            <option>2</option>
            <option>3</option>
            <option>4</option>
            <option>5</option> -->
        </select>
    </div>
    <?php $time = date("H:i:s");?>
    <img src="asset/foto/<?php echo $data_edit_siswa->foto;?>?time=<?php echo $time;?>" width="150" class="img-thumbnail">
    <div class="mb-3"><label for="exampleFormControlInput1">Foto</label><input class="form-control" name="file_kirim" type="file" placeholder="RFID"></div>
    <button name="simpan_data" type='submit' class="btn btn-primary btn-user btn-block">Simpan</button>
    </form>
    <?php }} ?>

                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->


            
     <?php require "partials/footer.php"; ?>