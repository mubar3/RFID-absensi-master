<!-- <!DOCTYPE html>
<html>
<body> -->
    <?php 
    // require "partial/nav.php"; 
    require "partials/head.php";
    require "partials/sidebar.php";
    ?>
    <div class="container"><span class="float-md-right"><i class="fa fa-clock-o" aria-hidden="true"></i><st id="time"></st></span>
        <?php if(!empty($_SESSION['id_user'])){
        // if($_SESSION['role']!=1){
        //  session_destroy();
        //  session_unset();
        //  header("Location: index.php");
        // } 
    }else{
        header("Location: index.php");
    } ?>
        <h2 class="text-primary mt-4">Silahkan Tap kartu RFID </h2>

        <div class="form-group">
            <label for="rfidnumber">Kartu RFID</label>
            <input type="text" class="form-control" id="inputs" aria-describedby="rfidnumber" placeholder="RFID">
            <small id="rfidnumber" class="form-text text-muted">Absensi RFID</small>
        </div>

        <div class="container mb-4">
            <h3 id="classInformation"></h3>
            <div class="p-3 mb-2 text-white" id="tampilMessage">
                <!-- <b>Name</b> : Daniel Aditama <b>Course</b> : ERP Planning <b>Date/Time</b> : Mon,9-10-17/07:59:59 <b>Status</b>: Early -->
            </div>
            <div class="alert" role="alert"></div>
        </div>

        <div class="container">
            <?php
            require "vendor/autoload.php";
            use StelinDB\Database\Connection;
            use StelinDB\Database\QueryBuilder;
            use Carbon\Carbon;

            $dotenv = new \Dotenv\Dotenv(__DIR__);
                        $dotenv->load();
            $qb = new QueryBuilder(Connection::Connect());
            $HARI = [
                   0 => "Minggu",
                   1 => "Senin",
                   2 => "Selasa",
                   3 => "Rabu",
                   4 => "Kamis",
                   5 => "Jumat",
                   6 => "Sabtu"
                 ];
            $sekarang = Carbon::now('Asia/Jakarta')->dayOfWeek;
            $hasil = $qb->RAW("SELECT * from jadwal where id_user=".$_SESSION['id_user']." and hari = ?", [$HARI[$sekarang]]);
             
        $tanggal='Hari Ini';
        if(isset($_POST['cari_parkir'])){$tanggal='';}
        ?>

    <h1 class="h3 mb-2 text-gray-800">Riwayat <?php echo $tanggal; ?></h1>
    <div class="col-lg-12 mb-2">
        <form  role="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
        <div class="input-group">
             <span class="input-group-text" id="basic-addon1">Dari</span>
            <input type="date" class="form-control" value="<?php echo $_POST['tanggal_awal'];?>" name="tanggal_awal">
             <span class="input-group-text" id="basic-addon1">Sampai</span>
            <input type="date" class="form-control" value="<?php echo $_POST['tanggal_akhir'];?>" name="tanggal_akhir">
            <button type="submit" name="cari_parkir" class="input-group-text"><span  id="">cari</span></button>
        </div>
        </form>
    </div>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>NIS</th>
                        <th>Kelas</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <?php
                    if(isset($_POST['cari_parkir'])){
                    $parkir_log = $qb->RAW("select *
                        from rekap_parkir
                        left join siswa on rekap_parkir.norf=siswa.norf
                        left join kelas on siswa.kelas=kelas.id_kelas
                        where siswa.user_input='".$_SESSION['id_user']."' and DATE(rekap_parkir.input) between '".$_POST['tanggal_awal']."' and '".$_POST['tanggal_akhir']."'
                        group by rekap_parkir.norf",[]);
                    }else{
                    $parkir_log = $qb->RAW("select *
                        from rekap_parkir
                        left join siswa on rekap_parkir.norf=siswa.norf
                        left join kelas on siswa.kelas=kelas.id_kelas
                        where siswa.user_input='".$_SESSION['id_user']."' and DATE(rekap_parkir.input) = CURDATE() 
                        group by rekap_parkir.norf",[]);
                    }
                ?>
                <tbody>

                    <?php foreach ($parkir_log as $log) { ?>
                    <tr>
                        <td><?php echo $log->nama; ?></td>
                        <td><?php echo $log->nis; ?></td>
                        <td><?php echo $log->kelas; ?></td>
                        <td><?php echo $log->input; ?></td>
                    </tr>
                    `<?php } ?>
                </tbody>
            </table>
            <h5></h5>
            </div>
        </div>
    </div>


    </div>
    <?php require "partial/footer.php"; ?>
    <script type="text/javascript">
        var timestamp = "<?=date('H:i:s');?>";

        function updateTime() {
            $('#time').html(Date(timestamp));
            timestamp++;
        }
        $(function() {
            setInterval(updateTime, 1000);
        });

$(document).ready(function() {

  // Input field langsung fokus
  $('#inputs').focus();

  // jika ada perubahan di input field [ENTER], akan mentrigger
  $("#inputs").change(function() {
    var id = $('#inputs').val();

    $.ajax({
        url: 'aksi_parkir.php',
        type: 'post',
        data: {
          id: id
        }
      })
      .done(function(data) {
        console.log(data);

        // hapus alert danger dan sukses agar bisa bergantian class
        // $('.alert').removeClass('alert-danger alert-success');
        $('#tampilMessage').removeClass('bg-danger bg-success');

        if (data.match(/Nama.*/)) {
          // $('.alert').addClass('alert-success').html(data);
          // $('#classInformation').html("Class Information").addClass('display-4');
          $('#tampilMessage').addClass('bg-success').html(data);
        } else {
          // $('.alert').addClass('alert-danger').html("RFID belum terdaftar di dalam system kami: " + "<b>{ " + id + " }</b>");
          // $('#classInformation').html("Whoops, there was an error").addClass('display-4');
          $('#tampilMessage').addClass('bg-danger').html(data);
        }

        $('#inputs').val(""); //Mengkosongkan input field
        $('#inputs').focus(); //mengembalikan cursor ke input field

      })
      .fail(function(data) {
        console.log(data);
      });
  });
});
    </script>

    <?php require "partials/footer.php"; ?>
<!-- </body>

</html> -->
