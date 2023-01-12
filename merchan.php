
	<?php require "partials/head.php";
	require "partial/head.php";
	require "partials/sidebar.php";?> 
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
			<label for="rfidnumber">Qrcode</label>
			<input type="text" id="qrcode" class="form-control" class="form-control">
			<label for="rfidnumber">Data Barang</label>
			<div id="newinput"></div>
			<!-- <input type="text" id="datas" class="form-control" data-role="tagsinput"  name="tags" class="form-control"> -->
			<label for="rfidnumber">RFID Tag Number</label>
			<input type="text" class="form-control" id="inputs2" aria-describedby="rfidnumber" placeholder="RFID Number will shown here">
			<small id="rfidnumber" class="form-text text-muted">This System Automatically Record Your Abscence</small>
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
						
					<div class="table-responsive">
						<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th>Gambar</th>
									<th>Barang</th>
									<th>Stok</th>
									<!-- <th>Satuan Konversi</th> -->
									<th>Harga</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($data_menu as $menu) {?>
								<tr>
									<td>
										<?php if($menu->gambar != ''){?>
											<img class="card-img-top" style="display: block; margin-left: auto; margin-right: auto; " src="asset/menu/<?php echo $menu->gambar;?>">
										<?php } ?>
									</td>
									<td><?php echo $menu->nama; ?></td>
									<td><?php echo $menu->stok; ?></td>
									<!-- <td>
										<?php $dt = $qb->RAW("SELECT * FROM konversi where barang=?",[$menu->id]);?>
										<select>
											<?php foreach ($dt as $row) {?>
												<option value="<?php echo $row->id ?>"><?php echo $row->konversi ?></option>
											<?php } ?>
										</select>
									</td> -->
									<td><?php echo convertToRupiah($menu->harga); ?></td>
									<!-- <td><button href="javascript:void(0);" value="<?php echo $menu->nama.','.$menu->id;?>" class="datas btn btn-primary"style=" font-size:12px!important;">Tambah</button></td> -->
									<td><button href="javascript:void(0);" onclick="add_barang(<?php echo $menu->id;?>)" class="datas btn btn-primary"style=" font-size:12px!important;">Tambah</button></td>
								</tr>
							<?php } ?>
								
							</tbody>
						</table>
					</div>

  					<!-- <?php foreach ($data_menu as $menu) {?>
  					<div class="col-sm-2">        	
		        	<div class="card">
		        		<?php if($menu->gambar != ''){?>
		        	<img class="card-img-top" style="display: block; margin-left: auto; margin-right: auto; " src="asset/menu/<?php echo $menu->gambar;?>">
		        		<?php } ?>
							  <div class="card-body" style="padding:0px;">
							    <center><h5 class="card-title" style="font-size:15px!important; margin: 0px;"><?php echo 'Stok : '.$menu->stok; ?></h5></center>
							    <center><h5 class="card-title" style="font-size:15px!important; margin: 0px;"><?php echo $menu->nama; ?></h5></center>
							    <center><h5 class="card-title" style="font-size:15px!important; margin: 0px;"><?php echo convertToRupiah($menu->harga); ?></h5></center>
							    <center><button href="javascript:void(0);" value="<?php echo $menu->nama.','.$menu->id;?>" class="datas btn btn-primary"style=" font-size:12px!important;">Tambah</button></center>
							  </div>
							</div>
						</div>
						<?php } ?> -->

		  	
		  	</div>
		  </div>
		</div>


		

		

	</div>
		<div class="container mb-4">
			<h3 id="classInformation"></h3>
			<!-- <div class="p-3 mb-2 text-white" id="tampilMessage"> -->
			<div class="p-3" id="tampilMessage">
				<!-- <b>Name</b> : Daniel Aditama <b>Course</b> : ERP Planning <b>Date/Time</b> : Mon,9-10-17/07:59:59 <b>Status</b>: Early -->
			</div>
			<div class="alert" role="alert"></div>
		</div>
		<?php
		$tanggal='Hari Ini';
		if(isset($_POST['cari_transaksi'])){$tanggal='';}
		?>
	<h1 class="h3 mb-2 text-gray-800">Riwayat Transaksi Toko <?php echo $tanggal; ?></h1>
	<div class="col-lg-12 mb-2">
		<form  role="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
		<div class="input-group">
			 <span class="input-group-text" id="basic-addon1">Dari</span>
			<input type="date" class="form-control" value="<?php echo $_POST['tanggal_awal'];?>" name="tanggal_awal">
			 <span class="input-group-text" id="basic-addon1">Sampai</span>
			<input type="date" class="form-control" value="<?php echo $_POST['tanggal_akhir'];?>" name="tanggal_akhir">
			<button type="submit" name="cari_transaksi" class="input-group-text"><span  id="">cari</span></button>
		</div>
		</form>
	</div>
    <div class="card shadow mb-4" id='here'>
        <div class="card-body">
        	<div class="table-responsive">
        	<table class="table table-bordered" width="100%" cellspacing="0">
	            <thead>
	                <tr>
	                    <!-- <th>Nama</th>
	                    <th>NIS</th>
	                    <th>Lembaga/Sekolah Siswa</th>
	                    <th>Lembaga/Sekolah Transaksi</th>
	                    <th>SubUser Transaksi</th>
	                    <th>Toko</th>
	                    <th>Pembelian</th>
	                    <th>Banyak</th>
	                    <th>Harga</th> -->

	                    <th>Nama</th>
	                    <th>NIS</th>
	                    <th>Lembaga/Sekolah</th>
	                    <th>Toko</th>
	                    <th>Total</th>
	                    <th>Aksi</th>
	                </tr>
	            </thead>
	            <?php
	            	$total=0;
	            	$id_user="user='".$_SESSION['id_user']."'";
	            	if($_SESSION['role'] == 3){$id_user="subuser='".$_SESSION['sub_user']."'";}
	            	if(isset($_POST['cari_transaksi'])){
	            	// $saldo_log = $qb->RAW("SELECT (select count(id_log) from saldo_log x where ket=saldo_log.ket) as banyak_pemb,u1.lembaga as userutama,u2.lembaga as subuser,user.lembaga,siswa.nama,siswa.nis,saldo_log.ket,saldo_log.banyak FROM saldo_log 
	            	// 	left join siswa on siswa.norf=saldo_log.id_rfid
	            	// 	left join user on siswa.user_input=user.id_user
	            	// 	left join user u1 on saldo_log.user=u1.id_user
	            	// 	left join user u2 on saldo_log.subuser=u2.id_user
	            	// 	where saldo_log.".$id_user." and DATE(saldo_log.waktu) between '".$_POST['tanggal_awal']."' and '".$_POST['tanggal_akhir']."' and saldo_log.jenis='keluar' 
	            	// 	group by saldo_log.ket",[]);
            		$saldo_log = $qb->RAW("select 
            			u1.lembaga as userutama,u2.lembaga as subuser,siswa.nama,siswa.nis,t_toko.jumlah,user.lembaga,t_toko.id
            			from t_toko
            			left join siswa on siswa.norf=t_toko.rfid
            			left join user on siswa.user_input=user.id_user
            			left join user u1 on t_toko.user=u1.id_user
            			left join user u2 on t_toko.subuser=u2.id_user
            			where t_toko.".$id_user." and DATE(t_toko.waktu) between '".$_POST['tanggal_awal']."' and '".$_POST['tanggal_akhir']."'",[]);
	            	}else{
	            	// $saldo_log = $qb->RAW("SELECT (select count(id_log) from saldo_log y where ket=saldo_log.ket) as banyak_pemb,u1.lembaga as userutama,u2.lembaga as subuser,user.lembaga,siswa.nama,siswa.nis,saldo_log.ket,saldo_log.banyak FROM saldo_log 
	            	// 	left join siswa on siswa.norf=saldo_log.id_rfid
	            	// 	left join user on siswa.user_input=user.id_user
	            	// 	left join user u1 on saldo_log.user=u1.id_user
	            	// 	left join user u2 on saldo_log.subuser=u2.id_user
	            	// 	where saldo_log.".$id_user." and DATE(saldo_log.waktu) = CURDATE() and saldo_log.jenis='keluar' 
	            	// 	group by saldo_log.ket",[]);
            		$saldo_log = $qb->RAW("select 
            			u1.lembaga as userutama,u2.lembaga as subuser,siswa.nama,siswa.nis,t_toko.jumlah,user.lembaga,t_toko.id
            			from t_toko
            			left join siswa on siswa.norf=t_toko.rfid
            			left join user on siswa.user_input=user.id_user
            			left join user u1 on t_toko.user=u1.id_user
            			left join user u2 on t_toko.subuser=u2.id_user
            			where t_toko.".$id_user." and DATE(t_toko.waktu) = CURDATE()",[]);
	            	}
	            ?>
	            <tbody>
	            	

	            	<?php foreach ($saldo_log as $log) { 
	            		// $harga=enkripsiDekripsi($log->banyak, $kunciRahasia);
	            		// $total=$total+($harga*$log->banyak_pemb);
	            		$total=$total+(enkripsiDekripsi($log->jumlah, $kunciRahasia));
	            		?>
	                <tr>
	                    <!-- <td><?php echo $log->nama; ?></td>
	                    <td><?php echo $log->nis; ?></td>
	                    <td><?php echo $log->lembaga; ?></td>
	                    <td><?php echo $log->userutama; ?></td>
	                    <td><?php echo $log->subuser; ?></td>
	                    <td><?php echo $log->ket; ?></td>
	                    <td><?php echo $log->banyak_pemb; ?></td>
	                    <td><?php echo convertToRupiah($harga); ?></td> -->

	                    <td><?php echo $log->nama; ?></td>
	                    <td><?php echo $log->nis; ?></td>
	                    <td><?php echo $log->lembaga; ?></td>
	                    <td><?php echo $log->subuser; ?></td>
	                    <td><?php echo convertToRupiah(enkripsiDekripsi($log->jumlah, $kunciRahasia)); ?></td>
	                    <td>
                        <center>
                            <a href="faktur_toko.php?id=<?php echo $log->id;?>" target="_blank"><i class="fa-solid fa-print"></i></a>
                        </center>
                    	</td>
	                </tr>
	                `<?php } ?>
	            </tbody>
	        </table>
	        <h5>Total : <?php echo convertToRupiah($total); ?></h5>
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
	$('#datas').tagsinput({
		  allowDuplicates: true,
        itemValue: 'id',  // this will be used to set id of tag
        itemText: 'label' // this will be used to set text of tag
		});
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

    	$('#rp').val("");
    	$('#keperluan').val("");
        $('#inputs').val(""); //Mengkosongkan input field
        $('#inputs').focus(); //mengembalikan cursor ke input field

      })
      .fail(function(data1) {
        console.log(data1);
      });
      updateDiv()
  });

  $("#inputs2").change(function() {

	var harga = $('input[name="harga[]"]').map(function(){ return this.value;}).get();
	var satuan = $('select[name="satuan[]"]').map(function(){ return this.value;}).get();
	var jumlah = $('input[name="jumlah[]"]').map(function(){ return this.value;}).get();
	var barang = $('input[name="barang[]"]').map(function(){ return this.value;}).get();
    var id = $('#inputs2').val();

    $.ajax({
        url: 'aksimerchan.php',
        type: 'post',
        data: {
          id2: id,
          harga: harga,
          satuan: satuan,
          jumlah: jumlah,
          barang: barang,
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

		$("#datas").tagsinput('removeAll');
        $('#inputs2').val(""); //Mengkosongkan input field
        $('#inputs2').focus(); //mengembalikan cursor ke input field

      })
      .fail(function(data1) {
        console.log(data1);
      });
      updateDiv()
  });
});

  function updateDiv()
	{ 
	    $( "#here" ).load(window.location.href + " #here" );
	}

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
	        let text = $(this).val();
			const myArray = text.split(",");
				$("#datas").tagsinput('add', { id:myArray[1] , label: myArray[0] });
	    });
		let text = '';
		$("#qrcode").change(function() {
			add_barang($('#qrcode').val());
		});

		function add_barang(id){
			// let text = '';
			$.ajax({
				url: 'name_toko.php?id='+id,
				type: 'get',
				dataType:'json',
			})
			.done(function(data1) {
				// console.log(data1[0]);
				// return;
				if(data1.length < 1){
					alert('Data Kosong');
				}else{
					// const myArray = text.split(",");
					// $("#datas").tagsinput('add', { id:myArray[1] , label: myArray[0] });

					newRowAdd =
					'<div class="input-group" id="row">'+
							'<input type="hidden" id="barang'+data1[0].id+'" class="form-control" name="barang[]" value="'+data1[0].id+'" required>'+
							'<input type="text" class="form-control" placeholder="Barang" value="'+data1[0].nama+'" readonly>'+
							'<input type="text" class="form-control" placeholder="Stok" value="Stok : '+data1[0].stok+'" readonly>'+
							'<span class="input-group-text">Jumlah :</span>'+
							'<input type="number" class="form-control" placeholder="Jumlah" name="jumlah[]" value="1" >'+
							'<select id="satuan'+data1[0].id+'" onchange="myFunction'+data1[0].id+'()" class="form-control" name="satuan[]" required>'+
								'<option value="'+data1[0].satuan+'">Satuan : '+data1[0].nama_satuan+'</option>';
							// '@foreach($barang as $dt)'+
							// '<option value="{{ $dt->id }}" >{{ $dt->kode_barang."-".$dt->nama."-stok:".$dt->stok }}</option>'+
							// '@endforeach'+
					konversi=data1[0].konversi;
					for (let index = 0; index < konversi.length; index++) {
						newRowAdd = newRowAdd+'<option value="'+konversi[index].konversi+'">'+konversi[index].nama_satuan+'</option>';
					}
					newRowAdd = newRowAdd+'</select>'+
						'<span class="input-group-text">Harga :</span>'+
							'<input type="number" id="harga'+data1[0].id+'" class="form-control" name="harga[]" placeholder="Harga" value="'+data1[0].harga+'" readonly>'+
						'<div class="input-group-prepend">'+
							'<button class="btn btn-danger form-control" id="DeleteRow" type="button"><i class="fa-solid fa-trash-can"></i>Delete</button>'+
						'</div>'+
					'</div>'+
					'<script>'+
						// '$("#satuan'+data1[0].id+'").on("change",function() {'+
						'function myFunction'+data1[0].id+'() {'+
							// 'alert("haha");'+
							'$.ajax({'+
										'url: "ajax_harga.php?barang="+$("#barang'+data1[0].id+'").val()+"&&id="+$("#satuan'+data1[0].id+'").val(),'+
										'type: "get",'+
										'dataType:"json",'+
									'})'+
							'.done(function(data1) {'+
								'$("#harga'+data1[0].id+'").val(data1[0].harga);'+
							'})'+
						'};'+
						// '});'+
					'</\script>';

					$('#newinput').append(newRowAdd);
				}
				
				$('#qrcode').val(""); //Mengkosongkan input field
			})

			// console.log(text);
			// alert(text);
			// return;

		};

		$("body").on("click", "#DeleteRow", function () {
			$(this).parents("#row").remove();
		})

		// function processResult(result){
		// 	// console.log("The result is: " + result)
		// 	text=result;
		// 	}
		
	</script>

