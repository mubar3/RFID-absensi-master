<?php 
$id=$_GET['id'];
// echo $id; die();

require "vendor/autoload.php";
require "partial/head.php";
require "partials/head.php";
use StelinDB\Database\QueryBuilder;
use Carbon\Carbon;

$dotenv = new \Dotenv\Dotenv(__DIR__);
            $dotenv->load();
$now = new Carbon;
$now->setTimezone('Asia/Jakarta');

$qb = new QueryBuilder(\StelinDB\Database\Connection::Connect());

$data_siswa = $qb->RAW(
            "SELECT * FROM siswa  
            join kelas on kelas.id_kelas=siswa.kelas
            join tb_jk on tb_jk.id=siswa.jk
            where siswa.nisn='".$id."' order by last_update desc",[]);
// print_r($data_siswa);die();
if(array_key_exists(0, $data_siswa)){
            $siswa=$data_siswa[0];
?>
<script src="https://kit.fontawesome.com/6e703c102f.js" crossorigin="anonymous"></script>
<style type="text/css">
  div,table,body,p {color: black!important; font-family:times;}
</style>
<body>
<section style="
/*background-color: #eee; */
background: url('https://baradesain.files.wordpress.com/2021/03/tut-wuri-handayani-logo-featured-03.jpg?w=1200');
background-position: center;
background-size: cover;">
  <div class="container py-5">

    <div class="row">
      <div class="col-lg-4">
        <div class="card mb-4">
          <div class="card-body text-center">
            <img src="asset/foto/<?php echo $siswa->foto; ?>" alt="avatar"
              class="img-fluid" style="width: 150px;">
            <H5 class="my-3" style="color:black;" >NAMA :</H5>
            <p class="mb-1"><?php echo $siswa->nama; ?></p>
            <H5 class="my-3" style="color:black"  >KELAS :</H5>
            <p class="mb-1"><?php echo $siswa->kelas; ?></p>
            <H5 class="my-3" style="color:black"  >NISN :</H5>
            <p class="mb-4"><?php echo $siswa->nisn; ?></p>
            <div class="d-flex justify-content-center mb-2">
              <!-- <button type="button" class="btn btn-primary">Follow</button> -->
              <!-- <button type="button" class="btn btn-outline-primary ms-1">Message</button> -->
            </div>
          </div>
        </div>

        <div class="card mb-4">
          <div class="card-body text-center">
            <h1 style="text-align: center;" class="h3 mb-2 text-gray-800">Absen Hari Ini</h1>
                <?php
                $absen = $qb->RAW(
                  "SELECT * FROM rekap_absen WHERE tanggal_absen >= DATE(NOW()) and norf='".$siswa->norf."' order by tanggal_absen desc",[]);
                  // "SELECT * FROM rekap_absen WHERE norf='".$siswa->norf."' order by tanggal_absen desc",[]);
                // print_r($absen);die();
                // if(count($absen) != 0){echo 'a';}die();
                $masuk='';
                $pulang='';
                if(array_key_exists(0, $absen)){$masuk='<i class="fa-solid fa-check"></i>';}
                if(array_key_exists(1, $absen)){$pulang='<i class="fa-solid fa-check"></i>';}
                ?>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <tbody>
                      <tr>
                        <td>Masuk</td>
                        <td><?php echo $masuk;?></td>
                      </tr>
                      <tr>
                        <td>Pulang</td>
                        <td><?php echo $pulang;?></td>
                      </tr>
                    </tbody>
                </table>
          </div>
        </div>
    <!--     <div class="card mb-4 mb-lg-0">
          <div class="card-body p-0">
            <ul class="list-group list-group-flush rounded-3">
              <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <i class="fas fa-globe fa-lg text-warning"></i>
                <p class="mb-0">https://mdbootstrap.com</p>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <i class="fab fa-github fa-lg" style="color: #333333;"></i>
                <p class="mb-0">mdbootstrap</p>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <i class="fab fa-twitter fa-lg" style="color: #55acee;"></i>
                <p class="mb-0">@mdbootstrap</p>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <i class="fab fa-instagram fa-lg" style="color: #ac2bac;"></i>
                <p class="mb-0">mdbootstrap</p>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <i class="fab fa-facebook-f fa-lg" style="color: #3b5998;"></i>
                <p class="mb-0">mdbootstrap</p>
              </li>
            </ul>
          </div>
        </div> -->
      </div>
      <div class="col-lg-8">
        <div class="card mb-4">
          <div class="card-body">
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Jenis Kelamin</p>
              </div>
              <div class="col-sm-9">
                <p class="mb-0"><?php echo $siswa->jk; ?></p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">NIK</p>
              </div>
              <div class="col-sm-9">
                <p class="mb-0"><?php echo $siswa->nim; ?></p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">TTl</p>
              </div>
              <div class="col-sm-9">
                <p class="mb-0"><?php echo $siswa->tmp_lhr; ?>, <?php echo $siswa->tgl_lhr; ?></p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Alamat</p>
              </div>
              <div class="col-sm-9">
                <p class="mb-0"><?php echo $siswa->alamat; ?></p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Nama Ayah</p>
              </div>
              <div class="col-sm-9">
                <p class="mb-0"><?php echo $siswa->nm_ayah; ?></p>
              </div>
            </div>
          </div>
        </div>
         <div class="card mb-4">
          <div class="card-body">
                <?php
                // $data_saldo = $qb->RAW(
                   // "SELECT * FROM saldo_log where id_rfid=?",[$siswa->norf]);

                $saldo = $qb->RAW(
                   "SELECT * FROM saldo_rfid where id_rfid=?",[$siswa->norf]);
                if(array_key_exists(0, $saldo)){$saldo=convertToRupiah(enkripsiDekripsi(strval($saldo[0]->saldo), $kunciRahasia));}else{$saldo=convertToRupiah(0);}
                ?>
                <h1 style="text-align: center;" class="h3 mb-2 text-gray-800">Saldo Kartu : <?php echo $saldo; ?></h1>
                <h5 style="text-align:center;">Uang Masuk</h5>
                  <div class="table-responsive">
                  <table class="display table table-bordered" id="table1" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <!-- <th>Jenis</th> -->
                            <th>Jumlah</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php                             
                        $data_user = $qb->RAW(
                        "SELECT * FROM saldo_log where id_rfid=? and jenis='masuk'",[$siswa->norf]);
                        foreach ($data_user as $user) {
                            ?>
                        <tr>
                            <!-- <td><?php echo $user->jenis;?></td> -->
                            <td><?php 
                            if($user->banyak != ''){
                  echo convertToRupiah(enkripsiDekripsi(strval($user->banyak), $kunciRahasia));}
                  ?></td>
                            <td><?php echo $user->waktu;?></td>
                        </tr>
                        <?php } ?>
                        
                    </tbody>
                </table>
                </div>
                    <h5 style="text-align:center;">Uang Keluar</h5>
                <div class="table-responsive">                    
                <table class="display table table-bordered" id="table2" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <!-- <th>Jenis</th> -->
                            <th>Jumlah</th>
                            <th>Keterangan</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php                              
                        $data_user = $qb->RAW(
                        "SELECT * FROM saldo_log where id_rfid=? and jenis='keluar'",[$siswa->norf]);
                        foreach ($data_user as $user) {
                            ?>
                        <tr>
                            <!-- <td><?php echo $user->jenis;?></td> -->
                            <td><?php 
                            if($user->banyak != ''){
                            echo convertToRupiah(enkripsiDekripsi(strval($user->banyak), $kunciRahasia));}
                            ?></td>
                            <td><?php echo $user->ket;?></td>
                            <td><?php echo $user->waktu;?></td>
                        </tr>
                        <?php } ?>
                        
                    </tbody>
                </table>
                <!-- <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                      <tr>
                          <th>Transaksi</th>
                          <th>Waktu</th>
                          <th>Jumlah</th>
                          <th>Keterangan</th>
                      </tr>
                  </thead>
                    <tbody>
                        <?php 
                        foreach ($data_saldo as $user) {
                            ?>
                        <tr>
                            <td><?php echo $user->jenis;?></td>
                            <td><?php echo $user->waktu;?></td>
                            <td><?php 
                            if($user->banyak != ''){
                            echo convertToRupiah(enkripsiDekripsi(strval($user->banyak), $kunciRahasia));}
                            ?></td>
                            <td><?php echo $user->ket;?></td>
                        </tr>
                        <?php } ?>
                        
                    </tbody>
                </table> -->
              </div>
          </div>
        </div>
       <!--  <div class="row">
          <div class="col-md-6">
            <div class="card mb-4 mb-md-0">
              <div class="card-body">

               </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card mb-4 mb-md-0">
              <div class="card-body">
                 
               </div>
            </div>
          </div>
        </div>  -->
        <!-- <div class="row">
          <div class="col-md-6">
            <div class="card mb-4 mb-md-0">
              <div class="card-body">
                <p class="mb-4"><span class="text-primary font-italic me-1">assigment</span> Project Status
                </p>
                <p class="mb-1" style="font-size: .77rem;">Web Design</p>
                <div class="progress rounded" style="height: 5px;">
                  <div class="progress-bar" role="progressbar" style="width: 80%" aria-valuenow="80"
                    aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <p class="mt-4 mb-1" style="font-size: .77rem;">Website Markup</p>
                <div class="progress rounded" style="height: 5px;">
                  <div class="progress-bar" role="progressbar" style="width: 72%" aria-valuenow="72"
                    aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <p class="mt-4 mb-1" style="font-size: .77rem;">One Page</p>
                <div class="progress rounded" style="height: 5px;">
                  <div class="progress-bar" role="progressbar" style="width: 89%" aria-valuenow="89"
                    aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <p class="mt-4 mb-1" style="font-size: .77rem;">Mobile Template</p>
                <div class="progress rounded" style="height: 5px;">
                  <div class="progress-bar" role="progressbar" style="width: 55%" aria-valuenow="55"
                    aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <p class="mt-4 mb-1" style="font-size: .77rem;">Backend API</p>
                <div class="progress rounded mb-2" style="height: 5px;">
                  <div class="progress-bar" role="progressbar" style="width: 66%" aria-valuenow="66"
                    aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
            </div>
          </div> -->
         <!--  <div class="col-md-6">
            <div class="card mb-4 mb-md-0">
              <div class="card-body">
                <p class="mb-4"><span class="text-primary font-italic me-1">assigment</span> Project Status
                </p>
                <p class="mb-1" style="font-size: .77rem;">Web Design</p>
                <div class="progress rounded" style="height: 5px;">
                  <div class="progress-bar" role="progressbar" style="width: 80%" aria-valuenow="80"
                    aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <p class="mt-4 mb-1" style="font-size: .77rem;">Website Markup</p>
                <div class="progress rounded" style="height: 5px;">
                  <div class="progress-bar" role="progressbar" style="width: 72%" aria-valuenow="72"
                    aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <p class="mt-4 mb-1" style="font-size: .77rem;">One Page</p>
                <div class="progress rounded" style="height: 5px;">
                  <div class="progress-bar" role="progressbar" style="width: 89%" aria-valuenow="89"
                    aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <p class="mt-4 mb-1" style="font-size: .77rem;">Mobile Template</p>
                <div class="progress rounded" style="height: 5px;">
                  <div class="progress-bar" role="progressbar" style="width: 55%" aria-valuenow="55"
                    aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <p class="mt-4 mb-1" style="font-size: .77rem;">Backend API</p>
                <div class="progress rounded mb-2" style="height: 5px;">
                  <div class="progress-bar" role="progressbar" style="width: 66%" aria-valuenow="66"
                    aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
            </div>
          </div> -->
        </div>
      </div>
    </div>
  </div>
</section>
</body>
  
  <?php }?>

<?php require "partials/footer.php"; ?>
<script type="text/javascript">
    $(document).ready(function() {
  $('table.display').DataTable();
} );
</script>