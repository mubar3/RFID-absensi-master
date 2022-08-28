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

     	if(isset($_POST['simpan_menu'])){
     		$filename=$_FILES['gambar']['name'];
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

     		$aksi = $qb->insert('toko_menu', [
	          'harga' => $_POST['harga'],
	          'nama' => $_POST['nama'],
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

    ?>


    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Daftar Barang Toko</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">

    
    <form  role="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
	    <div class="col-lg-12 mb-2">
				<h1 class="h3 mb-2 text-gray-800">Tambah</h1>
	        <div class="input-group">
			  <input type="file" name="gambar" placeholder="gambar" class="form-control" required>
			  <input type="text" name="nama" placeholder="Nama" class="form-control" required>
			  <input type="number" name="harga" placeholder="Harga" class="form-control" required>
			  <div class="input-group-prepend">
			  	<button type="submit" name="simpan_menu" class="input-group-text"><span  id="">Simpan</span></button>
			  </div>

			</div>
	    </div>
	</form>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th>Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach ($data_menu as $data_user) {
                            ?>
                        <tr>
                            <td><img style="display: block; margin-left: auto;  margin-right: auto;   width: 70px;" class="img-responsive img" src="asset/menu/<?php echo $data_user->gambar;?>"></td>
                            <td><?php echo $data_user->nama;?></td>
                            <td><?php echo $data_user->harga;?></td>
                            <td>
                            <center>
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
</div>
<?php require "partials/footer.php"; ?>