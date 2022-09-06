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

		if(isset($_POST['simpan_kelas'])){
     		$aksi = $qb->insert('kelas', [
	          'kelas' => $_POST['kelas'],
	          'spp' => $_POST['spp'],
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
     	if(isset($_POST['update_kelas'])){
	        $aksi = $qb->RAW(
    		"UPDATE kelas SET kelas='".$_POST['kelas']."',spp='".$_POST['spp']."' where id_kelas=".$_POST['id_kelas'],[]);
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
	        echo '<script>setTimeout(function(){location.replace("setting_absen.php"); }, 1000);</script>'; 
     	}
     	if(isset($_GET['hapus_kelas'])){
	        $aksi = $qb->RAW(
    		"DELETE from kelas where id_kelas=".$_GET['hapus_kelas'],[]);
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
	        echo '<script>setTimeout(function(){location.replace("setting_absen.php"); }, 1000);</script>'; 
     	}


     	if(isset($_POST['simpan_aktif'])){
     		$aksi = $qb->insert('jadwal', [
	          'hari' => $_POST['hari'],
	          'jam_mulai' => $_POST['jam_mulai'],
	          'jam_akhir' => $_POST['jam_akhir'],
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
     	if(isset($_POST['update_aktif'])){
	        $aksi = $qb->RAW(
    		"UPDATE jadwal SET hari='".$_POST['hari']."',jam_mulai='".$_POST['jam_mulai']."',jam_akhir='".$_POST['jam_akhir']."' where id=".$_POST['id_aktif'],[]);
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
	        echo '<script>setTimeout(function(){location.replace("setting_absen.php"); }, 1000);</script>'; 
     	}
     	if(isset($_GET['hapus_aktif'])){
	        $aksi = $qb->RAW(
    		"DELETE from jadwal where id=".$_GET['hapus_aktif'],[]);
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
	        echo '<script>setTimeout(function(){location.replace("setting_absen.php"); }, 1000);</script>'; 
     	}


    $table='kelas';
    $data_kelas = $qb->RAW(
    "SELECT * FROM kelas where id_user=".$_SESSION['id_user'],[]);
    $data_hari_aktif = $qb->RAW(
    "SELECT * FROM jadwal where id_user=".$_SESSION['id_user'],[]);

    ?>
    <!-- Page Heading -->
    <!-- <h1 class="h3 mb-2 text-gray-800">Pengaturan Aplikasi</h1> -->

    <!-- DataTales Example -->
    <!-- <div class="card shadow mb-4">
        <div class="card-body">
	    <form  role="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
	    	<span>Ubah Foto Login</span>	
		  	<input type="file" name="foto_login" placeholder="Jam Selesai" class="form-control">
		  	<br>
		  	<button type="submit" name="simpan_setting" class="input-group-text"><span  id="">Simpan</span></button>'
        </form>
        </div>
    </div> -->

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Daftar Kelas</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
     
    <form  role="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
	    <div class="col-lg-12 mb-2">
	    		<?php if(isset($_GET['edit_kelas'])){echo '<h1 class="h3 mb-2 text-gray-800">Edit</h1>';}
	    		else{echo'<h1 class="h3 mb-2 text-gray-800">Tambah</h1>';}?>	  			   
	        <div class="input-group">
	        	<?php if(isset($_GET['edit_kelas'])){ ?>
	        	<input type="hidden" name="id_kelas" value="<?php echo $_GET['edit_kelas']; ?>" class="form-control">
	        	<?php } ?>
			  <input type="text" name="kelas" value="<?php if(isset($_GET['edit_kelas'])){ echo $_GET['kelas'];}?>" placeholder="Kelas" class="form-control">
			  <input type="text" name="spp" value="<?php if(isset($_GET['edit_kelas'])){ echo $_GET['spp'];}?>" placeholder="Jumlah SPP" class="form-control">
			  <div class="input-group-prepend">
			  	<?php if(isset($_GET['edit_kelas'])){echo '<button type="submit" name="update_kelas" class="input-group-text"><span  id="">Update</span></button>';}
	    		else{echo'<button type="submit" name="simpan_kelas" class="input-group-text"><span  id="">Simpan</span></button>';}?>
			  	
			  </div>

			</div>
	    </div>
	</form>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kelas</th>
                            <th>SPP</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no=1;
                        foreach ($data_kelas as $kelas) {
                            ?>
                        <tr>
                            <td><?php echo $no;?></td>
                            <td><?php echo $kelas->kelas;?></td>
                            <td><?php echo $kelas->spp;?></td>
                            <td>
                            <center>
                            	<a href="setting_absen.php?edit_kelas=<?php echo $kelas->id_kelas;?>&&kelas=<?php echo $kelas->kelas;?>&&spp=<?php echo $kelas->spp;?>"><i class="fa-solid fa-pen-to-square"></i></a>
                            	&nbsp
                            	<a href="setting_absen.php?hapus_kelas=<?php echo $kelas->id_kelas;?>"><i class="fa-solid fa-trash-can"></i></a>
                            </center>
                            </td>
                        </tr>
                        <?php $no++;} ?>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Hari Aktif</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">

    
    <form  role="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
	    <div class="col-lg-12 mb-2">
			   <?php if(isset($_GET['edit_aktif'])){echo '<h1 class="h3 mb-2 text-gray-800">Edit</h1>';}
	    		else{echo'<h1 class="h3 mb-2 text-gray-800">Tambah</h1>';}?>
	        <div class="input-group">
	        	<?php if(isset($_GET['edit_aktif'])){ ?>
	        	<input type="hidden" name="id_aktif" value="<?php echo $_GET['edit_aktif']; ?>" class="form-control">
	        	<?php } ?>
			  <select name="hari" placeholder="Hari" class="form-control">
			  	<option value="" >Pilih Hari</option>
			  	<option value="Minggu" <?php if(isset($_GET['edit_aktif'])){ if($_GET['hari']=='Minggu'){echo "selected";}}?> >Minggu</option>
			  	<option value="Senin" <?php if(isset($_GET['edit_aktif'])){ if($_GET['hari']=='Senin'){echo "selected";}}?> >Senin</option>
			  	<option value="Selasa" <?php if(isset($_GET['edit_aktif'])){ if($_GET['hari']=='Selasa'){echo "selected";}}?> >Selasa</option>
			  	<option value="Rabu" <?php if(isset($_GET['edit_aktif'])){ if($_GET['hari']=='Rabu'){echo "selected";}}?> >Rabu</option>
			  	<option value="Kamis" <?php if(isset($_GET['edit_aktif'])){ if($_GET['hari']=='Kamis'){echo "selected";}}?> >Kamis</option>
			  	<option value="Jumat" <?php if(isset($_GET['edit_aktif'])){ if($_GET['hari']=='Jumat'){echo "selected";}}?> >Jumat</option>
			  	<option value="Sabtu" <?php if(isset($_GET['edit_aktif'])){ if($_GET['hari']=='Sabtu'){echo "selected";}}?> >Sabtu</option>
			  </select>
			  <input type="time" name="jam_mulai" <?php if(isset($_GET['edit_aktif'])){ echo 'value="'.$_GET['jam_mulai'].'"'; }?> placeholder="Jam Mulai" class="form-control">
			  <input type="time" name="jam_akhir" <?php if(isset($_GET['edit_aktif'])){ echo 'value="'.$_GET['jam_akhir'].'"'; }?> placeholder="Jam Selesai" class="form-control">
			  <div class="input-group-prepend">
			  	<?php if(isset($_GET['edit_aktif'])){echo '<button type="submit" name="update_aktif" class="input-group-text"><span  id="">Update</span></button>';}
	    		else{echo'<button type="submit" name="simpan_aktif" class="input-group-text"><span  id="">Simpan</span></button>';}?>
			  </div>

			</div>
	    </div>
	</form>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Hari</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach ($data_hari_aktif as $hari) {
                            ?>
                        <tr>
                            <td><?php echo $hari->hari;?></td>
                            <td><?php echo $hari->jam_mulai;?></td>
                            <td><?php echo $hari->jam_akhir;?></td>
                            <td>
                            <center>
                            	<a href="setting_absen.php?edit_aktif=<?php echo $hari->id;?>&&hari=<?php echo $hari->hari;?>&&jam_mulai=<?php echo $hari->jam_mulai;?>&&jam_akhir=<?php echo $hari->jam_akhir;?>"><i class="fa-solid fa-pen-to-square"></i></a>
                            	&nbsp
                            	<a href="setting_absen.php?hapus_aktif=<?php echo $hari->id;?>"><i class="fa-solid fa-trash-can"></i></a>
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