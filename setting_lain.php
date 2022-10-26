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


    
    <!-- setting ukuran kartu -->
    <h1 class="h3 mb-2 text-gray-800">Pengaturan lainnya</h1>
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
    </div>


		</div>
	</div>
</div>
<?php require "partials/footer.php"; ?>