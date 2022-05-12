<!DOCTYPE html>
<html>
<body>
	<?php require "partial/nav.php"; ?>
	<div class="container"><span class="float-md-right"><i class="fa fa-clock-o" aria-hidden="true"></i><st id="time"></st></span>
		<h2 class="text-primary mt-4">Put Your RFID Card to Your Scanner </h2>
		<?php
		require "vendor/autoload.php";

use StelinDB\Database\QueryBuilder;
use Carbon\Carbon;

$dotenv = new \Dotenv\Dotenv(__DIR__);
            $dotenv->load();
$now = new Carbon;
$now->setTimezone('Asia/Jakarta');

$qb = new QueryBuilder(\StelinDB\Database\Connection::Connect());

$id_user= $_SESSION['id_user'];

    $user = $qb->RAW(
    "SELECT * FROM user WHERE id_user=?",[$id_user]);
    $user = $user[0];

$saldo = $user->saldo;    
$saldo = enkripsiDekripsi($saldo, $kunciRahasia);

?>
		<h6 for="rfidnumber">Saldo Akun Anda <?php echo $saldo;?></h6>
		<br>

		<?php if(!empty($_SESSION['id_user'])){
		if($_SESSION['role']!=3){
			session_destroy();
			session_unset();
			header("Location: index.php");
		} 
	}else{
		header("Location: index.php");
	} ?>

		<div class="form-group">
			<label for="rfidnumber">RFID Tag Number</label>
			<input type="text" class="form-control" id="inputs" aria-describedby="rfidnumber" placeholder="RFID Number will shown here">
			<small id="rfidnumber" class="form-text text-muted">This System Automatically Record Your Abscence</small>
		</div>

		<div class="container mb-4">
			<h3 id="classInformation"></h3>
			<div class="p-3 mb-2 text-white" id="tampilMessage">
				<!-- <b>Name</b> : Daniel Aditama <b>Course</b> : ERP Planning <b>Date/Time</b> : Mon,9-10-17/07:59:59 <b>Status</b>: Early -->
			</div>
			<div class="alert" role="alert"></div>
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
  $("input").change(function() {
    var id = $('#inputs').val();

    $.ajax({
        url: 'aksicek_saldo.php',
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

        if (data == "err") {
          // $('.alert').addClass('alert-danger').html("RFID belum terdaftar di dalam system kami: " + "<b>{ " + id + " }</b>");
          $('#classInformation').html("Whoops, there was an error").addClass('display-4');
          $('#tampilMessage').addClass('bg-danger').html("RFID is not yet registered in our system: " + "<b>{ " + id + " }</b>");
        } else {
          // $('.alert').addClass('alert-success').html(data);
          $('#classInformation').html("Class Information").addClass('display-4');
          $('#tampilMessage').addClass('bg-success').html(data);
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
</body>

</html>
