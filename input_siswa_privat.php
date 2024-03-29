<?php 
require "partials/head.php";
// require "partials/sidebar.php"; 
require "asset/phpqrcode/qrlib.php"; ?>

<div class="container-fluid" style="
color: white!important;
background-color: skyblue; 
/*background: url('https://baradesain.files.wordpress.com/2021/03/tut-wuri-handayani-logo-featured-03.jpg?w=1200');*/
/*background: url('asset/desain/background.jpg');*/
/*background-position: center;*/
/*background-size: cover;*/
">
        <?php

require "vendor/autoload.php";

use StelinDB\Database\QueryBuilder;
use Carbon\Carbon;

$dotenv = new \Dotenv\Dotenv(__DIR__);
            $dotenv->load();
$now = new Carbon;
$now->setTimezone('Asia/Jakarta');

$qb = new QueryBuilder(\StelinDB\Database\Connection::Connect());

echo'<br><br><h4 class="sidebar-brand-text mx-3" style="text-align:center;">E-Smart Card</h4>';
$input=0;
if(isset($_POST['cari_user'])){

    $user = $qb->RAW(
    "SELECT * FROM user WHERE id_user=?",[$_POST['data_user']]);
    $user = $user[0];
    // print_r($user->kode_input);die();
    if($user->kode_input != $_POST['code_input']){
        echo'
            <div class="col-lg-12 mb-4">
                <div class="card bg-danger text-white shadow">
                    <div class="card-body">
                        Gagal
                        <div class="text-white-50 small">kode salah</div>
                    </div>
                </div>
            </div>
            ';
    }else{$input=1;}

    $table='kelas';
    $data_kelas = $qb->RAW(
    "SELECT * FROM kelas where id_user=".$_POST['data_user'],[]);
    // print_r($data_kelas);
    // die();
    }
 if(isset($_POST['simpan_data'])){

  $nama = $_POST['nama'];
  $norfid = $_POST['norfid'];
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
  $user_input=$_POST['data_user'];

$user = $qb->RAW(
    // "SELECT * FROM siswa WHERE norf='".$norfid."'",[]);
    "SELECT * FROM siswa WHERE nisn='".$nisn."'",[]);
// $rfid = $qb->RAW(
//     "SELECT * FROM siswa WHERE norf='".$norfid."'",[]);
//     // "SELECT * FROM siswa WHERE user_input=".$_SESSION['id_user']."and nisn='".$nisn."'",[]);


// // print_r($user);
// // die();
//   if (array_key_exists(0, $rfid)) {
//     echo'
//     <div class="col-lg-12 mb-4">
//         <div class="card bg-danger text-white shadow">
//             <div class="card-body">
//                 Gagal
//                 <div class="text-white-50 small">Data RFID Sudah Ada</div>
//             </div>
//         </div>
//     </div>
//     ';}else{
  if (array_key_exists(0, $user)) {
    echo'
    <div class="col-lg-12 mb-4">
        <div class="card bg-danger text-white shadow">
            <div class="card-body">
                Gagal
                <div class="text-white-50 small">Data NISN Sudah Ada</div>
            </div>
        </div>
    </div>
    ';
  }else{
$filename=$_FILES['file_kirim']['name'];
if(!empty($filename)){
$ext = pathinfo($_FILES['file_kirim']['name'], PATHINFO_EXTENSION);
$filename = $nisn.'.'.$ext;
// echo $filename;
// die();
// $filename=$filename.rand(111,9999);
$upload=move_uploaded_file($_FILES['file_kirim']['tmp_name'],  "asset/foto/".$filename);

 for($x=0;$x<20;$x++){
    if(filesize("asset/foto/".$filename)>50000)
    {resizer("asset/foto/".$filename, "asset/foto/".$filename, 70);}else{ break;}
    clearstatcache();
 }
}else{
    $filename='';
}
$rekapAbsen = $qb->insert('siswa', [
          'nama' => $nama,
          'nim' => $nim,
          'norf' => $norfid,
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
          'user_input' => $user_input,
          'no_ibu' => $no_ibu,
          'gaji_ibu' => $_POST['gaji_ibu'],
          'kerj_ibu' => $kerj_ibu,
          'pend_ibu' => $pend_ibu,
          'tgl_ibu' => $tgl_ibu,
          'tmp_ibu' => $tmp_ibu,
          'nik_ibu' => $nik_ibu,
          'nm_ibu' => $nm_ibu,
          'no_ayah' => $no_ayah,
          'gaji_ayah' => $_POST['gaji_ayah'],
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
          'provinsi' => $provinsi,
          'input_role' => 2
        ]);

            // $nameqrcode    = $norfid.'.png';              
            // $tempdir        = "asset/qrcode/"; 
            // $isiqrcode     = $server."data?rfid=".$norfid;
            // $quality        = 'H';
            // $Ukuran         = 10;
            // $padding        = 0;

            $nameqrcode    = $nisn.'.png';              
            $tempdir        = "asset/qrcode/"; 
            $isiqrcode     = $server."data?id=".$nisn;
            $quality        = 'H';
            $Ukuran         = 10;
            $padding        = 0;

            QRCode::png($isiqrcode,$tempdir.$nameqrcode,$quality,$Ukuran,$padding);


if($rekapAbsen){
   echo '
   <div class="col-lg-12 mb-4">
        <div class="card bg-success text-white shadow">
            <div class="card-body">
                Berhasil
                <div class="text-white-50 small">Data Tersimpan</div>
            </div>
        </div>
    </div>
    '; 
}else{
    echo'
    <div class="col-lg-12 mb-4">
        <div class="card bg-danger text-white shadow">
            <div class="card-body">
                Gagal
                <div class="text-white-50 small">Data Gagal Tersimpan</div>
            </div>
        </div>
    </div>
    ';
}
}
// }
}



    ?>
<br><br>
<form  role="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
<div class="col-lg-12 mb-2">
    <div class="input-group">
      <div class="input-group-prepend">
       <span  class="input-group-text" >Provinsi<sup style="color:brown;">*wajib</sup></span>
      </div>
        <select id="provinsi1" name="provinsi" class="form-control" required>
            <?php 
            $data= $qb->RAW("SELECT * FROM provinsi",[]);
            ?>
            <option value="">Provinsi</option>
            <?php foreach ($data as $data) {
                $selected='';
                if($_POST['provinsi'] == $data->id_prov){$selected='selected';}
                echo '<option value="'.$data->id_prov.'" '.$selected.'>'.$data->nama_provinsi.'</option>';
            }?>
        </select>
    </div>
</div>
<div class="col-lg-12 mb-2">
    <div class="input-group">
      <div class="input-group-prepend">
       <span  class="input-group-text" >Kabupaten<sup style="color:brown;">*wajib</sup></span>
      </div>
        <select id="kabupaten1" name="kabupaten" class="form-control" required>
            <?php 
            $data= $qb->RAW("SELECT * FROM kabupaten",[]);
            ?>
            <option value="">Kab</option>
            <?php foreach ($data as $data) {
                $selected='';
                if($_POST['kabupaten'] == $data->id_kab){$selected='selected';}
                echo '<option id="kabupaten1" class="'.$data->id_prov.'" value="'.$data->id_kab.'" '.$selected.'>'.$data->nama_kabupaten.'</option>';
            }?>
        </select>
    </div>
</div>
<div class="col-lg-12 mb-2">
    <div class="input-group">
      <div class="input-group-prepend">
       <span  class="input-group-text" >Kecamatan<sup style="color:brown;">*wajib</sup></span>
      </div>
        <select id="kecamatan1" name="kecamatan" class="form-control" required>
            <?php 
            $data= $qb->RAW("SELECT * FROM kecamatan",[]);
            ?>
            <option value="">Kec</option>
            <?php foreach ($data as $data) {
                $selected='';
                if($_POST['kecamatan'] == $data->id_kec){$selected='selected';}
                echo '<option id="kecamatan1" class="'.$data->id_kab.'" value="'.$data->id_kec.'" '.$selected.'>'.$data->nama_kecamatan.'</option>';
            }?>
        </select>
    </div>
</div>
<div class="col-lg-12 mb-2">
    <div class="input-group">
      <div class="input-group-prepend">
       <span  class="input-group-text" >Lembaga<sup style="color:brown;">*wajib</sup></span>
      </div>
        <select id="lembaga" class="form-control" name="data_user" required>
            <option value="">User</option>
            <?php
            $data_user = $qb->RAW(
                    "SELECT * FROM user",[]);
            foreach ($data_user as $user) {
                if($user->id_user == $_POST['data_user']){
                echo '<option id="lembaga" class="'.$user->kecamatan.'" value="'.$user->id_user.'" selected>'.$user->lembaga.'</option>';}
                else{
                echo '<option id="lembaga" class="'.$user->kecamatan.'" value="'.$user->id_user.'">'.$user->lembaga.'</option>';}
            }
            ?>
        </select>
    </div>
</div>
<div class="col-lg-12 mb-2">
    <div class="input-group">
      <div class="input-group-prepend">
       <span  class="input-group-text" >Kode<sup style="color:brown;">*wajib</sup></span>
      </div>
      <input type="text" class="form-control" name="code_input" placeholder="Kode" required>
      <div class="input-group-prepend">
       <button type="submit" name="cari_user" class="btn btn-primary"><span  id="">Input</span></button>
     </div>
    </div>
</div>
<br>
</form>
<?php if($input == 1){ ?>
<form  role="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
    <div class="mb-3"><label for="exampleFormControlInput1">Nama<sup style="color:brown;">*wajib</sup></label><input class="form-control" name="nama" type="text" placeholder="Masukkan Nama" required></div>
    <input class="form-control" name="data_user" type="hidden" value="<?php echo $_POST['data_user'];?>" placeholder="Masukkan Nama" required>
    <div class="mb-3"><label for="exampleFormControlInput1">NISN<sup style="color:brown;">*wajib</sup></label><input class="form-control" name="nisn" type="text" placeholder="Masukkan NISN" required></div>
    <div class="mb-3"><label for="exampleFormControlInput1">NIS<sup style="color:brown;">*wajib</sup></label><input class="form-control" name="nis" type="text" placeholder="Masukkan NIS" required></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Agama<sup style="color:brown;">*wajib</sup></label>
    <select name="agama" class="form-control" id="exampleFormControlSelect1" required>
            <?php 
            $data_agama = $qb->RAW("SELECT * FROM tb_agama",[]);
            ?>
            <option value="">Pilih</option>
            <?php foreach ($data_agama as $data_agama) {
                echo '<option value="'.$data_agama->id.'">'.$data_agama->agama.'</option>';
            }?>
        </select></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Tempat Lahir<sup style="color:brown;">*wajib</sup></label><input class="form-control" name="tmp_lhr" type="text" placeholder="Tempat Lahir" required></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Tanggal Lahir<sup style="color:brown;">*wajib</sup></label><input id="tgl_lahir" class="form-control" name="tgl_lhr" type="date" placeholder="Tanggal Lahir" required></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Umur</label><input class="form-control" id="umur"name="umur" type="text" placeholder="Masukkan Umur" required></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Jenis Kelamin<sup style="color:brown;">*wajib</sup></label>
    <select name="jk" class="form-control" id="exampleFormControlSelect1" required>
            <?php 
            $data_jk = $qb->RAW("SELECT * FROM tb_jk",[]);
            ?>
            <option value="">Pilih</option>
            <?php foreach ($data_jk as $data_jk) {
                echo '<option value="'.$data_jk->id.'">'.$data_jk->jk.'</option>';
            }?>
        </select></div>
    <div class="mb-3"><label for="exampleFormControlInput1">No RFID</label><input class="form-control" name="norfid" type="text" placeholder="RFID" ></div>
    <div class="mb-3"><label for="exampleFormControlInput1">NIK<sup style="color:brown;">*wajib</sup></label><input class="form-control" name="nim" type="text" placeholder="RFID" required></div>
    <div class="mb-3">
        <label for="exampleFormControlSelect1">Kelas<sup style="color:brown;">*wajib</sup></label>
        <select name="kelas" class="form-control" id="exampleFormControlSelect1" required>
            <option value="">Pilih Kelas</option>
            <?php foreach ($data_kelas as $data_kelas) {
                echo '<option value="'.$data_kelas->id_kelas.'">'.$data_kelas->kelas.'</option>';
            }?>
        </select>
    </div>
    <div class="mb-3"><label for="exampleFormControlInput1">Alamat Lengkap<sup style="color:brown;">*wajib</sup></label><input class="form-control" name="alamat" type="text" placeholder="Alamat" required></div>

    <div class="mb-3"><label for="exampleFormControlInput1">Provinsi<sup style="color:brown;">*wajib</sup></label> <select id="provinsi" name="provinsi" class="form-control" required>
            <?php 
            $data= $qb->RAW("SELECT * FROM provinsi",[]);
            ?>
            <option value="">Pilih</option>
            <?php foreach ($data as $data) {
                // echo '<option value="'.$data->id_prov.'">'.$data->nama_provinsi.'</option>';
                $selected='';
                if($_POST['provinsi'] == $data->id_prov){$selected='selected';}
                echo '<option value="'.$data->id_prov.'" '.$selected.'>'.$data->nama_provinsi.'</option>';
            }?>
        </select></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Kabupaten<sup style="color:brown;">*wajib</sup></label> <select id="kabupaten" name="kabupaten" class="form-control" required>
            <?php 
            $data= $qb->RAW("SELECT * FROM kabupaten",[]);
            ?>
            <option value="">Pilih</option>
            <?php foreach ($data as $data) {
                // echo '<option id="kabupaten" class="'.$data->id_prov.'" value="'.$data->id_kab.'">'.$data->nama_kabupaten.'</option>';
                $selected='';
                if($_POST['kabupaten'] == $data->id_kab){$selected='selected';}
                echo '<option id="kabupaten1" class="'.$data->id_prov.'" value="'.$data->id_kab.'" '.$selected.'>'.$data->nama_kabupaten.'</option>';
            }?>
        </select></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Kecamatan<sup style="color:brown;">*wajib</sup></label> <select onchange="myFunction()" id="kecamatan" name="kecamatan" class="form-control" required>
            <?php 
            $data= $qb->RAW("SELECT * FROM kecamatan",[]);
            ?>
            <option value="">Pilih</option>
            <?php foreach ($data as $data) {
                echo '<option id="kecamatan" class="'.$data->id_kab.'" value="'.$data->id_kec.'">'.$data->nama_kecamatan.'</option>';
            }?>
        </select></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Desa<sup style="color:brown;">*wajib</sup></label> <select name="desa" id="selectdesa" class="form-control" required></select></div>


           
    <!-- belum add -->
    <div class="mb-3"><label for="exampleFormControlInput1">Kodepos</label><input class="form-control" name="kodepos" type="text" placeholder="Kodepos" ></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Sekolah Asal</label><input class="form-control" name="s_asal" type="text" placeholder="Sekolah Asal" ></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Tanggal Diterima</label><input class="form-control" name="tgl_diterima" type="date" placeholder="Tanggal Diterima" ></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Anak Ke</label><input class="form-control" name="anak_ke" type="number" placeholder="Anak Ke" ></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Jumlah Saudara</label><input class="form-control" name="j_saudara" type="number" placeholder="Jumlah Saudara" ></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Cita-cita</label><input class="form-control" name="cita_cita" type="text" placeholder="Cita-cita" ></div>
    <div class="mb-3"><label for="exampleFormControlInput1">No Hp</label><input class="form-control" name="no_hp" type="number" placeholder="Nomor Hp" ></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Alamat Email</label><input class="form-control" name="email" type="email" placeholder="Email" ></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Hobi</label><input class="form-control" name="hobi" type="text" placeholder="Hobi" ></div>
    <div class="mb-3"><label for="exampleFormControlInput1">No KK</label><input class="form-control" name="no_kk" type="text" placeholder="Nomor Kartu Keluarga" ></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Nama Kepala Keluarga</label><input class="form-control" name="nama_kk" type="text" placeholder="Nama Kepala Keluarga" ></div>

    <!-- data ayah -->
    <div class="mb-3"><label for="exampleFormControlInput1">Nama Ayah</label><input class="form-control" name="nm_ayah" type="text" placeholder="Nama Ayah" ></div>
    <div class="mb-3"><label for="exampleFormControlInput1">NIK Ayah</label><input class="form-control" name="nik_ayah" type="text" placeholder="NIK Ayah" ></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Tempat Lahir Ayah</label><input class="form-control" name="tmp_ayah" type="text" placeholder="Tempat lahir" ></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Tanggal Lahir Ayah</label><input class="form-control" name="tgl_ayah" type="date" placeholder="Tanggal Lahir" ></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Pendidikan Ayah</label> <select name="pend_ayah" class="form-control">
            <?php 
            $data = $qb->RAW("SELECT * FROM tb_pendidikan",[]);
            ?>
            <option value="">Pilih</option>
            <?php foreach ($data as $data) {
                echo '<option value="'.$data->id.'">'.$data->pendidikan.'</option>';
            }?>
        </select></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Pekerjaan Ayah</label> <select name="kerj_ayah" class="form-control">
            <?php 
            $data = $qb->RAW("SELECT * FROM tb_pekerjaan",[]);
            ?>
            <option value="">Pilih</option>
            <?php foreach ($data as $data) {
                echo '<option value="'.$data->id.'">'.$data->pekerjaan.'</option>';
            }?>
        </select></div>
    <div class="mb-3"><label for="exampleFormControlInput1">No Ayah</label><input class="form-control" name="no_ayah" type="text" placeholder="No Hp" ></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Gaji Ayah</label><input class="form-control" name="gaji_ayah" type="Number" placeholder="Gaji Ayah" ></div>

    <!-- data ibu -->
    <div class="mb-3"><label for="exampleFormControlInput1">Nama Ibu</label><input class="form-control" name="nm_ibu" type="text" placeholder="Nama Ibu" ></div>
    <div class="mb-3"><label for="exampleFormControlInput1">NIK Ibu</label><input class="form-control" name="nik_ibu" type="text" placeholder="NIK Ibu" ></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Tempat Lahir Ibu</label><input class="form-control" name="tmp_ibu" type="text" placeholder="Tempat Lahir" ></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Tanggal Lahir Ibu</label><input class="form-control" name="tgl_ibu" type="date" placeholder="Tanggal Lahir" ></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Pendidikan Ibu</label> <select name="pend_ibu" class="form-control">
            <?php 
            $data = $qb->RAW("SELECT * FROM tb_pendidikan",[]);
            ?>
            <option value="">Pilih</option>
            <?php foreach ($data as $data) {
                echo '<option value="'.$data->id.'">'.$data->pendidikan.'</option>';
            }?>
        </select></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Pekerjaan Ibu</label> <select name="kerj_ibu" class="form-control">
            <?php 
            $data = $qb->RAW("SELECT * FROM tb_pekerjaan",[]);
            ?>
            <option value="">Pilih</option>
            <?php foreach ($data as $data) {
                echo '<option value="'.$data->id.'">'.$data->pekerjaan.'</option>';
            }?>
        </select></div>
    <div class="mb-3"><label for="exampleFormControlInput1">No Ibu</label><input class="form-control" name="no_ibu" type="text" placeholder="No Hp Ibu" ></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Gaji Ibu</label><input class="form-control" name="gaji_ibu" type="text" placeholder="Gaji Ibu" ></div>

    
    <div class="mb-3"><label for="exampleFormControlInput1">Foto</label><input class="form-control" name="file_kirim" type="file" placeholder="RFID" ></div>
    <button name="simpan_data" type='submit' class="btn btn-primary btn-user btn-block">Simpan</button>
</form>
<?php } ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript">
                      function myFunction() {
                          var elt=$('#kecamatan').val();
                          // alert(elt);
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
                                options += "<option value='"+data[i].id_desa+"'>"+ data[i].nama_desa+"</option>";    
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

    $("#kabupaten1").chained("#provinsi1");
    $("#kecamatan1").chained("#kabupaten1");
    $("#lembaga").chained("#kecamatan1");
  </script>
 <?php require "partials/footer.php"; ?>
</div>