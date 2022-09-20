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



    $table='kelas';
     $data_user = $qb->RAW(
    "SELECT * FROM user",[]);
     if(isset($_POST['cari_user'])){
        $data_siswa = $qb->RAW(
            "SELECT *,kelas.kelas as nama_kelas FROM siswa
            left join kelas on kelas.id_kelas=siswa.kelas
            left join saldo_rfid on saldo_rfid.id_rfid=siswa.norf
            where siswa.user_input=".$_POST['data_user']
            ,[]);  
    }else{
        $data_siswa = $qb->RAW(
            "SELECT *,kelas.kelas as nama_kelas FROM siswa
            left join kelas on kelas.id_kelas=siswa.kelas
            left join saldo_rfid on saldo_rfid.id_rfid=siswa.norf
            where siswa.user_input=".$_SESSION['id_user']
            ,[]);
    }

    // print_r($data_kelas);
    // die();

    require "partial/head.php";
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
                                            <th>Foto</th>
                                            <th>Nama Siswa</th>
                                            <th>NIS</th>
                                            <th>Kelas</th>
                                            <th>Saldo</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i=1;
                                        foreach ($data_siswa as $siswa) {
                                            ?>
                                        <tr>
                                            <td><img style="display: block; margin-left: auto;  margin-right: auto;   width: 70px;" class="img-responsive img" src="asset/foto/<?php if(!empty($siswa->foto)){echo $siswa->foto;}else{echo 'blank.png';}?>?time=<?php echo date("H:i:s");?>"></td>
                                            <td><?php echo $siswa->nama;?></td>
                                            <td><?php echo $siswa->nis;?></td>
                                            <td><?php echo $siswa->nama_kelas;?></td>
                                            <td><?php 
                                            if($siswa->saldo != ''){
                                            echo convertToRupiah(enkripsiDekripsi(strval($siswa->saldo), $kunciRahasia));}
                                            ?></td>
                                            <td>
                                            <center>
                                                <a href="#" data-toggle="modal" data-target="#logoutModal<?php echo $i; ?>" ><i class="fa-solid fa-wand-magic-sparkles"></i></a>
                                                <div class="modal-dialog" role="document">
            <!-- Logout Modal-->
    <div class="modal fade " id="logoutModal<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" style="width:70%;" id="alasan<?php echo $i; ?>" placeholder="Alasan" required>
                        <input type="hidden" class="form-control" style="width:70%;" id="norf<?php echo $i; ?>" readonly value="<?php echo $siswa->norf;?>">
                        <input type="checkbox" id="telat<?php echo $i; ?>" value="1"> Telat
                    </div>
                    <div class="p-3 mb-2 text-white" id="tampilMessage<?php echo $i; ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" onclick="izin(<?php echo $i; ?>)" name="izin" style="color: white;" type="submit">Izin</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
  
  <script type="text/javascript">
      // jika ada perubahan di input field [ENTER], akan mentrigger
      function izin(no) {
        telat=0;
         if (document.getElementById("telat"+no).checked == true){
            // console.log('a');
            telat=1;
          }
            // return;     
        if($("#alasan"+no).val() == ''){
              $('#tampilMessage'+no).addClass('bg-danger').html('Alasan Kosong');
              return;
        }
        // return;
        $.ajax({
            url: 'input.php',
            type: 'post',
            data: {
              id: $("#norf"+no).val(),
              izin: $("#alasan"+no).val(),
              telat: telat
            }
          })
          .done(function(data) {
            console.log(data);
            // return;

            // hapus alert danger dan sukses agar bisa bergantian class
            // $('.alert').removeClass('alert-danger alert-success');
            $('#tampilMessage'+no).removeClass('bg-danger bg-success');

            if (data.match(/Nama.*/)) {
              // $('.alert').addClass('alert-success').html(data);
              // $('#classInformation').html("Class Information").addClass('display-4');
              $('#tampilMessage'+no).addClass('bg-success').html(data);
              
              $("#alasan"+no).val('');

            } else {
              // $('.alert').addClass('alert-danger').html("RFID belum terdaftar di dalam system kami: " + "<b>{ " + id + " }</b>");
              // $('#classInformation').html("Whoops, there was an error").addClass('display-4');
              $('#tampilMessage'+no).addClass('bg-danger').html(data);
            }


          })
          .fail(function(data) {
            console.log(data);
          });
      };

      $('.close').click(function(){
        $('#tampilMessage').removeClass('bg-danger bg-success');
      });

    </script>
                                            </center>
                                            </td>
                                        </tr>
                                        <?php $i++;} ?>
                                        
                                    </tbody>
                                </table>


                            </div>

   
                        </div>
                    </div>




    <?php 


    if(isset($_GET['hapus_jadwal'])){
        $aksi = $qb->RAW(
        "DELETE from rekap_absen where id=".$_GET['hapus_jadwal'],[]);
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
        echo '<script>setTimeout(function(){location.replace("izin_siswa.php"); }, 1000);</script>';
    }

    $absen = $qb->RAW(
            "SELECT rekap_absen.*,siswa.nama,siswa.nis,kelas.kelas
            FROM rekap_absen
            left join siswa on rekap_absen.norf=siswa.norf
            left join kelas on kelas.id_kelas=siswa.kelas
            where date(rekap_absen.tanggal_absen)=date(NOW()) and rekap_absen.ket != '' and siswa.user_input=".$_SESSION['id_user']
            ,[]);  
    ?>

    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Data Inputan Hari Ini</h1>
                    
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
                                            <th>Status</th>
                                            <th>Ket</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i=1;
                                        foreach ($absen as $siswa) {
                                            ?>
                                        <tr>
                                            <td><?php echo $siswa->nama;?></td>
                                            <td><?php echo $siswa->nis;?></td>
                                            <td><?php echo $siswa->kelas;?></td>
                                            <td><?php 
                                                if($siswa->telat == 1){
                                                    echo 'Terlambat';
                                                }else{echo 'izin';}
                                            ?></td>
                                            <td><?php echo $siswa->ket;?></td>
                                            <td><center>
                                                
                                                <a href="#" data-toggle="modal" data-target="#logoutModal1<?php echo $i; ?>" ><i class="fa-solid fa-trash-can"></i></a>
                                                <div class="modal-dialog" role="document">
            <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal1<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Peringatan</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Apa anda yakin untuk Hapus?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="izin_siswa.php?hapus_jadwal=<?php echo $siswa->id;?>">Hapus</a>
                </div>
            </div>
        </div>
    </div>
     </center>
                                            </td>
                                        </tr>
                                        <?php $i++;} ?>
                                        
                                    </tbody>
                                </table>


                            </div>
                            </div>

   
                        </div>
                    </div>


                </div>
                <!-- /.container-fluid -->




    

     <?php require "partials/footer.php"; ?>