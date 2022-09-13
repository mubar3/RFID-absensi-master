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

		if(isset($_POST['simpan_nilai'])){
			$nilai=$_POST['nilai'];
			$id_siswa=$_POST['id_siswa'];
			$ujian=$_POST['ujian'];
			$pelajaran=$_POST['pelajaran'];
			$kelas=$_POST['kelas'];

			$qb->RAW(
    		"DELETE from nilai where kelas=".$kelas." and id_ujian=".$ujian." and id_pelajaran=".$pelajaran,[]);
			for ($i=0; $i < count($nilai); $i++) { 
	     		$aksi = $qb->insert('nilai', [
		          'id_siswa' => $id_siswa[$i],
		          'id_pelajaran' => $pelajaran,
		          'kelas' => $kelas,
		          'id_ujian' => $ujian,
		          'nilai' => $nilai[$i],
		          'tampil' => 1,
		          'id_user' => $_SESSION['id_user']
		        ]);
			}

	        if($aksi){
	        	unset($_POST['ujian']);
	        	unset($_POST['kelas']);
	        	unset($_POST['pelajaran']);

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

    $data_kelas = $qb->RAW(
    "SELECT * FROM kelas where id_user=".$_SESSION['id_user'],[]);
    $data_pelajaran = $qb->RAW(
    "SELECT * FROM m_pelajaran where id_user=".$_SESSION['id_user'],[]);
    $data_ujian = $qb->RAW(
    "SELECT * FROM j_ujian where id_user=".$_SESSION['id_user'],[]);

    ?>
    <h1 class="h3 mb-2 text-gray-800">Tambahkan Nilai</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
     
    <form  role="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
	    <div class="col-lg-12 mb-2">
	    		<!-- <h1 class="h3 mb-2 text-gray-800">Tambah</h1>	  			    -->
	        <div class="input-group">
			  <select class="form-control" name="kelas" required>
			  	<option value="">Pilih Kelas</option>
			  	<?php 
			  		foreach ($data_kelas as $kelas) {
			  			$select='';
			  			if($kelas->id_kelas == $_POST['kelas']){$select='selected';}
			  			echo '<option value="'.$kelas->id_kelas.'" '.$select.'>'.$kelas->kelas.'</option>';
			  		}
			  	?>
			  </select>
			  <select class="form-control" name="pelajaran" required>
			  	<option value="">Mata Pelajaran</option>
			  	<?php 
			  		foreach ($data_pelajaran as $pelajaran) {
			  			$select='';
			  			if($pelajaran->id == $_POST['pelajaran']){$select='selected';}
			  			echo '<option value="'.$pelajaran->id.'" '.$select.'>'.$pelajaran->nama.'</option>';
			  		}
			  	?>
			  </select>
			  <select class="form-control" name="ujian" required>
			  	<option value="">Ujian</option>
			  	<?php 
			  		foreach ($data_ujian as $ujian) {
			  			$select='';
			  			if($ujian->id == $_POST['ujian']){$select='selected';}
			  			echo '<option value="'.$ujian->id.'" '.$select.'>'.$ujian->nama.'</option>';
			  		}
			  	?>
			  </select>
			  <div class="input-group-prepend">
			  	<button type="submit" name="cari_data" class="input-group-text"><span  id="">Cari</span></button>
			  </div>

			</div>
	    </div>
	</form>
	<form  role="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
		<?php 
		if(isset($_POST['cari_data'])){
			echo'<input type="hidden" class="form-control" name="kelas" value="'.$_POST['kelas'].'" readonly>';
			echo'<input type="hidden" class="form-control" name="pelajaran" value="'.$_POST['pelajaran'].'" readonly>';
			echo'<input type="hidden" class="form-control" name="ujian" value="'.$_POST['ujian'].'" readonly>';
			$data_siswa = $qb->RAW(
    		"SELECT siswa.*,nilai.nilai FROM nilai 
    		left join siswa on siswa.id=nilai.id_siswa
    		where nilai.kelas=".$_POST['kelas']." and nilai.id_pelajaran=".$_POST['pelajaran']." and nilai.id_ujian=".$_POST['ujian']." and nilai.id_user=".$_SESSION['id_user'],[]);
    		if (!array_key_exists(0, $data_siswa)) {
    			$data_siswa = $qb->RAW(
    			"SELECT * FROM siswa where kelas=".$_POST['kelas']." and user_input=".$_SESSION['id_user'],[]);

	    		foreach ($data_siswa as $siswa) {
	    			echo'<div class="input-group">
				  			<input type="text" class="form-control" value="'.$siswa->nis.'" readonly>
				  			<input type="hidden" class="form-control" name="id_siswa[]" value="'.$siswa->id.'" readonly>
				  			<input type="text" class="form-control" value="'.$siswa->nama.'" readonly>
				  			<input type="number" class="form-control" name="nilai[]" placeholder="Nilai"></input>
						  

						</div>';
	    		}

    		}else{
    			foreach ($data_siswa as $siswa) {
	    			echo'<div class="input-group">
				  			<input type="text" class="form-control" value="'.$siswa->nis.'" readonly>
				  			<input type="hidden" class="form-control" name="id_siswa[]" value="'.$siswa->id.'" readonly>
				  			<input type="text" class="form-control" value="'.$siswa->nama.'" readonly>
				  			<input type="number" class="form-control" name="nilai[]" value="'.$siswa->nilai.'" placeholder="Nilai"></input>
						  

						</div>';
	    		}
    		}
			
			echo '<br><center><button type="submit" name="simpan_nilai" class="btn btn-primary"><span>simpan</span></button></center>';
		}

			?>
	</form>
        </div>
    </div>

    

</div>
<?php require "partials/footer.php"; ?>
<script type="text/javascript">
    $(document).ready(function() {
  $('table.display').DataTable();
} );
</script>