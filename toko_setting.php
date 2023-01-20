<?php 
require "partial/head.php";
require "partials/head.php";
require "partials/sidebar.php";
require "asset/phpqrcode/qrlib.php"; ?>
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
		if(isset($_POST['simpan_ukuran'])){
			$data=$_POST['data'];
			$set=array();
			foreach ($data as $key => $value) {
				array_push($set,$key.'="'.$value.'"');
			}
			$set=implode(",",$set);
			// print_r($set);die();

			$aksi = $qb->RAW(
            "UPDATE user SET ".$set." where id_user=".$_SESSION['id_user'],[]);

	        if($aksi){
	        	echo '<div class="col-lg-12 mb-4">
			        <div class="card bg-success text-white shadow">
			            <div class="card-body">
			                Berhasil
			                <div class="text-white-50 small">Tersimpan</div>
			            </div>
			        </div>
			    </div>';
	        }else{
	        	echo '<div class="col-lg-12 mb-4">
		        <div class="card bg-danger text-white shadow">
		            <div class="card-body">
		                Gagal
		                <div class="text-white-50 small">Gagal Tersimpan</div>
		            </div>
		       	 </div>
		    	</div>';
	        }
		}

     	if(isset($_POST['simpan_menu'])){
     		$filename=$_FILES['gambar']['name'];
     	   if(!empty($filename)){
     		$ext = pathinfo($filename, PATHINFO_EXTENSION);
     		$name = pathinfo($filename, PATHINFO_FILENAME);
            $filename=$name.rand(111,9999).'.'.$ext;
            // print_r($filename);die();
            $upload=move_uploaded_file($_FILES['gambar']['tmp_name'],  "asset/menu/".$filename);
                for($x=0;$x<20;$x++){
                    if(filesize("asset/menu/".$filename)>50000)
                    {resizer("asset/menu/".$filename, "asset/menu/".$filename, 70);}else{ break;}
                    clearstatcache();
                }
            }

			$id_toko=$qb->pdo->lastInsertId();
			$isiqr=$id_toko;
			$cek='';
			if($_POST['qr'] != ''){
				$cek = $qb->RAW("SELECT * FROM toko_menu where qr = ? and qr != '' ",[$_POST['qr']]);
				$isiqr=$_POST['qr'];
			}
			if(array_key_exists(0, $cek)){
	        	echo '<div class="col-lg-12 mb-4">
		        <div class="card bg-danger text-white shadow">
		            <div class="card-body">
		                Gagal
		                <div class="text-white-50 small">Kode qr sudah tersimpan</div>
		            </div>
		       	 </div>
		    	</div>';
			}else{

				$nameqrcode    = $id_toko.'.png';              
				$tempdir        = "asset/qrcode_toko/"; 
				$isiqrcode     = $isiqr;
				$quality        = 'H';
				$Ukuran         = 10;
				$padding        = 0;

				QRCode::png($isiqrcode,$tempdir.$nameqrcode,$quality,$Ukuran,$padding);
				
				$aksi = $qb->insert('toko_menu', [
				'harga' => $_POST['harga'],
				'harga_pokok' => $_POST['harga_pokok'],
				'nama' => $_POST['nama'],
				'stok' => $_POST['stok'],
				'satuan' => $_POST['satuan'],
				'jenis' => $_POST['jenis'],
				'qr' => $_POST['qr'],
				'gambar' => $filename,
				'id_user' => $_SESSION['id_user']

				]);

				if($aksi){
					echo '<div class="col-lg-12 mb-4">
						<div class="card bg-success text-white shadow">
							<div class="card-body">
								Berhasil
								<div class="text-white-50 small">Data Tersimpan</div>
							</div>
						</div>
					</div>';
				}else{
					echo '<div class="col-lg-12 mb-4">
					<div class="card bg-danger text-white shadow">
						<div class="card-body">
							Gagal
							<div class="text-white-50 small">Data Gagal Tersimpan</div>
						</div>
					</div>
					</div>';
				}
			}
     	}

     	if(isset($_POST['update_menu'])){

			$cek='';
			if($_POST['qr'] != ''){
				$cek = $qb->RAW("SELECT * FROM toko_menu where qr = ? and qr != '' and id != ? ",[$_POST['qr'],$_POST['id_menu']]);
			}

			if($cek != '' && array_key_exists(0, $cek)){
	        	echo '<div class="col-lg-12 mb-4">
		        <div class="card bg-danger text-white shadow">
		            <div class="card-body">
		                Gagal
		                <div class="text-white-50 small">Kode qr sudah tersimpan</div>
		            </div>
		       	 </div>
		    	</div>';
			}else{
				if($_POST['qr'] != ''){
					$nameqrcode    = $_POST['id_menu'].'.png';              
					$tempdir        = "asset/qrcode_toko/"; 
					$isiqrcode     = $_POST['qr'];
					$quality        = 'H';
					$Ukuran         = 10;
					$padding        = 0;

					QRCode::png($isiqrcode,$tempdir.$nameqrcode,$quality,$Ukuran,$padding);
				}

				$filename=$_FILES['gambar']['name'];
				if(!empty($filename)){
					unlink('asset/menu/'.$_GET['foto']);
				}
				if(!empty($filename)){
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				$name = pathinfo($filename, PATHINFO_FILENAME);
				$filename=$name.rand(111,9999).'.'.$ext;
				// print_r($filename);die();
				$upload=move_uploaded_file($_FILES['gambar']['tmp_name'],  "asset/menu/".$filename);
					for($x=0;$x<20;$x++){
						if(filesize("asset/menu/".$filename)>50000)
						{resizer("asset/menu/".$filename, "asset/menu/".$filename, 70);}else{ break;}
						clearstatcache();
					}
				}

				$aksi = $qb->RAW("UPDATE toko_menu set harga=?,harga_pokok=?,nama=?,gambar=?,stok=?,satuan=?,jenis=?,qr=? WHERE id=?",[$_POST['harga'],$_POST['harga_pokok'],$_POST['nama'],$filename,$_POST['stok'],$_POST['satuan'],$_POST['jenis'],$_POST['qr'],$_POST['id_menu']]);

				if($aksi){
					echo '<div class="col-lg-12 mb-4">
						<div class="card bg-success text-white shadow">
							<div class="card-body">
								Berhasil
								<div class="text-white-50 small">Data Tersimpan</div>
							</div>
						</div>
					</div>';
				}else{
					echo '<div class="col-lg-12 mb-4">
					<div class="card bg-danger text-white shadow">
						<div class="card-body">
							Gagal
							<div class="text-white-50 small">Data Gagal Tersimpan</div>
						</div>
					</div>
					</div>';
				}
			}
            echo '<script>setTimeout(function(){location.replace("toko_setting.php"); }, 1000);</script>';
     	}
     	
     	if(isset($_GET['hapus_menu'])){
	        $aksi = $qb->RAW(
    		"DELETE from toko_menu where id=".$_GET['hapus_menu'],[]);
			if(file_exists('asset/qrcode_toko/'.$_GET['hapus_menu'].'.png')){
				unlink('asset/qrcode_toko/'.$_GET['hapus_menu'].'.png');
			}
	        if($aksi){
	        	if ($_GET['foto_menu'] != '') {
                	unlink('asset/menu/'.$_GET['foto_menu']);
            	}
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
            echo '<script>setTimeout(function(){location.replace("toko_setting.php"); }, 1000);</script>';
     	}

     	 if(isset($_POST['Upload'])){
        require "asset/excel_reader2.php";
		ini_set('max_execution_time', 1000000);
        // upload file xls
        $target = strtotime(date("Y-m-d H:i:s")).$_SESSION['id_user'].basename($_FILES['excel']['name']);
        // print_r(strtotime(date("Y-m-d H:i:s")).$target);die();
        move_uploaded_file($_FILES['excel']['tmp_name'], $target);

        // beri permisi agar file xls dapat di baca
        chmod($target,0777);

        // mengambil isi file xls
        $data = new Spreadsheet_Excel_Reader($target,false);
        // menghitung jumlah baris data yang ada
        $jumlah_baris = $data->rowcount($sheet_index=0);
		
		// cek data
		$cek=true;
		for ($i=2; $i<=$jumlah_baris; $i++){
            if($data->val($i, 1) == ''){break;}
			IF($data->val($i, 6) != ''){
				$cek = $qb->RAW("SELECT * FROM toko_menu where qr = ? and qr != '' ",[$data->val($i, 6)]);
				if(array_key_exists(0, $cek)){$cek=false;break;}
			}
			IF($data->val($i, 5) != ''){
				$cek = $qb->RAW("SELECT * FROM jenis where id = ? ",[$data->val($i, 5)]);
				if(array_key_exists(0, $cek)){$cek=true;}else{$cek=false;break;}
			}
			IF($data->val($i, 4) != ''){
				$cek = $qb->RAW("SELECT * FROM satuan where id = ? ",[$data->val($i, 4)]);
				if(array_key_exists(0, $cek)){$cek=true;}else{$cek=false;break;}
			}
		}

        // jumlah default data yang berhasil di import
		if($cek){
			for ($i=2; $i<=$jumlah_baris; $i++){
				if($data->val($i, 2) == ''){break;}

						$qb->insert('toko_menu', [
								'nama' => $data->val($i, 1),
								'harga' => $data->val($i, 2),
								'stok' => $data->val($i, 3),
								'satuan' => $data->val($i, 4),
								'jenis' => $data->val($i, 5),
								'qr' => $data->val($i, 6),
								'harga_pokok' => $data->val($i, 7),
								'id_user' => $_SESSION['id_user']
								]);

						$id_toko=$qb->pdo->lastInsertId();
						$isiqr=$id_toko;
						IF($data->val($i, 6) != ''){
							$isiqr=$data->val($i, 6);
						}
						
						$nameqrcode    = $id_toko.'.png';              
						$tempdir        = "asset/qrcode_toko/"; 
						$isiqrcode     = $isiqr;
						$quality        = 'H';
						$Ukuran         = 10;
						$padding        = 0;
			
						QRCode::png($isiqrcode,$tempdir.$nameqrcode,$quality,$Ukuran,$padding);

			}
			echo '
			<div class="col-lg-12 mb-4">
				 <div class="card bg-success text-white shadow">
					 <div class="card-body">
						 Berhasil
						 <div class="text-white-50 small">Import selesai</div>
					 </div>
				 </div>
			 </div>
			 '; 
		}else{
			echo '<div class="col-lg-12 mb-4">
			<div class="card bg-danger text-white shadow">
				<div class="card-body">
					Gagal
					<div class="text-white-50 small">Data ada yang eror</div>
				</div>
				</div>
			</div>';

		}           

        unlink($target);
        }


    $data_menu = $qb->RAW(
    "SELECT 
		toko_menu.*,
		jenis.nama as nama_jenis,
		satuan.nama as nama_satuan
	FROM toko_menu
	left join satuan on satuan.id=toko_menu.satuan 
	left join jenis on jenis.id=toko_menu.jenis 
	where toko_menu.id_user=?",[$_SESSION['id_user']]);

	$data_satuan = $qb->RAW(
	"SELECT * FROM satuan where id_user=?",[$_SESSION['id_user']]);

	$data_jenis = $qb->RAW(
	"SELECT * FROM jenis where id_user=?",[$_SESSION['id_user']]);

    ?>

    <!-- setting ukuran kartu -->
    <h1 class="h3 mb-2 text-gray-800">Pengaturan Toko</h1>
    <?php 
	    $data_user = $qb->RAW(
			"SELECT * FROM user where id_user=?",[$_SESSION['id_user']]);
	    $data_user=$data_user[0];
    ?>
    <div class="card shadow mb-4">
        <div class="card-body">
        	<form  role="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
        	<table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Saldo Maksimal Kartu/saldo maksimal topup</td>
                            <td><input type="text" class="form-control" value="<?php echo $data_user->saldo_max; ?>" name="data[saldo_max]"></td>
                        </tr>
                    </tbody>
                </table>
                <center><button type="submit" name="simpan_ukuran" class="btn btn-primary"><span  id="">Simpan</span></button></center>
            	</form>
        </div>
    </div>


    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Daftar Barang Toko</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
		<a class="btn btn-primary" target="_Blank" href="download_excel_toko.php">
                                    <span class="glyphicon glyphicon-download"></span>
                                    Download excel</a>
		<a class="btn btn-primary" target="_Blank" href="download_zip_toko.php">
                                    <span class="glyphicon glyphicon-download"></span>
                                    Download Qrcode</a>

    <!-- <h4 class="h3 mb-2 text-gray-800">Import Data Toko</h4><a href="asset/sampel_toko.xls">Template Excel</a> -->
    <form  role="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
		<div class="col-lg-12 mb-2">
			<h4 class="h3 mb-2 text-gray-800">Import Data Toko</h4><a href="asset/sampel_toko.xls">Template Excel</a>
            <div class="input-group">
                <input type="file" name="excel" class="form-control">
            <div class="input-group-prepend">
                <button type="submit" name="Upload" class="input-group-text"><span  id="">Upload</span></button>
            </div>
            </div>
        </div>
    </form>

    
    <form  role="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
	    <div class="col-lg-12 mb-2">
	        	<?php if(isset($_GET['edit_menu'])){ ?>
				<h1 class="h3 mb-2 text-gray-800">Edit</h1>
	        		<?php }else{ ?>
				<h1 class="h3 mb-2 text-gray-800">Tambah</h1>
	        		<?php } ?>
	        <div class="input-group">
	        	<?php if(isset($_GET['edit_menu'])){ ?>
	        	<input type="hidden" name="id_menu" value="<?php echo $_GET['edit_menu']; ?>" class="form-control">
	        		<?php } ?>
			  <input type="file" name="gambar" placeholder="gambar" class="form-control">
			  <input type="text" name="nama" placeholder="Nama*" value="<?php if(isset($_GET['edit_menu'])){ echo $_GET['menu'];}?>" class="form-control" required>
			  <input type="number" name="harga" placeholder="Harga jual*" value="<?php if(isset($_GET['edit_menu'])){ echo $_GET['harga'];}?>" class="form-control" required>
			  <input type="text" name="stok" placeholder="Stok*" value="<?php if(isset($_GET['edit_menu'])){ echo $_GET['stok'];}?>" class="form-control" required>

			</div>
			<div class="input-group">
			    <input type="number" name="harga_pokok" placeholder="Harga pokok*" value="<?php if(isset($_GET['edit_menu'])){ echo $_GET['harga_pokok'];}?>" class="form-control" required>
				<select class="form-control" name="satuan">
					<option value="">Satuan</option>
					<?php foreach ($data_satuan as $row) {
						if(isset($_GET['edit_menu']) && $_GET['satuan'] == $row->id){ ?>
							<option value="<?php echo $row->id ?>" selected><?php echo $row->nama ?></option>
						<?php }else{ ?>
							<option value="<?php echo $row->id ?>"><?php echo $row->nama ?></option>
						<?php }
					} ?>
				</select>
				<select class="form-control" name="jenis">
					<option value="">Jenis</option>
					<?php foreach ($data_jenis as $row) { 
						if(isset($_GET['edit_menu']) && $_GET['jenis'] == $row->id){ ?>
							<option value="<?php echo $row->id ?>" selected><?php echo $row->nama ?></option>
						<?php }else{ ?>
							<option value="<?php echo $row->id ?>"><?php echo $row->nama ?></option>
						<?php } 
					} ?>
				</select>
			  <input type="text" name="qr" placeholder="Kode qr" value="<?php if(isset($_GET['edit_menu'])){ echo $_GET['qr'];}?>" class="form-control">
				<div class="input-group-prepend">
					<?php if(isset($_GET['edit_menu'])){ ?>
					<button type="submit" name="update_menu" class="input-group-text"><span  id="">Update</span></button>
						<?php }else{ ?>
					<button type="submit" name="simpan_menu" class="input-group-text"><span  id="">Simpan</span></button>
						<?php } ?>
				</div>
			</div>
	    </div>
	</form>
            <div class="table-responsive">
                <table class="display table table-bordered" id="table1" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Nama</th>
                            <th>Jenis Barang</th>
                            <th>Harga Jual</th>
                            <th>Harga Pokok</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach ($data_menu as $data_user) {
                            ?>
                        <tr>
                            <td><?php if($data_user->gambar != ''){?><img style="display: block; margin-left: auto;  margin-right: auto;   width: 70px;" class="img-responsive img" src="asset/menu/<?php echo $data_user->gambar;?>"><?php } ?></td>
                            <td><?php echo $data_user->nama;?></td>
                            <td><?php echo $data_user->nama_jenis;?></td>
                            <td><?php echo convertToRupiah($data_user->harga);?></td>
                            <td><?php echo convertToRupiah($data_user->harga_pokok);?></td>
                            <td><?php echo $data_user->stok.' '.$data_user->nama_satuan; ?></td>
                            <td>
                            <center>
                            	<a href="toko_setting.php?edit_menu=<?php echo $data_user->id;?>&&menu=<?php echo $data_user->nama;?>&&harga=<?php echo $data_user->harga;?>&&harga_pokok=<?php echo $data_user->harga_pokok;?>&&foto=<?php echo $data_user->gambar;?>&&stok=<?php echo $data_user->stok;?>&&satuan=<?php echo $data_user->satuan;?>&&jenis=<?php echo $data_user->jenis;?>&&qr=<?php echo $data_user->qr;?>"><i class="fa-solid fa-pen-to-square"></i></a>
                            	&nbsp
                            	<a href="toko_setting.php?hapus_menu=<?php echo $data_user->id;?>&&foto_menu=<?php echo $data_user->gambar;?>"><i class="fa-solid fa-trash-can"></i></a>
                            </center>
                            </td>
                        </tr>
                        <?php } ?>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php


if(isset($_POST['simpan_jenis'])){
	$aksi = $qb->insert('jenis', [
	 'id' => $_POST['kode'],
	 'nama' => $_POST['jenis'],
	 'id_user' => $_SESSION['id_user']
   ]);

   if($aksi){
	   echo '<div class="col-lg-12 mb-4">
		   <div class="card bg-success text-white shadow">
			   <div class="card-body">
				   Berhasil
				   <div class="text-white-50 small">Data Tersimpan</div>
			   </div>
		   </div>
	   </div>';
   }else{
	   echo '<div class="col-lg-12 mb-4">
	   <div class="card bg-danger text-white shadow">
		   <div class="card-body">
			   Gagal
			   <div class="text-white-50 small">Data Gagal Tersimpan</div>
		   </div>
		   </div>
	   </div>';
   }
}

if(isset($_POST['update_jenis'])){

	$aksi = $qb->RAW("UPDATE jenis set nama=?,id=? WHERE id=?",[$_POST['jenis'],$_POST['kode'],$_POST['id_jenis']]);

   if($aksi){
	   echo '<div class="col-lg-12 mb-4">
		   <div class="card bg-success text-white shadow">
			   <div class="card-body">
				   Berhasil
				   <div class="text-white-50 small">Data Tersimpan</div>
			   </div>
		   </div>
	   </div>';
   }else{
	   echo '<div class="col-lg-12 mb-4">
	   <div class="card bg-danger text-white shadow">
		   <div class="card-body">
			   Gagal
			   <div class="text-white-50 small">Data Gagal Tersimpan</div>
		   </div>
		   </div>
	   </div>';
   }
   echo '<script>setTimeout(function(){location.replace("toko_setting.php"); }, 1000);</script>';
}

if(isset($_GET['hapus_jenis'])){
   $aksi = $qb->RAW(
   "DELETE from jenis where id=?",[$_GET['hapus_jenis']]);
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
   echo '<script>setTimeout(function(){location.replace("toko_setting.php"); }, 1000);</script>';
}

?>

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Daftar Jenis Barang</h1>
<!-- DataTales Example -->
<div class="card shadow mb-4">
	<div class="card-body">

<form  role="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
	<div class="col-lg-12 mb-2">
			<?php if(isset($_GET['edit_jenis'])){ ?>
			<h1 class="h3 mb-2 text-gray-800">Edit</h1>
				<?php }else{ ?>
			<h1 class="h3 mb-2 text-gray-800">Tambah</h1>
				<?php } ?>
		<div class="input-group">
			<?php if(isset($_GET['edit_jenis'])){ ?>
			<input type="hidden" name="id_jenis" value="<?php echo $_GET['edit_jenis']; ?>" class="form-control">
				<?php } ?>
		  <input type="text" name="kode" placeholder="Kode" value="<?php if(isset($_GET['edit_jenis'])){ echo $_GET['edit_jenis'];}?>" class="form-control" required>
		  <input type="text" name="jenis" placeholder="Nama" value="<?php if(isset($_GET['edit_jenis'])){ echo $_GET['nama_jenis'];}?>" class="form-control" required>
		  <div class="input-group-prepend">
			<?php if(isset($_GET['edit_jenis'])){ ?>
			  <button type="submit" name="update_jenis" class="input-group-text"><span  id="">Update</span></button>
				<?php }else{ ?>
			  <button type="submit" name="simpan_jenis" class="input-group-text"><span  id="">Simpan</span></button>
				<?php } ?>
		  </div>

		</div>
	</div>
</form>
		<div class="table-responsive">
			<table class="display table table-bordered" id="table2" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>Kode</th>
						<th>Nama</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					foreach ($data_jenis as $row) {
						?>
					<tr>
						<td><?php echo $row->id;?></td>
						<td><?php echo $row->nama;?></td>
						<td>
						<center>
							<a href="toko_setting.php?edit_jenis=<?php echo $row->id;?>&&nama_jenis=<?php echo $row->nama;?>"><i class="fa-solid fa-pen-to-square"></i></a>
							&nbsp
							<a href="toko_setting.php?hapus_jenis=<?php echo $row->id;?>"><i class="fa-solid fa-trash-can"></i></a>
						</center>
						</td>
					</tr>
					<?php } ?>
					
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php


if(isset($_POST['simpan_satuan'])){
	$aksi = $qb->insert('satuan', [
	 'id' => $_POST['kode'],
	 'nama' => $_POST['satuan'],
	 'id_user' => $_SESSION['id_user']
   ]);

   if($aksi){
	   echo '<div class="col-lg-12 mb-4">
		   <div class="card bg-success text-white shadow">
			   <div class="card-body">
				   Berhasil
				   <div class="text-white-50 small">Data Tersimpan</div>
			   </div>
		   </div>
	   </div>';
   }else{
	   echo '<div class="col-lg-12 mb-4">
	   <div class="card bg-danger text-white shadow">
		   <div class="card-body">
			   Gagal
			   <div class="text-white-50 small">Data Gagal Tersimpan</div>
		   </div>
		   </div>
	   </div>';
   }
}

if(isset($_POST['update_satuan'])){

	$aksi = $qb->RAW("UPDATE satuan set nama=?,id=? WHERE id=?",[$_POST['satuan'],$_POST['kode'],$_POST['id_satuan']]);

   if($aksi){
	   echo '<div class="col-lg-12 mb-4">
		   <div class="card bg-success text-white shadow">
			   <div class="card-body">
				   Berhasil
				   <div class="text-white-50 small">Data Tersimpan</div>
			   </div>
		   </div>
	   </div>';
   }else{
	   echo '<div class="col-lg-12 mb-4">
	   <div class="card bg-danger text-white shadow">
		   <div class="card-body">
			   Gagal
			   <div class="text-white-50 small">Data Gagal Tersimpan</div>
		   </div>
		   </div>
	   </div>';
   }
   echo '<script>setTimeout(function(){location.replace("toko_setting.php"); }, 1000);</script>';
}

if(isset($_GET['hapus_satuan'])){
   $aksi = $qb->RAW(
   "DELETE from satuan where id=?",[$_GET['hapus_satuan']]);
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
   echo '<script>setTimeout(function(){location.replace("toko_setting.php"); }, 1000);</script>';
}

?>

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Daftar Satuan</h1>
<!-- DataTales Example -->
<div class="card shadow mb-4">
	<div class="card-body">

<form  role="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
	<div class="col-lg-12 mb-2">
			<?php if(isset($_GET['edit_satuan'])){ ?>
			<h1 class="h3 mb-2 text-gray-800">Edit</h1>
				<?php }else{ ?>
			<h1 class="h3 mb-2 text-gray-800">Tambah</h1>
				<?php } ?>
		<div class="input-group">
			<?php if(isset($_GET['edit_satuan'])){ ?>
			<input type="hidden" name="id_satuan" value="<?php echo $_GET['edit_satuan']; ?>" class="form-control">
				<?php } ?>
		  <input type="text" name="kode" placeholder="Kode" value="<?php if(isset($_GET['edit_satuan'])){ echo $_GET['edit_satuan'];}?>" class="form-control" required>
		  <input type="text" name="satuan" placeholder="Nama" value="<?php if(isset($_GET['edit_satuan'])){ echo $_GET['nama_satuan'];}?>" class="form-control" required>
		  <div class="input-group-prepend">
			<?php if(isset($_GET['edit_satuan'])){ ?>
			  <button type="submit" name="update_satuan" class="input-group-text"><span  id="">Update</span></button>
				<?php }else{ ?>
			  <button type="submit" name="simpan_satuan" class="input-group-text"><span  id="">Simpan</span></button>
				<?php } ?>
		  </div>

		</div>
	</div>
</form>
		<div class="table-responsive">
			<table class="display table table-bordered" id="table2" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>Kode</th>
						<th>Nama</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					foreach ($data_satuan as $row) {
						?>
					<tr>
						<td><?php echo $row->id;?></td>
						<td><?php echo $row->nama;?></td>
						<td>
						<center>
							<a href="toko_setting.php?edit_satuan=<?php echo $row->id;?>&&nama_satuan=<?php echo $row->nama;?>"><i class="fa-solid fa-pen-to-square"></i></a>
							&nbsp
							<a href="toko_setting.php?hapus_satuan=<?php echo $row->id;?>"><i class="fa-solid fa-trash-can"></i></a>
						</center>
						</td>
					</tr>
					<?php } ?>
					
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php


if(isset($_POST['simpan_konversi'])){
	$aksi = $qb->insert('konversi', [
	 'barang' => $_POST['barang'],
	 'konversi' => $_POST['konversi'],
	 'nilai' => $_POST['nilai'],
	 'harga' => $_POST['harga'],
	 'id_user' => $_SESSION['id_user']
   ]);

   if($aksi){
	   echo '<div class="col-lg-12 mb-4">
		   <div class="card bg-success text-white shadow">
			   <div class="card-body">
				   Berhasil
				   <div class="text-white-50 small">Data Tersimpan</div>
			   </div>
		   </div>
	   </div>';
   }else{
	   echo '<div class="col-lg-12 mb-4">
	   <div class="card bg-danger text-white shadow">
		   <div class="card-body">
			   Gagal
			   <div class="text-white-50 small">Data Gagal Tersimpan</div>
		   </div>
		   </div>
	   </div>';
   }
}

if(isset($_POST['update_konversi'])){

	$aksi = $qb->RAW("UPDATE konversi set barang=?,konversi=?,nilai=?,harga=? WHERE id=?",[$_POST['barang'],$_POST['konversi'],$_POST['nilai'],$_POST['harga'],$_POST['id_konversi']]);

   if($aksi){
	   echo '<div class="col-lg-12 mb-4">
		   <div class="card bg-success text-white shadow">
			   <div class="card-body">
				   Berhasil
				   <div class="text-white-50 small">Data Tersimpan</div>
			   </div>
		   </div>
	   </div>';
   }else{
	   echo '<div class="col-lg-12 mb-4">
	   <div class="card bg-danger text-white shadow">
		   <div class="card-body">
			   Gagal
			   <div class="text-white-50 small">Data Gagal Tersimpan</div>
		   </div>
		   </div>
	   </div>';
   }
   echo '<script>setTimeout(function(){location.replace("toko_setting.php"); }, 1000);</script>';
}

if(isset($_GET['hapus_konversi'])){
   $aksi = $qb->RAW(
   "DELETE from konversi where id=".$_GET['hapus_konversi'],[]);
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
   echo '<script>setTimeout(function(){location.replace("toko_setting.php"); }, 1000);</script>';
}

$data_konversi = $qb->RAW(
"SELECT
	 konversi.*
	,toko_menu.id as id_barang
	,toko_menu.nama as nama_barang
	, konversi.id as id
	, satuan.id as id_satuan
	, satuan.nama as nama_satuan
FROM konversi
join toko_menu on toko_menu.id=konversi.barang 
join satuan on satuan.id=konversi.konversi 
where konversi.id_user=?",[$_SESSION['id_user']]);

?>

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Daftar Konversi barang</h1>
<!-- DataTales Example -->
<div class="card shadow mb-4">
	<div class="card-body">

<form  role="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
	<div class="col-lg-12 mb-2">
			<?php if(isset($_GET['edit_konversi'])){ ?>
			<h1 class="h3 mb-2 text-gray-800">Edit</h1>
				<?php }else{ ?>
			<h1 class="h3 mb-2 text-gray-800">Tambah</h1>
				<?php } ?>
		<div class="input-group">
			<?php if(isset($_GET['edit_konversi'])){ ?>
			<input type="hidden" name="id_konversi" value="<?php echo $_GET['edit_konversi']; ?>" class="form-control">
				<?php } ?>
		  <select class="form-control" name="barang" >
				<?php 
				foreach ($data_menu as $key) {
					if(isset($_GET['barang_konversi']) &&  $_GET['barang_konversi'] == $key->id){
						echo"<option value='".$key->id."' selected>".$key->nama."</option>";
					}else{
						echo"<option value='".$key->id."'>".$key->nama."</option>";
					}
				}
				?>
		  </select>
		  <select class="form-control" name="konversi" >
				<?php 
				foreach ($data_satuan as $key) {
					if(isset($_GET['konversi']) &&  $_GET['konversi'] == $key->id){
						echo"<option value='".$key->id."' selected>".$key->nama."</option>";
					}else{
						echo"<option value='".$key->id."'>".$key->nama."</option>";
					}
				}
				?>
		  </select>
		  <!-- <input type="text" name="konversi" placeholder="Satuan" value="<?php if(isset($_GET['edit_konversi'])){ echo $_GET['konversi'];}?>" class="form-control" required> -->
		  <input type="number" name="harga" placeholder="Harga" value="<?php if(isset($_GET['edit_konversi'])){ echo $_GET['harga_konversi'];}?>" class="form-control" required>
		  <input type="number" name="nilai" placeholder="Banyak" value="<?php if(isset($_GET['edit_konversi'])){ echo $_GET['nilai_konversi'];}?>" class="form-control" required>
		  <div class="input-group-prepend">
			<?php if(isset($_GET['edit_konversi'])){ ?>
			  <button type="submit" name="update_konversi" class="input-group-text"><span  id="">Update</span></button>
				<?php }else{ ?>
			  <button type="submit" name="simpan_konversi" class="input-group-text"><span  id="">Simpan</span></button>
				<?php } ?>
		  </div>

		</div>
	</div>
</form>
		<div class="table-responsive">
			<table class="display table table-bordered" id="table3" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>Barang</th>
						<th>Satuan</th>
						<th>Banyak</th>
						<th>Harga</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					foreach ($data_konversi as $row) {
						?>
					<tr>
						<td><?php echo $row->nama_barang;?></td>
						<td><?php echo $row->nama_satuan;?></td>
						<td><?php echo $row->nilai;?></td>
						<td><?php echo convertToRupiah($row->harga);?></td>
						<td>
						<center>
							<a href="toko_setting.php?edit_konversi=<?php echo $row->id;?>&&barang_konversi=<?php echo $row->id_barang;?>&&konversi=<?php echo $row->id_satuan;?>&&nilai_konversi=<?php echo $row->nilai;?>&&harga_konversi=<?php echo $row->harga;?>"><i class="fa-solid fa-pen-to-square"></i></a>
							&nbsp
							<a href="toko_setting.php?hapus_konversi=<?php echo $row->id;?>"><i class="fa-solid fa-trash-can"></i></a>
						</center>
						</td>
					</tr>
					<?php } ?>
					
				</tbody>
			</table>
		</div>
	</div>
</div>

</div>

<?php require "partials/footer.php"; ?>
<script type="text/javascript">
    $(document).ready(function() {
  $('table.display').DataTable();
} );
</script>