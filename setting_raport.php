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

		if(isset($_POST['simpan_ujian'])){
     		$aksi = $qb->insert('j_ujian', [
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
     	if(isset($_POST['update_ujian'])){
	        $aksi = $qb->RAW(
    		"UPDATE j_ujian SET nama='".$_POST['jenis']."' where id=".$_POST['id_ujian'],[]);
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
     	if(isset($_GET['hapus_ujian'])){
	        $aksi = $qb->RAW(
    		"DELETE from j_ujian where id=".$_GET['hapus_ujian'],[]);
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

     	}


    $table='kelas';
    $data_ujian = $qb->RAW(
    "SELECT * FROM j_ujian where id_user=".$_SESSION['id_user'],[]);

    ?>
    <h1 class="h3 mb-2 text-gray-800">Daftar Ujian</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
     
    <form  role="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
	    <div class="col-lg-12 mb-2">
	    		<?php if(isset($_GET['edit_kelas'])){echo '<h1 class="h3 mb-2 text-gray-800">Edit</h1>';}
	    		else{echo'<h1 class="h3 mb-2 text-gray-800">Tambah</h1>';}?>	  			   
	        <div class="input-group">
	        	<?php if(isset($_GET['edit_ujian'])){ ?>
	        	<input type="hidden" name="id_ujian" value="<?php echo $_GET['edit_ujian']; ?>" class="form-control">
	        	<?php } ?>
			  <input type="text" name="jenis" value="<?php if(isset($_GET['edit_ujian'])){ echo $_GET['jenis'];}?>" placeholder="Jenis Ujian" class="form-control">
			  <div class="input-group-prepend">
			  	<?php if(isset($_GET['edit_ujian'])){echo '<button type="submit" name="update_ujian" class="input-group-text"><span  id="">Update</span></button>';}
	    		else{echo'<button type="submit" name="simpan_ujian" class="input-group-text"><span  id="">Simpan</span></button>';}?>
			  	
			  </div>

			</div>
	    </div>
	</form>
            <div class="table-responsive">
                <table class="display table table-bordered" id="table1" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no=1;
                        foreach ($data_ujian as $ujian) {
                            ?>
                        <tr>
                            <td><?php echo $no;?></td>
                            <td><?php echo $ujian->nama;?></td>
                            <td>
                            <center>
                            	<a href="setting_raport.php?edit_ujian=<?php echo $ujian->id;?>&&jenis=<?php echo $ujian->nama;?>"><i class="fa-solid fa-pen-to-square"></i></a>
                            	&nbsp
                            	<a href="setting_raport.php?hapus_ujian=<?php echo $ujian->id;?>"><i class="fa-solid fa-trash-can"></i></a>
                            </center>
                            </td>
                        </tr>
                        <?php $no++;} ?>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php

    if(isset($_POST['simpan_pelajaran'])){
     		$aksi = $qb->insert('m_pelajaran', [
	          'nama' => $_POST['nama'],
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
     	if(isset($_POST['update_pelajaran'])){
	        $aksi = $qb->RAW(
    		"UPDATE m_pelajaran SET nama='".$_POST['nama']."' where id=".$_POST['id_pelajaran'],[]);
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
     	if(isset($_GET['hapus_pelajaran'])){
	        $aksi = $qb->RAW(
    		"DELETE from m_pelajaran where id=".$_GET['hapus_pelajaran'],[]);
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


     	}


    $table='kelas';
    $data_pelajaran = $qb->RAW(
    "SELECT * FROM m_pelajaran where id_user=".$_SESSION['id_user'],[]);

    ?>
    <!-- <script type="text/javascript">
    	let url = `https://example.com?size=M&size=XL&price=29&sort=desc#clicked`

		const obj = new URL(url)
		obj.search = ''
		obj.hash = ''

		url = obj.toString()
		console.log(url)
    </script> -->
    <h1 class="h3 mb-2 text-gray-800">Daftar Pelajaran</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
     
    <form  role="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
	    <div class="col-lg-12 mb-2">
	    		<?php if(isset($_GET['edit_kelas'])){echo '<h1 class="h3 mb-2 text-gray-800">Edit</h1>';}
	    		else{echo'<h1 class="h3 mb-2 text-gray-800">Tambah</h1>';}?>	  			   
	        <div class="input-group">
	        	<?php if(isset($_GET['edit_pelajaran'])){ ?>
	        	<input type="hidden" name="id_pelajaran" value="<?php echo $_GET['edit_pelajaran']; ?>" class="form-control">
	        	<?php } ?>
			  <input type="text" name="nama" value="<?php if(isset($_GET['edit_pelajaran'])){ echo $_GET['nama'];}?>" placeholder="Jenis Mata Pelajaran" class="form-control">
			  <div class="input-group-prepend">
			  	<?php if(isset($_GET['edit_pelajaran'])){echo '<button type="submit" name="update_pelajaran" class="input-group-text"><span  id="">Update</span></button>';}
	    		else{echo'<button type="submit" name="simpan_pelajaran" class="input-group-text"><span  id="">Simpan</span></button>';}?>
			  	
			  </div>

			</div>
	    </div>
	</form>
            <div class="table-responsive">
                <table class="display table table-bordered" id="table2" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Mata Pelajaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no=1;
                        foreach ($data_pelajaran as $pelajaran) {
                            ?>
                        <tr>
                            <td><?php echo $no;?></td>
                            <td><?php echo $pelajaran->nama;?></td>
                            <td>
                            <center>
                            	<a href="setting_raport.php?edit_pelajaran=<?php echo $pelajaran->id;?>&&nama=<?php echo $pelajaran->nama;?>"><i class="fa-solid fa-pen-to-square"></i></a>
                            	&nbsp
                            	<a href="setting_raport.php?hapus_pelajaran=<?php echo $pelajaran->id;?>"><i class="fa-solid fa-trash-can"></i></a>
                            </center>
                            </td>
                        </tr>
                        <?php $no++;} ?>
                        
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