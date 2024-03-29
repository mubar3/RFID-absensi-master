<?php 
require "partials/head.php";
require "partials/sidebar.php"; ?>
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

   
     	if(isset($_POST['simpan_kartu'])){
     		$nama_depan=$_POST['depan_lama'];
     		$nama_belakang=$_POST['belakang_lama'];

     		$depan=$_FILES['depan']['name'];
     		if(!empty($depan)){
     			if(!empty($nama_depan)){
     			if(file_exists('asset/desain/'.$nama_depan)){
     			unlink('asset/desain/'.$nama_depan);}}
	     		$ext = pathinfo($depan, PATHINFO_EXTENSION);
	     		$name = pathinfo($depan, PATHINFO_FILENAME);
	            $depan=$name.rand(111,9999).'.'.$ext;
	            while(file_exists('asset/desain/'.$depan)){$depan=$name.rand(111,9999).'.'.$ext;}
     			$nama_depan=$depan;
	            $upload=move_uploaded_file($_FILES['depan']['tmp_name'],  "asset/desain/".$depan);
	                // for($x=0;$x<20;$x++){
	                //     if(filesize("asset/desain/".$depan)>50000)
	                //     {resizer("asset/desain/".$depan, "asset/desain/".$depan, 70);}else{ break;}
	                //     clearstatcache();
	                // }
	        	}
     		$belakang=$_FILES['belakang']['name'];
     		if(!empty($belakang)){
     			if(!empty($nama_belakang)){
     			if(file_exists('asset/desain/'.$nama_belakang)){
     			unlink('asset/desain/'.$nama_belakang);}}
	     		$ext = pathinfo($belakang, PATHINFO_EXTENSION);
	     		$name = pathinfo($belakang, PATHINFO_FILENAME);
	            $belakang=$name.rand(111,9999).'.'.$ext;
	            while(file_exists('asset/desain/'.$belakang)){$belakang=$name.rand(111,9999).'.'.$ext;}
     			$nama_belakang=$belakang;
	            $upload=move_uploaded_file($_FILES['belakang']['tmp_name'],  "asset/desain/".$belakang);
	                // for($x=0;$x<20;$x++){
	                //     if(filesize("asset/desain/".$belakang)>50000)
	                //     {resizer("asset/desain/".$belakang, "asset/desain/".$belakang, 70);}else{ break;}
	                //     clearstatcache();
	                // }
	        	}

	        // print_r("UPDATE user SET kar_depan='".$nama_depan."', kar_belakang='".$nama_belakang."' where id_user=".$_SESSION['id_user']);die();	
     		$aksi = $qb->RAW(
            "UPDATE user SET kar_depan='".$nama_depan."', kar_belakang='".$nama_belakang."' where id_user=".$_SESSION['id_user'],[]);

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
     	
     	if(isset($_GET['hapus_menu'])){
	        $aksi = $qb->RAW(
    		"DELETE from toko_menu where id=".$_GET['hapus_menu'],[]);
	        if($aksi){
                unlink('asset/menu/'.$_GET['foto_menu']);
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
     	}


    $data_menu = $qb->RAW(
    "SELECT * FROM toko_menu where id_user=?",[$_SESSION['id_user']]);

    $data_user = $qb->RAW(
    "SELECT * FROM user where id_user=?",[$_SESSION['id_user']]);
    $depan='depan.jpg';
    $belakang='belakang.jpg';
    if (array_key_exists(0, $data_user)) {
    	$data_user=$data_user[0];
    	if(!empty($data_user->kar_depan)){
    		$depan=$data_user->kar_depan;
    	}
    	if(!empty($data_user->kar_belakang)){
    		$belakang=$data_user->kar_belakang;
    	}
    }
    ?>


    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Desain Kartu</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
		<form  role="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
        <div class="card-body">
			<div class="row">
			  <div class="col-sm-6">
			  	<img class="card-img-top" src="asset/desain/<?php echo $depan; ?>">
			  	<input type="hidden" class="form-control" name="depan_lama" value="<?php echo $data_user->kar_depan; ?>">
			  	<input type="file" class="form-control" name="depan">
			  </div>
			  <div class="col-sm-6">
			    <img class="card-img-top" src="asset/desain/<?php echo $belakang; ?>">
			  	<input type="hidden" class="form-control" name="belakang_lama" value="<?php echo $data_user->kar_belakang; ?>">
			  	<input type="file" class="form-control" name="belakang">
			  </div>
			</div>
        </div>
        <center><button type="submit" name="simpan_kartu" class="btn btn-primary"><span  id="">Update</span></button></center><br>
		</form>
		<form  role="form" action="tampil_cetak.php" method="post" autocomplete="off" enctype="multipart/form-data" >
			<?php

			    $siswa = $qb->RAW(
			    "SELECT * FROM siswa where user_input=?",[$_SESSION['id_user']]);
			    if (array_key_exists(0, $siswa)) {
			    $siswa=$siswa[0];
			?>
			<center><button type="submit" name="simpan_user" formtarget="_blank" class="input-group-text"><span  id="">Contoh Kartu</span></button></center><br>
			<input name='selector[]' type='hidden' name='tandai' class='minimal flat' value='<?php echo $siswa->id; ?>'>
			<?php 
				}
			?>
        </form>
    </div>

    <!-- setting ukuran kartu -->
    <h1 class="h3 mb-2 text-gray-800">Pengaturan kartu</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
        	<form  role="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
        	<table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Ukuran (px)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Margin Kiri Foto</td>
                            <td><input type="text" class="form-control" value="<?php echo $data_user->kar_img_kiri; ?>" name="data[kar_img_kiri]"></td>
                        </tr>
                        <tr>
                            <td>Margin Atas Foto</td>
                            <td><input type="text" class="form-control" value="<?php echo $data_user->kar_img_atas; ?>" name="data[kar_img_atas]"></td>
                        </tr>
                        <tr>
                            <td>Lebar Foto</td>
                            <td><input type="text" class="form-control" value="<?php echo $data_user->kar_img_lebar; ?>" name="data[kar_img_lebar]"></td>
                        </tr>
                        <tr>
                            <td>Tinggi Foto</td>
                            <td><input type="text" class="form-control" value="<?php echo $data_user->kar_img_tinggi; ?>" name="data[kar_img_tinggi]"></td>
                        </tr>
                        <tr>
                            <td>Margin Kiri QR</td>
                            <td><input type="text" class="form-control" value="<?php echo $data_user->kar_qr_kiri; ?>" name="data[kar_qr_kiri]"></td>
                        </tr>
                        <tr>
                            <td>Margin Atas QR</td>
                            <td><input type="text" class="form-control" value="<?php echo $data_user->kar_qr_atas; ?>" name="data[kar_qr_atas]"></td>
                        </tr>
                        <tr>
                            <td>Lebar QR</td>
                            <td><input type="text" class="form-control" value="<?php echo $data_user->kar_qr_lebar; ?>" name="data[kar_qr_lebar]"></td>
                        </tr>
                        <tr>
                            <td>Tinggi QR</td>
                            <td><input type="text" class="form-control" value="<?php echo $data_user->kar_qr_tinggi; ?>" name="data[kar_qr_tinggi]"></td>
                        </tr>
                        <tr>
                            <td>Margin Atas 'DIkeluarkan tanggal'</td>
                            <td><input type="text" class="form-control" value="<?php echo $data_user->dikeluarkan_atas; ?>" name="data[dikeluarkan_atas]"></td>
                        </tr>
                        <tr><!-- 
                            <td>Saldo Maksimal Kartu/saldo maksimal topup</td>
                            <td><input type="text" class="form-control" value="<?php echo $data_user->saldo_max; ?>" name="data[saldo_max]"></td>
                        </tr> -->
                    </tbody>
                </table>
                <center><button type="submit" name="simpan_ukuran" class="btn btn-primary"><span  id="">Simpan</span></button></center>
            	</form>
        </div>
    </div>

    <!-- setting ukuran kartu -->
    <!-- <h1 class="h3 mb-2 text-gray-800">Pengaturan lainnya</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
        	<form  role="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
        	<table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Kode Formulir</td>
                            <td><input type="text" class="form-control" value="<?php echo $data_user->kode_input; ?>" name="data[kode_input]"></td>
                        </tr>
                        <tr>
                            <td>lama Peminjaman</td>
                            <td><input type="Number" class="form-control" value="<?php echo $data_user->pem_max; ?>" name="data[pem_max]" placeholder="Hari"></td>
                        </tr>
                    </tbody>
                </table>
                <center><button type="submit" name="simpan_ukuran" class="btn btn-primary"><span  id="">Simpan</span></button></center>
            	</form>
        </div>
    </div> -->

    <!-- setting urutan data kartu -->
    <h1 class="h3 mb-2 text-gray-800">Data Kartu</h1>
    <?php 
		if(isset($_POST['simpan_urutan'])){
			$id=$_POST['id'];
			$urutan=$_POST['urutan'];
			$status=$_POST['status'];

			for ($i=0; $i < count($id); $i++) { 
				$aksi = $qb->RAW(
	            "UPDATE data_kartu SET urutan='".$urutan[$i]."', status='".$status[$i]."' where id=".$id[$i],[]);
			}

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


    	 $data_urutan = $qb->RAW(
    		"SELECT * FROM data_kartu where user=? order by urutan asc",[$_SESSION['id_user']]);

    	 // print_r($data_urutan);die();
    ?>
	 <div class="card shadow mb-4">
        <div class="card-body">
        	<form  role="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
        	<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis</th>
                            <th>Urutan</th>
                            <th>Ditampilkan/Tidak</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach ($data_urutan as $urutan) {
                            ?>
                        <tr>
                        	<td><?php echo $urutan->urutan; ?></td>
                        	<input type="hidden" value="<?php echo $urutan->id; ?>" name="id[]">
                            <td><?php echo $urutan->code;?></td>
                            <td>
                            	<select class="form-control" name="urutan[]">
                            		<option value="1" <?php if($urutan->urutan == 1){echo 'selected';}?> >1</option>
                            		<option value="2" <?php if($urutan->urutan == 2){echo 'selected';}?>>2</option>
                            		<option value="3" <?php if($urutan->urutan == 3){echo 'selected';}?>>3</option>
                            		<option value="4" <?php if($urutan->urutan == 4){echo 'selected';}?>>4</option>
                            		<option value="5" <?php if($urutan->urutan == 5){echo 'selected';}?>>5</option>
                            		<option value="6" <?php if($urutan->urutan == 6){echo 'selected';}?>>6</option>
                            		<option value="7" <?php if($urutan->urutan == 7){echo 'selected';}?>>7</option>
                            		<option value="8" <?php if($urutan->urutan == 8){echo 'selected';}?>>8</option>
                            		<option value="9" <?php if($urutan->urutan == 9){echo 'selected';}?>>9</option>
                            		<option value="10" <?php if($urutan->urutan == 10){echo 'selected';}?>>10</option>
                            	</select>
                            </td>
                            <td>
                            	<select class="form-control" name="status[]">
                            		<option value="1" <?php if($urutan->status == 1){echo 'selected';}?> >Tampil</option>
                            		<option value="0" <?php if($urutan->status == 0){echo 'selected';}?> >Tidak</option>
                            	</select>
                            </td>
                        </tr>
                        <?php } ?>
                        
                    </tbody>
                </table>
                <center><button type="submit" name="simpan_urutan" class="btn btn-primary"><span  id="">Simpan</span></button></center>
            	</form>
            </div>

		</div>
	</div>
</div>
<?php require "partials/footer.php"; ?>