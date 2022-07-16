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
		// 	session_destroy();
		// 	session_unset();
		// 	header("Location: index.php");
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
             ?>
			<div class="row">
				<div class="col-md-2">
<h2> <?=$HARI[$sekarang]; ?> </h2>
				</div>
				<div class="col-md-10">
					<?php
                    $cariMakulabsen = $qb->RAW("SELECT * FROM jadwal where hari = ?", [$HARI[$sekarang]]);
                    foreach ($cariMakulabsen as $key => $value) {
                        $mulai = Carbon::parse($value->jam_mulai, 'Asia/Jakarta')->hour;
                        $mulaiMenit = Carbon::parse($value->jam_mulai, 'Asia/Jakarta')->addminutes(15);

                        $akhir = Carbon::parse($value->jam_akhir, 'Asia/Jakarta')->hour;
                        $sekarang = Carbon::now('Asia/Jakarta')->hour ;

                        if ($sekarang > $mulai && $sekarang < $akhir) { //10 > 8 && 10 < 12
                            $makul = "<span class=\"badge badge-success float-md-right\">Kelas Tersedia</span>";
                            break;
                        } else {
                            $makul = "<span class=\"badge badge-danger float-md-right\">Kelas Tidak Tersedia</span>";
                        }
                    }
                    if(!empty($makul)){
                    echo "<h2>{$makul}</h2>";
                  	}else{
                  	echo "<h2><span class=\"badge badge-danger float-md-right\">Kelas Tidak Tersedia</span></h2>";
                  	}
                     ?>
                  	<!-- } -->
				</div>
			</div>
		</div>

		<table class="table table-striped table-responsive">
			<thead>
				<tr>
					<th>#</th>
					<!-- <th>Mata kuliah</th> -->
					<th>Jam mulai</th>
					<th>Jam berakhir</th>
				</tr>
			</thead>
			<tbody>
				<?php
        $i = 1;
        foreach ($hasil as $key => $value):?>
				<tr>
					<th><?= $i?></th>
					<!-- <td><?= $value->makul;?></td> -->
					<td><?= $value->jam_mulai;?></td>
					<td><?= $value->jam_akhir;?></td>
				</tr>
			<?php $i++;endforeach; ?>
			</tbody>
		</table>

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
  $("input").change(function() {
    var id = $('#inputs').val();

    $.ajax({
        url: 'input.php',
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
