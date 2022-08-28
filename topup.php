
	<?php require "partials/head.php";
	require "partials/sidebar.php"; ?>
	<div class="container"><span class="float-md-right"><i class="fa fa-clock-o" aria-hidden="true"></i><st id="time"></st></span>
		<?php 
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
		<h2 class="text-primary mt-4">TopUp Saldo </h2>

		<div class="form-group">
			<label for="rfidnumber">Banyak (Rp)</label>
			<input class="form-control" id="rp" aria-describedby="rfidnumber" placeholder="Rp.">
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
  $("#inputs").change(function() {
    var id = $('#inputs').val();
    var isi = $('#rp').val();
    isi=isi.replace(/Rp. /g,'')
		isi=isi.replace(/[.,\/#!$%\^&\*;:{}=\-_`~()]/g,'')

    $.ajax({
        url: 'aksitopup.php',
        type: 'post',
        data: {
          id: id,
          isi: isi
        }
      })
      .done(function(data) {
        // console.log(data);

        // hapus alert danger dan sukses agar bisa bergantian class
        // $('.alert').removeClass('alert-danger alert-success');
        $('#tampilMessage').removeClass('bg-danger bg-success');

        // if (data == "err") {
          // $('.alert').addClass('alert-danger').html("RFID belum terdaftar di dalam system kami: " + "<b>{ " + id + " }</b>");
          // $('#classInformation').html("Whoops, there was an error").addClass('display-4');
          // $('#tampilMessage').html("RFID is not yet registered in our system: " + "<b>{ " + id + " }</b>");
        // } else {
          // $('.alert').addClass('alert-success').html(data);
          // $('#classInformation').html("Class Information").addClass('display-4');
          $('#tampilMessage').html(data);
        // }

        $('#inputs').val(""); //Mengkosongkan input field
        $('#inputs').focus(); //mengembalikan cursor ke input field

      })
      .fail(function(data) {
        // console.log(data);
      });
  });
});

		var rupiah = document.getElementById('rp');
		rupiah.addEventListener('keyup', function(e){
			// tambahkan 'Rp.' pada saat form di ketik
			// gunakan fungsi formatRupiah() untuk mengubah angka yang di ketik menjadi format angka
			rupiah.value = formatRupiah(this.value, 'Rp. ');
		});
 
		/* Fungsi formatRupiah */
		function formatRupiah(angka, prefix){
			var number_string = angka.replace(/[^,\d]/g, '').toString(),
			split   		= number_string.split(','),
			sisa     		= split[0].length % 3,
			rupiah     		= split[0].substr(0, sisa),
			ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
 
			// tambahkan titik jika yang di input sudah menjadi angka ribuan
			if(ribuan){
				separator = sisa ? '.' : '';
				rupiah += separator + ribuan.join('.');
			}
 
			rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
			return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
		}
	</script>
<?php require "partials/footer.php"; ?>
