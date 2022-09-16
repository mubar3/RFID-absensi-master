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

     	if(isset($_POST['simpan_user'])){
     		$user = $qb->RAW(
    			"SELECT * FROM user WHERE username='".$_POST['username'],[]);
     		if (!array_key_exists(0, $user)) {

			$kabupaten='';
     		$kecamatan='';
     		$lembaga='';
     		
     		if(isset($_POST['kabupaten'])){$kabupaten=$_POST['kabupaten'];}
     		if(isset($_POST['kecamatan'])){$kecamatan=$_POST['kecamatan'];}
     		if(isset($_POST['lembaga'])){$lembaga=$_POST['lembaga'];}

     		$aksi = $qb->insert('user', [
	          'username' => $_POST['username'],
	          'pass' => md5($_POST['password']),
	          'provinsi' => $_POST['provinsi'],
	          'kabupaten' => $kabupaten,
	          'kecamatan' => $kecamatan,
	          'lembaga' => $lembaga,
	          'role' => 2

	        ]);


	        if($aksi){
	     		// buat setting kartu default
	     		$user = $qb->RAW(
	    			"SELECT * FROM user WHERE username='".$_POST['username']."' and pass=?",[md5($_POST['password'])]);
	     		$user = $user[0]; 

	     		$code = array("nama", "ttl", "nis", "jk", "nisn", "agama", "alamat");
	     		for ($i=0; $i < count($code); $i++) { 
	     			$qb->insert('data_kartu', [
			          'user' => $user->id_user,
			          'code' => $code[$i],
			          'urutan' => $i+1,
			          'status' => 1
			        ]);
	     		}
	     		$code2 = array("kelas", "rfid","alamat_saja");
	     		$a=8;
	     		for ($i=0; $i < count($code2); $i++) { 
	     			$qb->insert('data_kartu', [
			          'user' => $user->id_user,
			          'code' => $code2[$i],
			          'urutan' =>$a,
			          'status' => 0
			        ]);
			        $a++;
	     		}
	     		// buat setting kartu default
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
	    	}else{
	        	echo '<div class="col-lg-12 mb-4">
		        <div class="card bg-danger text-white shadow">
		            <div class="card-body">
		                Gagal
		                <div class="text-white-50 small">Username Sudah Ada</div>
		            </div>
		       	 </div>
		    	</div>';

	    	}
     	}
     	if(isset($_POST['update_user'])){
     		$kabupaten='';
     		$kecamatan='';
     		$lembaga='';
     		
     		if(isset($_POST['kabupaten'])){$kabupaten=$_POST['kabupaten'];}
     		if(isset($_POST['kecamatan'])){$kecamatan=$_POST['kecamatan'];}
     		if(isset($_POST['lembaga'])){$lembaga=$_POST['lembaga'];}
     		// die();
     		// print_r("UPDATE user SET username='".$_POST['username']."',pass='".md5($_POST['password'])."' where id=".$_POST['id_user']);
     		// die();
	        $aksi = $qb->RAW(
    		"UPDATE user SET username='".$_POST['username']."',
    		provinsi='".$_POST['provinsi']."',
    		kabupaten='".$kabupaten."',
    		kecamatan='".$kecamatan."',
    		lembaga='".$lembaga."',
    		pass='".md5($_POST['password'])."' where id_user=".$_POST['id_user'],[]);
	        if($aksi){
	        	// buat setting kartu default
	        	$qb->RAW("DELETE from data_kartu where user=".$_POST['id_user'],[]);
	     		$user = $qb->RAW(
	    			"SELECT * FROM user WHERE username='".$_POST['username']."' and pass=?",[md5($_POST['password'])]);
	     		$user = $user[0]; 

	     		$code = array("nama", "ttl", "nis", "jk", "nisn", "agama", "alamat");
	     		for ($i=0; $i < count($code); $i++) { 
	     			$qb->insert('data_kartu', [
			          'user' => $_POST['id_user'],
			          'code' => $code[$i],
			          'urutan' => $i+1,
			          'status' => 1
			        ]);
	     		}
	     		$code2 = array("kelas", "rfid","alamat_saja");
	     		$a=8;
	     		for ($i=0; $i < count($code2); $i++) { 
	     			$qb->insert('data_kartu', [
			          'user' => $_POST['id_user'],
			          'code' => $code2[$i],
			          'urutan' =>$a,
			          'status' => 0
			        ]);
			        $a++;
	     		}
	     		// buat setting kartu default
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
	        echo '<script>setTimeout(function(){location.replace("users.php"); }, 1000);</script>'; 
	    }
     	if(isset($_GET['hapus_user'])){
	        $aksi = $qb->RAW(
    		"DELETE from user where id_user=".$_GET['hapus_user'],[]);
	        if($aksi){
				$qb->RAW("DELETE from data_kartu where user=".$_GET['hapus_user'],[]);
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
	        echo '<script>setTimeout(function(){location.replace("users.php"); }, 1000);</script>'; 
     	}


    $data_user = $qb->RAW(
    "SELECT * FROM user where role != 3",[]);

    ?>


    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Daftar User</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">

    
    <form  role="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
			  <center> <?php if(isset($_GET['edit_user'])){echo '<h1 class="h3 mb-2 text-gray-800">Edit</h1>';}
	    		else{echo'<h1 class="h3 mb-2 text-gray-800">Tambah</h1>';}?></center>
	    <div class="col-lg-12 mb-2">
	    	<div class="input-group">
	    		<select id="provinsi" name="provinsi" class="form-control" >
		            <?php 
		            $data= $qb->RAW("SELECT * FROM provinsi",[]);
		            ?>
		            <option value="">Provinsi</option>
		            <?php foreach ($data as $data) {
		            	$selected='';
		            	if($_GET['provinsi'] == $data->id_prov){$selected='selected';}
		                echo '<option value="'.$data->id_prov.'" '.$selected.'>'.$data->nama_provinsi.'</option>';
		            }?>
		        </select>
		        <select id="kabupaten" name="kabupaten" class="form-control" >
		            <?php 
		            $data= $qb->RAW("SELECT * FROM kabupaten",[]);
		            ?>
		            <option value="">Kab</option>
		            <?php foreach ($data as $data) {
		            	$selected='';
		            	if($_GET['kabupaten'] == $data->id_kab){$selected='selected';}
		                echo '<option id="kabupaten" class="'.$data->id_prov.'" value="'.$data->id_kab.'" '.$selected.'>'.$data->nama_kabupaten.'</option>';
		            }?>
		        </select>
	    	</div>
	    </div>
	    <div class="col-lg-12 mb-2">
	    	<div class="input-group">
	    		<select onchange="myFunction()" id="kecamatan" name="kecamatan" class="form-control" >
		            <?php 
		            $data= $qb->RAW("SELECT * FROM kecamatan",[]);
		            ?>
		            <option value="">Kec</option>
		            <?php foreach ($data as $data) {
		            	$selected='';
		            	if($_GET['kecamatan'] == $data->id_kec){$selected='selected';}
		                echo '<option id="kecamatan" class="'.$data->id_kab.'" value="'.$data->id_kec.'" '.$selected.'>'.$data->nama_kecamatan.'</option>';
		            }?>
		        </select>
	    	</div>
	    </div>
	    <div class="col-lg-12 mb-2">
	    	<div class="input-group">
	    		<input type="text" class="form-control" value="<?php if(isset($_GET['edit_user'])){echo $_GET['lembaga'];} ?>" name="lembaga" placeholder="Lembaga/Sekolah" >
	    	</div>
	    </div>
	    <div class="col-lg-12 mb-2">
	        <div class="input-group">
	        	<?php if(isset($_GET['edit_user'])){ ?>
	        	<input type="hidden" name="id_user" value="<?php echo $_GET['edit_user']; ?>" class="form-control">
	        	<?php } ?>
			  <input type="text" name="username" <?php if(isset($_GET['edit_user'])){ echo 'value="'.$_GET['username'].'"'; }?> placeholder="Username" class="form-control">
			  <input type="password" name="password" placeholder="Password" class="form-control">
			  <div class="input-group-prepend">
			  	<?php if(isset($_GET['edit_user'])){echo '<button type="submit" name="update_user" class="input-group-text"><span  id="">Update</span></button>';}
	    		else{echo'<button type="submit" name="simpan_user" class="input-group-text"><span  id="">Simpan</span></button>';}?>
			  </div>

			</div>
	    </div>
	</form>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Lembaga</th>
                            <th>Username</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach ($data_user as $data_user) {
                            ?>
                        <tr>
                            <td><?php echo $data_user->lembaga;?></td>
                            <td><?php echo $data_user->username;?></td>
                            <td>
                            <center>
                            	<a href="users.php?edit_user=<?php echo $data_user->id_user;?>&&username=<?php echo $data_user->username;?>&&lembaga=<?php echo $data_user->lembaga;?>&&provinsi=<?php echo $data_user->provinsi;?>&&kabupaten=<?php echo $data_user->kabupaten;?>&&kecamatan=<?php echo $data_user->kecamatan;?>"><i class="fa-solid fa-pen-to-square"></i></a>
                            	&nbsp
                            	<a href="users.php?hapus_user=<?php echo $data_user->id_user;?>"><i class="fa-solid fa-trash-can"></i></a>
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

<script src="asset/js/jquery-1.10.2.min.js"></script>
  <script src="asset/js/jquery.chained.min.js"></script>
<script>
    $("#kabupaten").chained("#provinsi");
    $("#kecamatan").chained("#kabupaten");
  </script>
<?php require "partials/footer.php"; ?>