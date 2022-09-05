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
     			$nama_depan=$depan;
	            $upload=move_uploaded_file($_FILES['depan']['tmp_name'],  "asset/desain/".$depan);
	                for($x=0;$x<20;$x++){
	                    if(filesize("asset/desain/".$depan)>50000)
	                    {resizer("asset/desain/".$depan, "asset/desain/".$depan, 70);}else{ break;}
	                    clearstatcache();
	                }
	        	}
     		$belakang=$_FILES['belakang']['name'];
     		if(!empty($belakang)){
     			if(!empty($nama_belakang)){
     			if(file_exists('asset/desain/'.$nama_belakang)){
     			unlink('asset/desain/'.$nama_belakang);}}
	     		$ext = pathinfo($belakang, PATHINFO_EXTENSION);
	     		$name = pathinfo($belakang, PATHINFO_FILENAME);
	            $belakang=$name.rand(111,9999).'.'.$ext;
     			$nama_belakang=$belakang;
	            $upload=move_uploaded_file($_FILES['belakang']['tmp_name'],  "asset/desain/".$belakang);
	                for($x=0;$x<20;$x++){
	                    if(filesize("asset/desain/".$belakang)>50000)
	                    {resizer("asset/desain/".$belakang, "asset/desain/".$belakang, 70);}else{ break;}
	                    clearstatcache();
	                }
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
</div>
<?php require "partials/footer.php"; ?>