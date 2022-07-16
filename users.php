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
     		$aksi = $qb->insert('user', [
	          'username' => $_POST['username'],
	          'pass' => md5($_POST['password']),
	          'role' => 2

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
     	if(isset($_POST['update_user'])){
     		// print_r("UPDATE user SET username='".$_POST['username']."',pass='".md5($_POST['password'])."' where id=".$_POST['id_user']);
     		// die();
	        $aksi = $qb->RAW(
    		"UPDATE user SET username='".$_POST['username']."',pass='".md5($_POST['password'])."' where id_user=".$_POST['id_user'],[]);
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
     	if(isset($_GET['hapus_user'])){
	        $aksi = $qb->RAW(
    		"DELETE from user where id_user=".$_GET['hapus_user'],[]);
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


    $data_user = $qb->RAW(
    "SELECT * FROM user",[]);

    ?>


    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Daftar User</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">

    
    <form  role="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
	    <div class="col-lg-12 mb-2">
			   <?php if(isset($_GET['edit_user'])){echo '<h1 class="h3 mb-2 text-gray-800">Edit</h1>';}
	    		else{echo'<h1 class="h3 mb-2 text-gray-800">Tambah</h1>';}?>
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
                            <th>Username</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach ($data_user as $data_user) {
                            ?>
                        <tr>
                            <td><?php echo $data_user->username;?></td>
                            <td>
                            <center>
                            	<a href="users.php?edit_user=<?php echo $data_user->id_user;?>&&username=<?php echo $data_user->username;?>"><i class="fa-solid fa-pen-to-square"></i></a>
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
<?php require "partials/footer.php"; ?>