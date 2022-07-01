<?php 
require "partials/head.php";
require "partials/sidebar.php";
require "asset/phpqrcode/qrlib.php"; ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">
<?php

require "vendor/autoload.php";

use StelinDB\Database\QueryBuilder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

$dotenv = new \Dotenv\Dotenv(__DIR__);
            $dotenv->load();
$now = new Carbon;
$now->setTimezone('Asia/Jakarta');

$qb = new QueryBuilder(\StelinDB\Database\Connection::Connect());


    if(isset($_POST['simpan_data'])){
              $nama = $_POST['nama'];
              $nim = $_POST['nim'];
              $kelas = $_POST['kelas'];

              $jk = $_POST['jk'];
              $alamat = $_POST['alamat'];
              $umur = $_POST['umur'];
              $tgl_lhr = $_POST['tgl_lhr'];
              $tmp_lhr = $_POST['tmp_lhr'];
              $agama = $_POST['agama'];
              $nisn = $_POST['nisn'];
              $nis = $_POST['nis'];

              $no_ibu = $_POST['no_ibu'];
              $kerj_ibu = $_POST['kerj_ibu'];
              $pend_ibu = $_POST['pend_ibu'];
              $tgl_ibu = $_POST['tgl_ibu'];
              $tmp_ibu = $_POST['tmp_ibu'];
              $nik_ibu = $_POST['nik_ibu'];
              $nik_ibu = $_POST['nik_ibu'];
              $nm_ibu = $_POST['nm_ibu'];
              $no_ayah= $_POST['no_ayah'];
              $kerj_ayah = $_POST['kerj_ayah'];
              $pend_ayah = $_POST['pend_ayah'];
              $tgl_ayah = $_POST['tgl_ayah'];
              $tmp_ayah = $_POST['tmp_ayah'];
              $nik_ayah = $_POST['nik_ayah'];
              $nm_ayah = $_POST['nm_ayah'];
              $nama_kk = $_POST['nama_kk'];
              $no_kk = $_POST['no_kk'];
              $hobi = $_POST['hobi'];
              $email = $_POST['email'];
              $no_hp = $_POST['no_hp'];
              $cita_cita = $_POST['cita_cita'];
              $j_saudara = $_POST['j_saudara'];
              $anak_ke = $_POST['anak_ke'];
              $tgl_diterima = $_POST['tgl_diterima'];
              $s_asal = $_POST['s_asal'];
              $kodepos = $_POST['kodepos'];
              $desa= $_POST['desa'];
              $kecamatan= $_POST['kecamatan'];
              $kabupaten= $_POST['kabupaten'];
              $provinsi= $_POST['provinsi'];

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
            $filename=$filename.rand(111,9999);
            unlink('asset/foto/'.$_POST['foto_lama']);
            $upload=move_uploaded_file($_FILES['file_kirim']['tmp_name'],  "asset/foto/".$filename);
                for($x=0;$x<20;$x++){
                    if(filesize("asset/foto/".$filename)>50000)
                    {resizer("asset/foto/".$filename, "asset/foto/".$filename, 70);}else{ break;}
                    clearstatcache();
                }
            }else{
            $filename=$_POST['foto_lama'];    
            }
            $data=[
              'nama' => $nama,
              'nim' => $nim,
              'norf' => $rfid_baru,
              'kelas' => $kelas,
              'nisn' => $nisn,
              'nis' => $nis,
              'agama' => $agama,
              'tmp_lhr' => $tmp_lhr,
              'tgl_lhr' => $tgl_lhr,
              'umur' => $umur,
              'alamat' => $alamat,
              'jk' => $jk,
              'foto' => $filename,
              'no_ibu' => $no_ibu,
              'kerj_ibu' => $kerj_ibu,
              'pend_ibu' => $pend_ibu,
              'tgl_ibu' => $tgl_ibu,
              'tmp_ibu' => $tmp_ibu,
              'nik_ibu' => $nik_ibu,
              'nm_ibu' => $nm_ibu,
              'no_ayah' => $no_ayah,
              'kerj_ayah' => $kerj_ayah,
              'pend_ayah' => $pend_ayah,
              'tgl_ayah' => $tgl_ayah,
              'tmp_ayah' => $tmp_ayah,
              'nik_ayah' => $nik_ayah,
              'nm_ayah' => $nm_ayah,
              'nama_kk' => $nama_kk,
              'no_kk' => $no_kk,
              'hobi' => $hobi,
              'email' => $email,
              'no_hp' => $no_hp,
              'cita_cita' => $cita_cita,
              'j_saudara' => $j_saudara,
              'anak_ke' => $anak_ke,
              'tgl_diterima' => $tgl_diterima,
              's_asal' => $s_asal,
              'kodepos' => $kodepos,
              'desa' => $desa,
              'kecamatan' => $kecamatan,
              'kabupaten' => $kabupaten,
              'provinsi' => $provinsi
            ];
            $set=array();
            foreach ($data as $key => $value) {
                // $code=$key."=".$value;
                $code=$key."='".$value."'";
                array_push($set, $code);
            }
            $set=implode(',',$set);
            $aksi = $qb->RAW(
            "UPDATE siswa SET ".$set." where id=".$_POST['id_siswa'],[]);

            $nameqrcode    = $rfid_baru.'.png';              
            $tempdir        = "asset/qrcode/"; 
            $isiqrcode     = $server."data?rfid=".$rfid_baru;
            $quality        = 'H';
            $Ukuran         = 10;
            $padding        = 0;

            QRCode::png($isiqrcode,$tempdir.$nameqrcode,$quality,$Ukuran,$padding);

            // $aksi = $qb->RAW(
            // "UPDATE siswa SET 
            // nama='".$_POST['nama'].
            // "',nim='".$_POST['nim'].
            // "',kelas='".$_POST['kelas'].
            // "',norf='".$rfid_baru.
            // "',foto='".$filename.
            // "' where id=".$_POST['id_siswa'],[]);

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
    where siswa.user_input=".$_SESSION['id_user']
    ,[]);
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
                                            <th>NIS</th>
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
                                            <td><?php echo $siswa->nis;?></td>
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

    <!-- udah -->

    <div class="mb-3"><label for="exampleFormControlInput1">Nama</label><input class="form-control" name="nama" type="text" placeholder="Masukkan Nama" value="<?php echo $data_edit_siswa->nama;?>" required></div>
    <div class="mb-3"><label for="exampleFormControlInput1">NISN</label><input class="form-control" name="nisn" type="text" placeholder="Masukkan NISN" value="<?php echo $data_edit_siswa->nisn;?>" required></div>
    <div class="mb-3"><label for="exampleFormControlInput1">NIS</label><input class="form-control" name="nis" type="text" placeholder="Masukkan NIS" value="<?php echo $data_edit_siswa->nis;?>" required></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Agama</label>
    <select name="agama" class="form-control" id="exampleFormControlSelect1" required>
            <?php 
            $data_agama = $qb->RAW("SELECT * FROM tb_agama",[]);
            ?>
            <option value="">Pilih</option>
            <?php foreach ($data_agama as $data_agama) {
                if($data_agama->id == $data_edit_siswa->agama){
                echo '<option value="'.$data_agama->id.'" selected>'.$data_agama->agama.'</option>';
                }else{
                echo '<option value="'.$data_agama->id.'">'.$data_agama->agama.'</option>';
                }
            }?>
        </select></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Tempat Lahir</label><input class="form-control" name="tmp_lhr" type="text" placeholder="Tempat Lahir" value="<?php echo $data_edit_siswa->tmp_lhr;?>" required></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Tanggal Lahir</label><input id="tgl_lahir" class="form-control" name="tgl_lhr" type="date" placeholder="Tanggal Lahir" value="<?php echo $data_edit_siswa->tgl_lhr;?>" required></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Umur</label><input class="form-control" id="umur"name="umur" type="text" placeholder="Masukkan Umur" value="<?php echo $data_edit_siswa->umur;?>" required></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Jenis Kelamin</label>
    <select name="jk" class="form-control" id="exampleFormControlSelect1" required>
            <?php 
            $data_jk = $qb->RAW("SELECT * FROM tb_jk",[]);
            ?>
            <option value="">Pilih</option>
            <?php foreach ($data_jk as $data_jk) {
                if($data_jk->id == $data_edit_siswa->jk){
                echo '<option value="'.$data_jk->id.'" selected>'.$data_jk->jk.'</option>';
                }else{
                echo '<option value="'.$data_jk->id.'">'.$data_jk->jk.'</option>';
                }
            }?>
        </select></div>
    <div class="mb-3"><label for="exampleFormControlInput1">No RFID</label><input class="form-control" name="norfid" type="text" placeholder="RFID" value="<?php echo $data_edit_siswa->norf;?>" required></div>
    <div class="mb-3"><label for="exampleFormControlInput1">NIK</label><input class="form-control" name="nim" type="text" placeholder="RFID" value="<?php echo $data_edit_siswa->nim;?>" required></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Alamat Lengkap</label><input class="form-control" name="alamat" type="text" placeholder="Alamat" value="<?php echo $data_edit_siswa->alamat;?>"required></div>

    <div class="mb-3"><label for="exampleFormControlInput1">Provinsi</label> <select id="provinsi" name="provinsi" class="form-control" required>
            <?php 
            $data= $qb->RAW("SELECT * FROM provinsi",[]);
            ?>
            <option value="">Pilih</option>
            <?php foreach ($data as $data) {
                if($data->id_prov == $data_edit_siswa->provinsi){
                echo '<option value="'.$data->id_prov.'" selected>'.$data->nama_provinsi.'</option>';
                }else{
                echo '<option value="'.$data->id_prov.'">'.$data->nama_provinsi.'</option>';
                }
            }?>
        </select></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Kabupaten</label> <select id="kabupaten" name="kabupaten" class="form-control" required>
            <?php 
            $data= $qb->RAW("SELECT * FROM kabupaten",[]);
            ?>
            <option value="">Pilih</option>
            <?php foreach ($data as $data) {
                if($data->id_kab == $data_edit_siswa->kabupaten){
                echo '<option id="kabupaten" class="'.$data->id_prov.'" value="'.$data->id_kab.'" selected>'.$data->nama_kabupaten.'</option>';
                }else{
                echo '<option id="kabupaten" class="'.$data->id_prov.'" value="'.$data->id_kab.'">'.$data->nama_kabupaten.'</option>';
                }
            }?>
        </select></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Kecamatan</label> <select onchange="myFunction()" id="kecamatan" name="kecamatan" class="form-control" required>
            <?php 
            $data= $qb->RAW("SELECT * FROM kecamatan",[]);
            ?>
            <option value="">Pilih</option>
            <?php foreach ($data as $data) {
                if($data->id_kec == $data_edit_siswa->kecamatan){
                echo '<option id="kecamatan" class="'.$data->id_kab.'" value="'.$data->id_kec.'" selected>'.$data->nama_kecamatan.'</option>';
                }else{
                echo '<option id="kecamatan" class="'.$data->id_kab.'" value="'.$data->id_kec.'">'.$data->nama_kecamatan.'</option>';
                }
            }?>
        </select></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Desa</label> <select name="desa" id="selectdesa" class="form-control" required></select></div>


           
    <!-- belum add -->
    <div class="mb-3"><label for="exampleFormControlInput1">Kodepos</label><input class="form-control" name="kodepos" type="text" placeholder="Kodepos" value="<?php echo $data_edit_siswa->kodepos;?>"></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Sekolah Asal</label><input class="form-control" name="s_asal" type="text" placeholder="Sekolah Asal" value="<?php echo $data_edit_siswa->s_asal;?>"></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Tanggal Diterima</label><input class="form-control" name="tgl_diterima" type="date" placeholder="Tanggal Diterima" value="<?php echo $data_edit_siswa->tgl_diterima;?>"></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Anak Ke</label><input class="form-control" name="anak_ke" type="number" placeholder="Anak Ke" value="<?php echo $data_edit_siswa->anak_ke;?>"></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Jumlah Saudara</label><input class="form-control" name="j_saudara" type="number" placeholder="Jumlah Saudara" value="<?php echo $data_edit_siswa->j_saudara;?>"></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Cita-cita</label><input class="form-control" name="cita_cita" type="text" placeholder="Cita-cita" value="<?php echo $data_edit_siswa->cita_cita;?>"></div>
    <div class="mb-3"><label for="exampleFormControlInput1">No Hp</label><input class="form-control" name="no_hp" type="number" placeholder="Nomor Hp" value="<?php echo $data_edit_siswa->no_hp;?>"></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Alamat Email</label><input class="form-control" name="email" type="email" placeholder="Email" value="<?php echo $data_edit_siswa->email;?>"></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Hobi</label><input class="form-control" name="hobi" type="text" placeholder="Hobi" value="<?php echo $data_edit_siswa->hobi;?>"></div>
    <div class="mb-3"><label for="exampleFormControlInput1">No KK</label><input class="form-control" name="no_kk" type="text" placeholder="Nomor Kartu Keluarga" value="<?php echo $data_edit_siswa->no_kk;?>"></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Nama Kepala Keluarga</label><input class="form-control" name="nama_kk" type="text" placeholder="Nama Kepala Keluarga" value="<?php echo $data_edit_siswa->nama_kk;?>"></div>

    <!-- data ayah -->
    <div class="mb-3"><label for="exampleFormControlInput1">Nama Ayah</label><input class="form-control" name="nm_ayah" type="text" placeholder="Nama Ayah" value="<?php echo $data_edit_siswa->nm_ayah;?>"></div>
    <div class="mb-3"><label for="exampleFormControlInput1">NIK Ayah</label><input class="form-control" name="nik_ayah" type="text" placeholder="NIK Ayah" value="<?php echo $data_edit_siswa->nik_ayah;?>"></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Tempat Lahir Ayah</label><input class="form-control" name="tmp_ayah" type="text" placeholder="Tempat lahir" value="<?php echo $data_edit_siswa->tmp_ayah;?>"></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Tanggal Lahir Ayah</label><input class="form-control" name="tgl_ayah" type="date" placeholder="Tanggal Lahir" value="<?php echo $data_edit_siswa->tgl_ayah;?>"></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Pendidikan Ayah</label> <select name="pend_ayah" class="form-control" >
            <?php 
            $data = $qb->RAW("SELECT * FROM tb_pendidikan",[]);
            ?>
            <option value="">Pilih</option>
            <?php foreach ($data as $data) {
                if($data->id == $data_edit_siswa->pend_ayah){
                echo '<option value="'.$data->id.'" selected>'.$data->pendidikan.'</option>';
                }else{
                echo '<option value="'.$data->id.'">'.$data->pendidikan.'</option>';
                }
            }?>
        </select></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Pekerjaan Ayah</label> <select name="kerj_ayah" class="form-control">
            <?php 
            $data = $qb->RAW("SELECT * FROM tb_pekerjaan",[]);
            ?>
            <option value="">Pilih</option>
            <?php foreach ($data as $data) {
                if($data->id == $data_edit_siswa->kerj_ayah){
                echo '<option value="'.$data->id.'" selected>'.$data->pekerjaan.'</option>';
                }else{
                echo '<option value="'.$data->id.'">'.$data->pekerjaan.'</option>';
                }
            }?>
        </select></div>
    <div class="mb-3"><label for="exampleFormControlInput1">No Ayah</label><input class="form-control" name="no_ayah" type="text" placeholder="No Hp" value="<?php echo $data_edit_siswa->no_ayah;?>"></div>

    <!-- data ibu -->
    <div class="mb-3"><label for="exampleFormControlInput1">Nama Ibu</label><input class="form-control" name="nm_ibu" type="text" placeholder="Nama Ibu" value="<?php echo $data_edit_siswa->nm_ibu;?>"></div>
    <div class="mb-3"><label for="exampleFormControlInput1">NIK Ibu</label><input class="form-control" name="nik_ibu" type="text" placeholder="NIK Ibu" value="<?php echo $data_edit_siswa->nik_ibu;?>"></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Tempat Lahir Ibu</label><input class="form-control" name="tmp_ibu" type="text" placeholder="Tempat Lahir" value="<?php echo $data_edit_siswa->tmp_ibu;?>"></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Tanggal Lahir Ibu</label><input class="form-control" name="tgl_ibu" type="date" placeholder="Tanggal Lahir" value="<?php echo $data_edit_siswa->tgl_ibu;?>"></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Pendidikan Ibu</label> <select name="pend_ibu" class="form-control">
            <?php 
            $data = $qb->RAW("SELECT * FROM tb_pendidikan",[]);
            ?>
            <option value="">Pilih</option>
            <?php foreach ($data as $data) {
                if($data->id == $data_edit_siswa->pend_ibu){
                echo '<option value="'.$data->id.'" selected>'.$data->pendidikan.'</option>';
                }else{
                echo '<option value="'.$data->id.'">'.$data->pendidikan.'</option>';
                }
            }?>
        </select></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Pekerjaan Ibu</label> <select name="kerj_ibu" class="form-control">
            <?php 
            $data = $qb->RAW("SELECT * FROM tb_pekerjaan",[]);
            ?>
            <option value="">Pilih</option>
            <?php foreach ($data as $data) {
                if($data->id == $data_edit_siswa->kerj_ibu){
                echo '<option value="'.$data->id.'" selected>'.$data->pekerjaan.'</option>';
                }else{
                echo '<option value="'.$data->id.'">'.$data->pekerjaan.'</option>';
                }
            }?>
        </select></div>
    <div class="mb-3"><label for="exampleFormControlInput1">No Ibu</label><input class="form-control" name="no_ibu" type="text" placeholder="No Hp Ibu" value="<?php echo $data_edit_siswa->no_ibu;?>"></div>


    <!-- udah -->

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



    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript">
        var desa="<?php echo $data_edit_siswa->desa; ?>";
                          var elt=$('#kecamatan').val();
                          // alert(elt);
                          // alert(desa);
                            $.ajax({
                            type: "POST",
                            url : "select_desa.php",
                            data: {kecamatan: elt},
                            dataType:'json',
                            success: function(data) {
                                console.log('show village');

                               var select = $("#selectdesa"), options = '';
                               select.empty();      

                               for(var i=0;i<data.length; i++)
                               {
                                if(data[i].id_desa == desa){
                                options += "<option value='"+data[i].id_desa+"' selected>"+ data[i].nama_desa+"</option>";
                                }else{  
                                options += "<option value='"+data[i].id_desa+"'>"+ data[i].nama_desa+"</option>";    
                                }
                                // console.log(options);          
                               }
                               select.append(options);
                            }
                            });
        function myFunction() {
                          var desa="<?php echo $data_edit_siswa->desa; ?>";
                          var elt=$('#kecamatan').val();
                          // alert(elt);
                          alert(desa);
                            $.ajax({
                            type: "POST",
                            url : "select_desa.php",
                            data: {kecamatan: elt},
                            dataType:'json',
                            success: function(data) {
                                console.log('show village');

                               var select = $("#selectdesa"), options = '';
                               select.empty();      

                               for(var i=0;i<data.length; i++)
                               {
                                if(data[i].id_desa == desa){
                                options += "<option value='"+data[i].id_desa+"' selected>"+ data[i].nama_desa+"</option>";
                                }else{  
                                options += "<option value='"+data[i].id_desa+"'>"+ data[i].nama_desa+"</option>";    
                                }
                                // console.log(options);          
                               }
                               select.append(options);
                            }
                            });
                      };
                  </script>

  <script type="text/javascript">
         $(function() {
           $( "#tgl_lahir" ).datepicker({
                  changeMonth: true,
                  changeYear: true
              });
         });
            window.onload=function(){
                $('#tgl_lahir').on('change', function() {
                    var dob = new Date(this.value);
                    var today = new Date();
          var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
                    $('#umur').val(age);
                });
            }
    </script>

    <script src="asset/js/jquery-1.10.2.min.js"></script>
  <script src="asset/js/jquery.chained.min.js"></script>

  <script>
    $("#kabupaten").chained("#provinsi");
    $("#kecamatan").chained("#kabupaten");
  </script>
     <?php require "partials/footer.php"; ?>