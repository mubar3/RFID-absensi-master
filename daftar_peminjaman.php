<?php
require "partials/head.php";
require "partials/sidebar.php"; ?>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha256-aAr2Zpq8MZ+YA/D6JtRD3xtrwpEz2IqOS+pWD/7XKIw=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" integrity="sha512-xmGTNt20S0t62wHLmQec2DauG9T+owP9e6VU8GigI0anN7OXLip9i7IwEhelasml2osdxX71XcYm6BQunTQeQg==" crossorigin="anonymous" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha256-OFRAJNoaD8L3Br5lglV7VyLRf0itmoBzWUoM+Sji4/8=" crossorigin="anonymous"></script> -->
     <style type="text/css">
        .bootstrap-tagsinput{
            width: 100%;
        }
        .label-info{
            background-color: #17a2b8;

        }
        .label {
            display: inline-block;
            padding: .25em .4em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25rem;
            transition: color .15s ease-in-out,background-color .15s ease-in-out,
            border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }
    </style>

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


    ?>
    <h2 class="text-primary mt-4">Kunjungan Perpustakaan </h2>

<div class="form-group">
    <label for="rfidnumber">RFID Tag Number</label>
    <input type="text" class="form-control" id="inputs_k" aria-describedby="rfidnumber" placeholder="RFID Number will shown here">
    <small id="rfidnumber" class="form-text text-muted">This System Automatically Record Your Book</small>
</div>

<div class="container mb-4">
    <h3 id="classInformation"></h3>
    <div class="p-3 mb-2 text-white" id="tampilMessagek">
        <!-- <b>Name</b> : Daniel Aditama <b>Course</b> : ERP Planning <b>Date/Time</b> : Mon,9-10-17/07:59:59 <b>Status</b>: Early -->
    </div>
    <div class="alert" role="alert"></div>
</div>

<!-- Page Heading -->
            <h1 class="h3 mb-2 text-gray-800">Data Pengunjung Hari Ini</h1>

            <!-- DataTales Example -->
            <div class="card shadow mb-4" >
                <div class="card-body" id='here_k'>
                  <?php
                    $data_buku = $qb->RAW(
                    "SELECT * FROM kunjungan
                    join siswa on siswa.norf=kunjungan.siswa
                    where kunjungan.user=? and DATE(kunjungan.tanggal) = CURDATE()
                    ",[$_SESSION['id_user']]);
                  ?>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Siswa</th>
                                    <th>NIS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                foreach ($data_buku as $buku) {
                                    ?>
                                <tr>
                                    <td><?php echo $buku->nama;?></td>
                                    <td><?php echo $buku->nis;?></td>
                                </tr>
                                <?php } ?>
                                
                            </tbody>
                        </table>
                    </div>
                    </div>

                </div>
            </div>
                <!-- /.container-fluid -->


    <?php require "partial/footer.php"; ?>
    <script type="text/javascript">
        var timestamp = "<?=date('H:i:s');?>";

        function updateTime() {
            $('#time').html(Date(timestamp));
            timestamp++;
        }
        function updateDiv()
        { 
            $( "#here" ).load(window.location.href + " #here" );
        }
        $(function() {
            setInterval(updateTime, 1000);
        });

$(document).ready(function() {		
    $("#inputs_k").change(function() {
    var id = $('#inputs_k').val();
    $.ajax({
        url: 'aksi_perpus_k.php',
        type: 'post',
        data: {
          id: id
        }
      })
      .done(function(data) {
        console.log(data);

        // hapus alert danger dan sukses agar bisa bergantian class
        // $('.alert').removeClass('alert-danger alert-success');
        $('#tampilMessagek').removeClass('bg-danger bg-success');

        if (data.match(/Berhasil.*/)) {
          // $('.alert').addClass('alert-success').html(data);
          // $('#classInformation').html("Class Information").addClass('display-4');
          $('#tampilMessagek').addClass('bg-success').html(data);
        } else {
          // $('.alert').addClass('alert-danger').html("RFID belum terdaftar di dalam system kami: " + "<b>{ " + id + " }</b>");
          // $('#classInformation').html("Whoops, there was an error").addClass('display-4');
          $('#tampilMessagek').addClass('bg-danger').html(data);
        }

        $('#inputs_k').val(""); //Mengkosongkan input field
        // $('#books').val("");
        // $('#books').tagsinput('focus');
        // $('#inputs').focus(); //mengembalikan cursor ke input field
        // setTimeout(location.reload.bind(location), 1200);
      })
      .fail(function(data) {
        console.log(data);
      });

      updateDiv_k()
  });

  // Input field langsung fokus
  // $('#books').focus();
  // $('#books').tagsinput('focus');

  // jika ada perubahan di input field [ENTER], akan mentrigger
});

        
    </script>
     <?php require "partials/footer.php"; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.js" integrity="sha512-VvWznBcyBJK71YKEKDMpZ0pCVxjNuKwApp4zLF3ul+CiflQi6aIJR+aZCP/qWsoFBA28avL5T5HA+RE+zrGQYg==" crossorigin="anonymous"></script>