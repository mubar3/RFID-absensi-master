
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
	<div class="container"><span class="float-md-right"><i class="fa fa-clock-o" aria-hidden="true"></i><st id="time"></st></span>
	<?php if(!empty($_SESSION['id_user'])){
		// if($_SESSION['role']!=3){
			// session_destroy();
			// session_unset();
			// header("Location: index.php");
		// } 
	}else{
		header("Location: index.php");
	} ?>
		<h2 class="text-primary mt-4">Debet Saldo </h2>


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
        <input type="radio" onclick="Check();" class="" id="yesCheck" value="1" name="pakai_resep[]" ><label>Manual</label>
        <input type="radio" onclick="Check();" class="" value="0" name="pakai_resep[]" checked><label>Tidak</label>
        <!-- <input type="radio" name="pakai_resep[]" checked><label>Tidak</label> -->

		<div class="form-group" id="ifYes" style="display:none">
			<label for="rfidnumber">Keperluan</label>
			<input type="text" class="form-control" id="keperluan" aria-describedby="rfidnumber" placeholder="Keperluan">
			<label for="rfidnumber">Banyak (Rp)</label>
			<input type="text" class="form-control" id="rp" aria-describedby="rfidnumber" placeholder="Rp.">
			<label for="rfidnumber">RFID Tag Number</label>
			<input type="text" class="form-control" id="inputs" aria-describedby="rfidnumber" placeholder="RFID Number will shown here">
			<small id="rfidnumber" class="form-text text-muted">This System Automatically Record Your Abscence</small>
		</div>

		<div class="form-group" id="ifNo" style="display:block">
			<div class="card shadow mb-4">
        <div class="card-body">
						<center><h5>Daftar Barang</h5></center>
					<div class="row datas-barang">
						<?php
						require "vendor/autoload.php";

						use StelinDB\Database\QueryBuilder;
						use Carbon\Carbon;

						$dotenv = new \Dotenv\Dotenv(__DIR__);
						            $dotenv->load();
						$now = new Carbon;
						$now->setTimezone('Asia/Jakarta');

						$qb = new QueryBuilder(\StelinDB\Database\Connection::Connect());

							$data_menu = $qb->RAW(
    						"SELECT * FROM toko_menu where id_user=?",[$_SESSION['id_user']]);
						?>
  					
  					<!-- <div class="col-sm-2">        	
		        	<div class="card">
		        	<img width="150" style="display: block; margin-left: auto; margin-right: auto; " src="https://images.unsplash.com/photo-1518837695005-2083093ee35b?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=60" class="img-fluid" alt="Responsive image">
							  <div class="card-body" style="padding:0px;">
							    <center><h5 class="card-title" style="font-size:15px!important; margin: 0px;">Baju tentara nasional</h5></center>
							    <center><button  href="javascript:void(0);" value="100" class="datas" class="btn btn-primary"style="width: 100px; font-size:10px!important;">Add</button></center>
							  </div>
							</div>
						</div> -->
  					<?php foreach ($data_menu as $menu) {?>
  					<div class="col-sm-2">        	
		        	<div class="card">
		        	<img width="150" style="display: block; margin-left: auto; margin-right: auto; " src="asset/menu/<?php echo $menu->gambar;?>">
							  <div class="card-body" style="padding:0px;">
							    <center><h5 class="card-title" style="font-size:15px!important; margin: 0px;"><?php echo $menu->nama; ?></h5></center>
							    <center><button href="javascript:void(0);" value="<?php echo $menu->id;?>" class="datas btn btn-primary"style="width: 100px; font-size:10px!important;">Tambah</button></center>
							  </div>
							</div>
						</div>
						<?php } ?>

		  	
		  	</div>
		  </div>
		</div>
			<label for="rfidnumber">Data Barang</label>
			<input type="text" id="datas" class="form-control" data-role="tagsinput"  name="tags" class="form-control">
			<label for="rfidnumber">RFID Tag Number</label>
			<input type="text" class="form-control" id="inputs2" aria-describedby="rfidnumber" placeholder="RFID Number will shown here">
			<small id="rfidnumber" class="form-text text-muted">This System Automatically Record Your Abscence</small>


		

		

	</div>
		<div class="container mb-4">
			<h3 id="classInformation"></h3>
			<!-- <div class="p-3 mb-2 text-white" id="tampilMessage"> -->
			<div class="p-3" id="tampilMessage">
				<!-- <b>Name</b> : Daniel Aditama <b>Course</b> : ERP Planning <b>Date/Time</b> : Mon,9-10-17/07:59:59 <b>Status</b>: Early -->
			</div>
			<div class="alert" role="alert"></div>
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
  // $('#inputs2').focus();

  // jika ada perubahan di input field [ENTER], akan mentrigger
  $("#inputs").change(function() {
    var id = $('#inputs').val();
    var isi = $('#rp').val();
    var keperluan = $('#keperluan').val();
    isi=isi.replace(/Rp. /g,'')
		isi=isi.replace(/[.,\/#!$%\^&\*;:{}=\-_`~()]/g,'')

    $.ajax({
        url: 'aksimerchan.php',
        type: 'post',
        data: {
          id: id,
          isi: isi,
          keperluan: keperluan
        }
      })
      .done(function(data1) {
        // console.log(data1);

        // hapus alert danger dan sukses agar bisa bergantian class
        // $('.alert').removeClass('alert-danger alert-success');
        $('#tampilMessage').removeClass('bg-danger bg-success');

        // if (data1 <= 0) {
        //   // $('.alert').addClass('alert-danger').html("RFID belum terdaftar di dalam system kami: " + "<b>{ " + id + " }</b>");
        //   $('#classInformation').html("Whoops, there was an error").addClass('display-4');
        //   $('#tampilMessage').addClass('bg-danger').html("Saldo tidak cukup");
        // } else {
        //   // $('.alert').addClass('alert-success').html(data);
        //   $('#classInformation').html("Class Information").addClass('display-4');
          $('#tampilMessage').html(data1);
        // }

        $('#inputs').val(""); //Mengkosongkan input field
        $('#inputs').focus(); //mengembalikan cursor ke input field

      })
      .fail(function(data1) {
        console.log(data1);
      });
  });

  $("#inputs2").change(function() {
  	// alert('aaaa');
    var id = $('#inputs2').val();
    var id_isi = $('#datas').val();

    $.ajax({
        url: 'aksimerchan.php',
        type: 'post',
        data: {
          id2: id,
          id_isi: id_isi
        }
      })
      .done(function(data1) {
        // console.log(data1);

        // hapus alert danger dan sukses agar bisa bergantian class
        // $('.alert').removeClass('alert-danger alert-success');
        $('#tampilMessage').removeClass('bg-danger bg-success');

        // if (data1 <= 0) {
        //   // $('.alert').addClass('alert-danger').html("RFID belum terdaftar di dalam system kami: " + "<b>{ " + id + " }</b>");
        //   $('#classInformation').html("Whoops, there was an error").addClass('display-4');
        //   $('#tampilMessage').addClass('bg-danger').html("Saldo tidak cukup");
        // } else {
        //   // $('.alert').addClass('alert-success').html(data);
        //   $('#classInformation').html("Class Information").addClass('display-4');
          $('#tampilMessage').html(data1);
        // }

        $('#inputs2').val(""); //Mengkosongkan input field
        $('#inputs2').focus(); //mengembalikan cursor ke input field

      })
      .fail(function(data1) {
        console.log(data1);
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.js" integrity="sha512-VvWznBcyBJK71YKEKDMpZ0pCVxjNuKwApp4zLF3ul+CiflQi6aIJR+aZCP/qWsoFBA28avL5T5HA+RE+zrGQYg==" crossorigin="anonymous"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput-angular.min.js" integrity="sha512-KT0oYlhnDf0XQfjuCS/QIw4sjTHdkefv8rOJY5HHdNEZ6AmOh1DW/ZdSqpipe+2AEXym5D0khNu95Mtmw9VNKg==" crossorigin="anonymous"></script> -->
	<script type="text/javascript">
				// $("#datas").tagsinput('add','');
				// $("#datas").tagsinput('remove', 'on');
		$(".datas-barang").on('click','.datas',function(){
	        // console.log($(this).val());
				$("#datas").tagsinput('add', $(this).val());
	    });
				// $("#books").tagsinput('add', 'yhaa');
				// $("#books").tagsinput('add', 'yhaaaaq');
				// $("#books").tagsinput('add', 'yhaaaaq1');
				// $("#books").tagsinput('add', 'yhaaaaq2');
				// $("#books").tagsinput('add', 'yhaaaaq3');
				// $("#books").val('some tag');
				// $("#books").addTag('some tag2');
				// $('#books').tagsinput('add', { "value": 1 , "text": "jQuery"});
		
	</script>