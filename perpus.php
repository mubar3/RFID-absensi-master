
	<?php require "partials/head.php";
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
	<div class="container"><span class="float-md-right"><i class="fa fa-clock-o" aria-hidden="true"></i><st id="time"></st></span><br>
		<?php 
    require "vendor/autoload.php";

    use StelinDB\Database\QueryBuilder;
    use Carbon\Carbon;

    $dotenv = new \Dotenv\Dotenv(__DIR__);
                $dotenv->load();
    $now = new Carbon;
    $now->setTimezone('Asia/Jakarta');

    $qb = new QueryBuilder(\StelinDB\Database\Connection::Connect());


		if(!empty($_SESSION['id_user'])){
		// if($_SESSION['role']!=2){
		// 	session_destroy();
		// 	session_unset();
		// 	header("Location: index.php");
		// } 
	}else{
		header("Location: index.php");
	} 
	?>
    <script type="text/javascript">
    function Check() {
        if (document.getElementById('yesCheck').checked) {
            document.getElementById('ifYes').style.display = 'block';
            document.getElementById('ifNo').style.display = 'none';
        } 
        else {
            document.getElementById('ifYes').style.display = 'none';
            document.getElementById('ifNo').style.display = 'block';

       }
    }

    </script>
        <input type="radio" onclick="Check();" class="" value="1" name="pakai_resep[]" checked><label>Peminjaman</label> 
        <input type="radio" onclick="Check();" class="" id="yesCheck" value="0" name="pakai_resep[]"><label>Pengembalian</label>
    <div id="ifYes" style="display:none">
		<?php 
        if(isset($_GET['peminjaman'])){
            
            $aksi = $qb->RAW(
            "UPDATE peminjaman SET 
            status=1,pengembalian=? where id_peminjaman=".$_GET['peminjaman'],[date("Y-m-d H:i:s")]);

            $data_rfid_buku = $qb->RAW("SELECT * FROM peminjaman where id_peminjaman=?",[$_GET['peminjaman']]);
            $isi=$data_rfid_buku[0]->buku;
            $data_buku=explode(',', $isi);
            $total_buku=count($data_buku);
                for($x=0;$x<$total_buku;$x++) {
                    $qb->RAW("UPDATE buku set pinjam=0 where rfid=?",[$data_buku[$x]]);
                }
            
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
    if(isset($_GET['hapus_peminjaman'])){
        
        $data_rfid_buku = $qb->RAW("SELECT * FROM peminjaman where id_peminjaman=?",[$_GET['hapus_peminjaman']]);
        $isi=$data_rfid_buku[0]->buku;
        $data_buku=explode(',', $isi);
        $total_buku=count($data_buku);
        for($x=0;$x<$total_buku;$x++) {
            $qb->RAW("UPDATE buku set pinjam=0 where rfid=?",[$data_buku[$x]]);
        }
        
        $aksi = $qb->RAW(
        "DELETE from  peminjaman where id_peminjaman=".$_GET['hapus_peminjaman'],[]);
            
            if($aksi){
                echo '<div class="col-lg-12 mb-4">
                    <div class="card bg-success text-white shadow">
                        <div class="card-body">
                            Berhasil
                            <div class="text-white-50 small">Berhasil Hapus</div>
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
            echo '<script>setTimeout(function(){location.replace("daftar_peminjaman.php"); }, 1000);</script>';
          
        }

    $table='kelas';
    // print_r($data_kelas);
    // die();

    ?>
                    <h2 class="text-primary mt-4">Pengembalian Perpus </h2>

        <div class="form-group">
            <label for="rfidnumber">RFID Buku</label>
            <input type="text" id="books_p" class="form-control" data-role="tagsinput"  name="tags" class="form-control">
            <label for="rfidnumber">RFID Tag Number</label>
            <input type="text" class="form-control" id="inputs_p" aria-describedby="rfidnumber" placeholder="RFID Number will shown here">
            <small id="rfidnumber" class="form-text text-muted">This System Automatically Record Your Book</small>
        </div>

        <div class="container mb-4">
            <h3 id="classInformation"></h3>
            <div class="p-3 mb-2 text-white" id="tampilMessage_p">
                <!-- <b>Name</b> : Daniel Aditama <b>Course</b> : ERP Planning <b>Date/Time</b> : Mon,9-10-17/07:59:59 <b>Status</b>: Early -->
            </div>
            <div class="alert" role="alert"></div>
        </div>

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Data Peminjaman Buku (Belum Dikembalikan)</h1>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4" id='here'>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="display table table-bordered" id="table1" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Peminjam</th>
                                            <th>Buku</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id='here'>
                                        <?php
                                            $data_buku = $qb->RAW(
                                            "SELECT * FROM peminjaman
                                            join siswa on siswa.norf=peminjaman.peminjam
                                            where peminjaman.status=0
                                            and peminjaman.user=?
                                            ",[$_SESSION['id_user']]);
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
                                            $i=1;
                                            for($x=0;$x<$total_buku;$x++){
                                                $nama_buku = $qb->RAW(
                                                    "SELECT * FROM buku where rfid=?
                                                    ",[$data[$x]]);
                                                echo '<li>'.$nama_buku[0]->judul_buku.'</li>';
                                            }
                                            ?>  
                                            </ul>  
                                            </td>
                                            <td>
                                            <center>
                                                <?php if($_SESSION['role'] != 3){?>
                                                <a href="daftar_peminjaman.php?peminjaman=<?php echo $buku->id_peminjaman;?>" title="Telah Dikembalikan"><i class="fa-solid fa-check"></i></a>
                                                &nbsp
                                                <?php } ?>
                                                <a href="#" data-toggle="modal" data-target="#logoutModal<?php echo $i; ?>" ><i class="fa-solid fa-trash-can"></i></a>
                                            <!-- <div class="modal-dialog" role="document"> -->
                                                    <!-- Logout Modal-->
                                            <div class="modal fade" id="logoutModal<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                                                            <a class="btn btn-primary" href="daftar_peminjaman.php?hapus_peminjaman=<?php echo $buku->id_peminjaman;?>">Hapus</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- </div> -->
                                            </center>
                                            </td>
                                        </tr>
                                        <?php $i++;} ?>
                                        
                                    </tbody>
                                </table>
                            </div>
    
                        </div>
                    </div>

                <!-- </div> -->
        </div>

        <div id="ifNo" style="display:block;">
        <h2 class="text-primary mt-4">Peminjaman Perpustakaan </h2>

		<div class="form-group">
			<label for="rfidnumber">RFID Buku</label>
			<input type="text" id="books" class="form-control" data-role="tagsinput"  name="tags" class="form-control">
			<label for="rfidnumber">RFID Tag Number</label>
			<input type="text" class="form-control" id="inputs" aria-describedby="rfidnumber" placeholder="RFID Number will shown here">
			<small id="rfidnumber" class="form-text text-muted">This System Automatically Record Your Book</small>
		</div>

		<div class="container mb-4">
			<h3 id="classInformation"></h3>
			<div class="p-3 mb-2 text-white" id="tampilMessage">
				<!-- <b>Name</b> : Daniel Aditama <b>Course</b> : ERP Planning <b>Date/Time</b> : Mon,9-10-17/07:59:59 <b>Status</b>: Early -->
			</div>
			<div class="alert" role="alert"></div>
		</div>

    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Data Peminjaman Buku Hari Ini</h1>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4" >
                        <?php
                        if(isset($_GET['hapus_peminjaman'])){
            
                              $aksi = $qb->RAW(
                              "DELETE from  peminjaman where id_peminjaman=".$_GET['hapus_peminjaman'],[]);
                              
                              if($aksi){
                                  echo '<div class="col-lg-12 mb-4">
                                      <div class="card bg-success text-white shadow">
                                          <div class="card-body">
                                              Berhasil
                                              <div class="text-white-50 small">Berhasil Hapus</div>
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
                              echo '<script>setTimeout(function(){location.replace("perpus.php"); }, 1000);</script>';
                            
                          }
                        ?>
                        <div class="card-body" id='here'>
                          <?php
                            $data_buku = $qb->RAW(
                            "SELECT * FROM peminjaman
                            join siswa on siswa.norf=peminjaman.peminjam
                            where peminjaman.status=0
                            and peminjaman.user=? and DATE(peminjaman.tanggal) = CURDATE()
                            ",[$_SESSION['id_user']]);
                          ?>
                            <div class="table-responsive">
                                <table class="display table table-bordered" id="table2" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Peminjam</th>
                                            <th>Buku</th>
                                            <th>Aksi</th>
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
                                            $i=1;
                                            for($x=0;$x<$total_buku;$x++){
                                                $nama_buku = $qb->RAW(
                                                    "SELECT * FROM buku where rfid=?
                                                    ",[$data[$x]]);
                                                echo '<li>'.$nama_buku[0]->judul_buku.'</li>';
                                            }
                                            ?>  
                                            </ul>  
                                            </td>
                                            <!-- <td>
                                            <center>
                                                <a href="daftar_peminjaman.php?peminjaman=<?php echo $buku->id_peminjaman;?>" title="Telah Dikembalikan"><i class="fa-solid fa-check"></i> Telah Dikembalikan</a>
                                            </center>
                                            </td> -->
                                            <td>
                                            <center>
                                                <a href="faktur_perpus.php?id=<?php echo $buku->id_peminjaman;?>" target="_blank"><i class="fa-solid fa-print"></i></a>
                                                &nbsp
                                                <a href="#" data-toggle="modal" data-target="#logoutModal<?php echo $i; ?>" ><i class="fa-solid fa-trash-can"></i></a>
                                                <div class="modal-dialog" role="document">
            <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                    <a class="btn btn-primary" href="perpus.php?hapus_peminjaman=<?php echo $buku->id_peminjaman;?>">Hapus</a>
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
    function updateDiv_k()
    { 
        $( "#here_k" ).load(window.location.href + " #here_k" );
    }
		$(function() {
			setInterval(updateTime, 1000);
		});

$(document).ready(function() {

  // Input field langsung fokus
  // $('#books').focus();
  // $('#books').tagsinput('focus');

  // jika ada perubahan di input field [ENTER], akan mentrigger
  $("#inputs").change(function() {
    var id = $('#inputs').val();
    var buku = $('#books').val();
    // alert(buku);
    // return;
    $.ajax({
        url: 'aksi_perpus.php',
        type: 'post',
        data: {
          id: id,
          isi: buku
        }
      })
      .done(function(data) {
        console.log(data);

        // hapus alert danger dan sukses agar bisa bergantian class
        // $('.alert').removeClass('alert-danger alert-success');
        $('#tampilMessage').removeClass('bg-danger bg-success');

        if (data.match(/Berhasil.*/)) {
          // $('.alert').addClass('alert-success').html(data);
          // $('#classInformation').html("Class Information").addClass('display-4');
          $('#tampilMessage').addClass('bg-success').html(data);
        } else {
          // $('.alert').addClass('alert-danger').html("RFID belum terdaftar di dalam system kami: " + "<b>{ " + id + " }</b>");
          // $('#classInformation').html("Whoops, there was an error").addClass('display-4');
          $('#tampilMessage').addClass('bg-danger').html(data);
        }

        $('#inputs').val(""); //Mengkosongkan input field
        $("#books").tagsinput('removeAll');
        // $('#books').val("");
        // $('#books').tagsinput('focus');
        // $('#inputs').focus(); //mengembalikan cursor ke input field
        // setTimeout(location.reload.bind(location), 1200);
      })
      .fail(function(data) {
        console.log(data);
      });

      updateDiv()
  });

  $("#inputs_p").change(function() {
    var id = $('#inputs_p').val();
    var buku = $('#books_p').val();
    // alert(buku);
    // return;
    $.ajax({
        url: 'aksi_perpus_balik.php',
        type: 'post',
        data: {
          id: id,
          isi: buku
        }
      })
      .done(function(data) {
        console.log(data);

        // hapus alert danger dan sukses agar bisa bergantian class
        // $('.alert').removeClass('alert-danger alert-success');
        $('#tampilMessage_p').removeClass('bg-danger bg-success');

        if (data.match(/Berhasil.*/)) {
          // $('.alert').addClass('alert-success').html(data);
          // $('#classInformation').html("Class Information").addClass('display-4');
          $('#tampilMessage_p').addClass('bg-success').html(data);
        } else {
          // $('.alert').addClass('alert-danger').html("RFID belum terdaftar di dalam system kami: " + "<b>{ " + id + " }</b>");
          // $('#classInformation').html("Whoops, there was an error").addClass('display-4');
          $('#tampilMessage_p').addClass('bg-danger').html(data);
        }

        $('#inputs_p').val(""); //Mengkosongkan input field
        $("#books_p").tagsinput('removeAll');
        // $('#books').val("");
        // $('#books').tagsinput('focus');
        // $('#inputs').focus(); //mengembalikan cursor ke input field
        // setTimeout(location.reload.bind(location), 1200);
      })
      .fail(function(data) {
        console.log(data);
      });
      updateDiv()
  });
  $('table.display').DataTable();
});

		
	</script>
<?php require "partials/footer.php"; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.js" integrity="sha512-VvWznBcyBJK71YKEKDMpZ0pCVxjNuKwApp4zLF3ul+CiflQi6aIJR+aZCP/qWsoFBA28avL5T5HA+RE+zrGQYg==" crossorigin="anonymous"></script>
