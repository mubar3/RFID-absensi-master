
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
		<h2 class="text-primary mt-4">Pembelian Barang </h2>

		<div class="form-group">
			<label for="rfidnumber">Qrcode Barang</label>
			<input type="text" id="qrcode" class="form-control" class="form-control">
			<label for="rfidnumber">Data Barang</label>
			<!-- <div class="input-group" id="row">
				<span class="input-group-text">Total :</span>
				<input type="number" class="form-control" id="total_pembelian" placeholder="Total" value="0" >
			</div> -->
			<div id="newinput"></div>
			<!-- <input type="text" id="datas" class="form-control" data-role="tagsinput"  name="tags" class="form-control"> -->
			<label for="rfidnumber">Tanggal Beli</label>
			<input type="date" class="form-control" id="tgl_beli" placeholder="Tanggal">
			<label for="rfidnumber">Tanggal Bayar</label>
			<input type="date" class="form-control" id="tgl_bayar" placeholder="Tanggal">
			<label for="rfidnumber">Telah dibayar (Rp)</label>
			<input type="number" class="form-control" id="dibayar" placeholder="Dibayar" value="0" required>
			<label for="rfidnumber">Biaya Lainnya (Rp)</label>
			<input type="number" class="form-control" id="b_lainnya" placeholder="Lainnya" value="0" required>
			<label for="rfidnumber">Ket</label>
			<input type="text" class="form-control" id="b_ket" placeholder="Keterangan">
			<center><button href="javascript:void(0);" onclick="simpan_penj()" class="btn btn-primary" >Simpan</button></center>
			<!-- <small id="rfidnumber" class="form-text text-muted">This System Automatically Record Your Abscence</small> -->
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
    						"SELECT toko_menu.*,satuan.nama as nama_satuan FROM toko_menu 
							left join satuan on satuan.id=toko_menu.satuan and satuan.id_user=?  
							where toko_menu.id_user=?",[$_SESSION['id_user'],$_SESSION['id_user']]);
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
									<th>Harga Jual</th>
									<th>Harga Pokok</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($data_menu as $menu) {?>
								<tr>
									<td>
										<?php if($menu->gambar != ''){?>
											<img class="card-img-top" style="display: block; margin-left: auto;  margin-right: auto;   width: 70px;" class="img-responsive img" src="asset/menu/<?php echo $menu->gambar;?>">
										<?php } ?>
									</td>
									<td><?php echo $menu->nama; ?></td>
									<td><?php echo $menu->stok.' '.$menu->nama_satuan; ?></td>
									<!-- <td>
										<?php $dt = $qb->RAW("SELECT * FROM konversi where barang=?",[$menu->id]);?>
										<select>
											<?php foreach ($dt as $row) {?>
												<option value="<?php echo $row->id ?>"><?php echo $row->konversi ?></option>
											<?php } ?>
										</select>
									</td> -->
									<td><?php echo convertToRupiah($menu->harga); ?></td>
									<td><?php echo convertToRupiah($menu->harga_pokok); ?></td>
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
				<?php 
				if(isset($_GET['hapus_id'])){
					$aksi = $qb->RAW("SELECT * FROM pembelian_detail WHERE id_pembelian=?",[$_GET['hapus_id']]);
					foreach ($aksi as $value) {
						$barang = $qb->RAW("SELECT * FROM toko_menu WHERE id=?",[$value->barang]);
						$barang=$barang[0];
						$dt=$barang->stok - $value->jumlah;
						$qb->RAW("UPDATE toko_menu set stok=? WHERE id=?",[$dt,$value->barang]);
					}

					$qb->RAW("DELETE from pembelian_detail where id_pembelian=?",[$_GET['hapus_id']]);
					$aksi=$qb->RAW("DELETE from pembelian where id=?",[$_GET['hapus_id']]);
					if($aksi){
						echo "<div class='p-3 mb-2 bg-success'>Hapus transaksi berhasil</div>";
					}else{
						echo "<div class='p-3 mb-2 bg-danger'>Hapus transaksi gagal<div>";
					}
					echo '<script>setTimeout(function(){location.replace("pembelian.php"); }, 1000);</script>';
					
				}
				?>
				<!-- <b>Name</b> : Daniel Aditama <b>Course</b> : ERP Planning <b>Date/Time</b> : Mon,9-10-17/07:59:59 <b>Status</b>: Early -->
			</div>
			<div class="alert" role="alert"></div>
		</div>
		<?php
		$tanggal='Hari Ini';
		if(isset($_POST['cari_transaksi'])){$tanggal='';}
		?>
	<h1 class="h3 mb-2 text-gray-800">Riwayat Pembelian <?php echo $tanggal; ?></h1>
	<div class="col-lg-12 mb-2">
		<form  role="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
		<div class="input-group">
			 <span class="input-group-text" id="basic-addon1">Dari</span>
			<input type="date" class="form-control" value="<?php echo $_POST['tanggal_awal'];?>" name="tanggal_awal">
			 <span class="input-group-text" id="basic-addon1">Sampai</span>
			<input type="date" class="form-control" value="<?php echo $_POST['tanggal_akhir'];?>" name="tanggal_akhir">
			<button type="submit" name="cari_transaksi" id="click_cari" class="input-group-text"><span  id="">cari</span></button>
			<?php if(isset($_POST['tanggal_akhir'])){ ?>
				<a class="btn btn-primary" target="_Blank" href="download_excel_pembelian.php?awal=<?php echo $_POST['tanggal_awal']; ?>&&akhir=<?php echo $_POST['tanggal_akhir']; ?>"><span class="glyphicon glyphicon-download"></span>Download excel</a>
			<?php } ?>
		</div>
		</form>
	</div>
    <div class="card shadow mb-4">
        <div class="card-body">
        	<div class="table-responsive">
        	<table class="table table-bordered" width="100%" cellspacing="0">
	            <thead>
	                <tr>
	                    <th>Tanggal Pembelian</th>
	                    <th>Tagihan (Rp)</th>
	                    <th>Sudah terbayar</th>
	                    <th>Tanggal pembayaran</th>
	                    <th>Aksi</th>
	                </tr>
	            </thead>
	            <?php
	            	$total=0;
	            	$id_user="user='".$_SESSION['id_user']."'";
	            	if($_SESSION['role'] == 3){$id_user="subuser='".$_SESSION['sub_user']."'";}
	            	if(isset($_POST['cari_transaksi'])){
						$saldo_log = $qb->RAW("select *,date(waktu) as waktu from pembelian
							where ".$id_user." and DATE(waktu) between '".$_POST['tanggal_awal']."' and '".$_POST['tanggal_akhir']."'",[]);
	            	}else{
						$saldo_log = $qb->RAW("select *,date(waktu) as waktu from pembelian
							where ".$id_user." and DATE(waktu) = CURDATE()",[]);
	            	}
	            ?>
	            <tbody id='here'>
	            	

	            	<?php foreach ($saldo_log as $log) { 
	            		$total=$total+(enkripsiDekripsi($log->jumlah, $kunciRahasia))+(enkripsiDekripsi($log->b_lainnya, $kunciRahasia));
	            		?>
	                <tr>

						<td>
							<input type="hidden" id="id_tgl_beli_ajax" value="<?php echo $log->id; ?>">
							<input type="date" id="tgl_beli_ajax" value="<?php echo $log->waktu; ?>">
							<button href="javascript:void(0);" onclick="simpan_tgl_beli()" class="btn btn-primary" >Update</button>
						</td>
	                    <td><?php echo convertToRupiah(enkripsiDekripsi($log->jumlah, $kunciRahasia)); ?></td>
	                    <td>
							<!-- <form  role="form" action="" method="post" autocomplete="off" enctype="multipart/form-data"> -->
								<input type="hidden" id="id_dibayar" value="<?php echo $log->id; ?>">
								<input type="text" id="jumlah_dibayar" value="<?php echo enkripsiDekripsi($log->dibayar, $kunciRahasia); ?>">
								<!-- <button type="submit" name="simpan_dibayar" class="btn btn-primary"><span  id="">Simpan</span></button></center> -->
								<button href="javascript:void(0);" onclick="simpan_dibayar()" class="btn btn-primary" >Update</button>
            				<!-- </form> -->
						</td>
	                    <td><?php echo $log->pembayaran; ?></td>
	                    <td>
                        <center>
							<a data-toggle="collapse" data-target="#demo<?php echo $log->id; ?>"><i class="fas fa-eye"></i></a>
							||
                            <a href="pembelian.php?hapus_id=<?php echo $log->id;?>"><i class="fa-solid fa-trash-can"></i></a>
                        </center>
                    	</td>
	                </tr>
	                <tr  class="collapse" id="demo<?php echo $log->id; ?>">
						<?php $detail = $qb->RAW("select 
								pembelian_detail.*, 
								toko_menu.nama as nama_barang,
								satuan.nama as satuan_konversi,
								s2.nama as satuan_asli
							from pembelian_detail
							join toko_menu on toko_menu.id = pembelian_detail.barang
							join satuan on satuan.id = pembelian_detail.satuan
							join satuan s2 on s2.id = toko_menu.satuan
							where pembelian_detail.id_pembelian = ? ",[$log->id]); ?>
						<td></td>
						<td>
							<b>Barang</b> <br>
							<?php 
								foreach ($detail as $key) {
									echo $key->nama_barang.'<br>';
								}
							?>
						</td>
						<td>
							<b>Banyak</b> <br>
							<?php 
								foreach ($detail as $key) {
									// echo $key->jumlah.' '.$key->satuan_asli.' / '.$key->jumlah_satuan.' '.$key->satuan_konversi.'<br>';
									echo $key->jumlah.' '.$key->satuan_asli.'<br>';
								}
							?>
						</td>
						<td>
							<b>Biaya lainnya</b> <br>
							<?php 
								if($log->b_ket != ''){
									echo $log->b_ket;
								}
								if($log->b_lainnya != ''){
									echo convertToRupiah(enkripsiDekripsi($log->b_lainnya, $kunciRahasia));
								}
							?>
						</td>
						<td></td>
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

  
  

});

	function simpan_dibayar(){

		var id_dibayar = $('#id_dibayar').val();
		var jumlah_dibayar = $('#jumlah_dibayar').val();

		$.ajax({
			url: 'aksi_ubah_dibayar.php',
			type: 'post',
			data: {
			id_dibayar: id_dibayar,
			jumlah_dibayar: jumlah_dibayar,
			}
		})
		.done(function(data1) {
			$('#tampilMessage').removeClass('bg-danger bg-success');

			$('#tampilMessage').html(data1);
			
			$('#row').remove();

		})
		.fail(function(data1) {
			// console.log(data1);
		});
		updateDiv()
	};

	function simpan_tgl_beli(){

		var id_dibayar = $('#id_dibayar').val();
		var jumlah_dibayar = $('#jumlah_dibayar').val();

		$.ajax({
			url: 'aksi_ubah_tanggal_beli.php',
			type: 'post',
			data: {
			id_tgl_beli_ajax: $('#id_tgl_beli_ajax').val(),
			tgl_beli_ajax: $('#tgl_beli_ajax').val(),
			}
		})
		.done(function(data1) {
			$('#tampilMessage').removeClass('bg-danger bg-success');

			$('#tampilMessage').html(data1);
			
			$('#row').remove();

		})
		.fail(function(data1) {
			// console.log(data1);
		});
		updateDiv()
		document.getElementById("click_cari").click();
	};

	function simpan_penj(){

		var harga = $('input[name="harga[]"]').map(function(){ return this.value;}).get();
		var satuan = $('select[name="satuan[]"]').map(function(){ return this.value;}).get();
		var jumlah = $('input[name="jumlah[]"]').map(function(){ return this.value;}).get();
		var barang = $('input[name="barang[]"]').map(function(){ return this.value;}).get();
		var tgl_bayar = $('#tgl_bayar').val();
		var tgl_beli = $('#tgl_beli').val();
		var dibayar = $('#dibayar').val();
		var b_lainnya = $('#b_lainnya').val();
		var b_ket = $('#b_ket').val();

		$.ajax({
			url: 'aksi_pembelian.php',
			type: 'post',
			data: {
			tgl_bayar: tgl_bayar,
			tgl_beli: tgl_beli,
			harga: harga,
			satuan: satuan,
			jumlah: jumlah,
			barang: barang,
			dibayar: dibayar,
			b_lainnya: b_lainnya,
			b_ket: b_ket,
			}
		})
		.done(function(data1) {
			$('#tampilMessage').removeClass('bg-danger bg-success');

			$('#tampilMessage').html(data1);
			
			// $('#row').remove();
			$('.rowid').remove();
			$('#tgl_bayar').val("");

		})
		.fail(function(data1) {
			// console.log(data1);
		});
		updateDiv()
	};

  function updateDiv()
	{ 
	    $( "#here" ).load(window.location.href + " #here" );
	}

var rupiah = document.getElementById('rp');
		// rupiah.addEventListener('keyup', function(e){
		// 	// tambahkan 'Rp.' pada saat form di ketik
		// 	// gunakan fungsi formatRupiah() untuk mengubah angka yang di ketik menjadi format angka
		// 	rupiah.value = formatRupiah(this.value, 'Rp. ');
		// });
 
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
		total_pembelian=0;
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
					'<div class="input-group rowid" id="row">'+
							'<input type="hidden" id="barang'+data1[0].id+'" class="form-control" name="barang[]" value="'+data1[0].id+'" required>'+
							'<input type="text" class="form-control" placeholder="Barang" value="'+data1[0].nama+'" readonly>'+
							'<input type="text" class="form-control" placeholder="Stok" value="Stok : '+data1[0].stok+'" readonly>'+
							'<span class="input-group-text">Jumlah :</span>'+
							'<input type="number" class="form-control" placeholder="Jumlah" name="jumlah[]" value="0" >'+
							'<select id="satuan'+data1[0].id+'" onchange="myFunction'+data1[0].id+'()" class="form-control" name="satuan[]" required>'+
								'<option value="'+data1[0].satuan+'">Satuan : '+data1[0].nama_satuan+'</option>';
							// '@foreach($barang as $dt)'+
							// '<option value="{{ $dt->id }}" >{{ $dt->kode_barang."-".$dt->nama."-stok:".$dt->stok }}</option>'+
							// '@endforeach'+
					// konversi=data1[0].konversi;
					// for (let index = 0; index < konversi.length; index++) {
					// 	newRowAdd = newRowAdd+'<option value="'+konversi[index].konversi+'">'+konversi[index].nama_satuan+'</option>';
					// }
					newRowAdd = newRowAdd+'</select>'+
						'<span class="input-group-text">Harga :</span>'+
							'<input type="number" id="harga'+data1[0].id+'" class="form-control" name="harga[]" placeholder="Harga" value="'+data1[0].harga_pokok+'" readonly>'+
						'<div class="input-group-prepend">'+
							'<button class="btn btn-danger form-control" id="DeleteRow" value="'+data1[0].harga_pokok+'" type="button"><i class="fa-solid fa-trash-can"></i>Delete</button>'+
						'</div>'+
					'</div>';

					$('#newinput').append(newRowAdd);
					total_pembelian=parseInt(total_pembelian)+parseInt(data1[0].harga_pokok);
					$('#total_pembelian').val(total_pembelian);
				}
				
				
				$('#qrcode').val(""); //Mengkosongkan input field
			})

			// console.log(text);
			// alert(text);
			// return;

		};

		$("body").on("click", "#DeleteRow", function () {
			total_pembelian=total_pembelian-$(this).val();
			$('#total_pembelian').val(total_pembelian);
			// alert($(this).val());
			$(this).parents("#row").remove();
		})

		// function processResult(result){
		// 	// console.log("The result is: " + result)
		// 	text=result;
		// 	}
		
	</script>

