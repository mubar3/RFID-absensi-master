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

     		$data=$_POST['data'];

     		$datas=array();
     		$status=array();
     		foreach ($data as $key => $value) {
     			array_push($datas,$key);
     			array_push($status,$value);
     		}

     		$aksi = $qb->insert('user', [
	          'username' => $_POST['username'],
	          'pass' => md5($_POST['password']),
	          'lembaga' => $_POST['lembaga'],
	          'role' => 3

	        ]);
	        $user = $qb->RAW(
	    			"SELECT * FROM user WHERE username='".$_POST['username']."' and pass=?",[md5($_POST['password'])]);
				if($user){
					echo '<div class="col-lg-12 mb-4">
						<div class="card bg-danger text-white shadow">
							<div class="card-body">
								Gagal
								<div class="text-white-50 small">Password salah</div>
							</div>
						</div>
					</div>';
				}else{
						$user = $user[0]; 

					$qb->insert('subuser', [
					'user_id' => $user->id_user,
					'create_id' => $_SESSION['id_user'],
					'data' => implode(",",$datas),
					'status' => implode(",",$status)

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
			
			$data=$_POST['data'];

     		$datas=array();
     		$status=array();
     		foreach ($data as $key => $value) {
     			array_push($datas,$key);
     			array_push($status,$value);
     		}

	        $aksi = $qb->RAW(
    		"UPDATE user SET username='".$_POST['username']."',
    		lembaga='".$_POST['lembaga']."',
    		pass='".md5($_POST['password'])."' where id_user=".$_POST['id_user'],[]); 

    		$qb->RAW(
    		"UPDATE subuser SET data='".implode(",",$datas)."',
    		status='".implode(",",$status)."' where user_id=".$_POST['id_user'],[]);
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
	        echo '<script>setTimeout(function(){location.replace("subusers.php"); }, 1000);</script>'; 
	    }
     	if(isset($_GET['hapus_user'])){
	        $aksi = $qb->RAW(
    		"DELETE from user where id_user=".$_GET['hapus_user'],[]);
    		$qb->RAW(
    		"DELETE from subuser where user_id=".$_GET['hapus_user'],[]);
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
	        echo '<script>setTimeout(function(){location.replace("subusers.php"); }, 1000);</script>'; 
     	}


    $data_user = $qb->RAW(
    "SELECT user.*,subuser.status as status_data FROM user
    left join subuser on subuser.user_id=user.id_user
    where subuser.create_id=?
    ",[$_SESSION['id_user']]);

    ?>


    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Daftar Sub User</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">

    
    <form  role="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
			  <center> <?php if(isset($_GET['edit_user'])){echo '<h1 class="h3 mb-2 text-gray-800">Edit</h1>';}
	    		else{echo'<h1 class="h3 mb-2 text-gray-800">Tambah</h1>';}?></center>
	    
	    <div class="col-lg-12 mb-2">
	    	<div class="input-group">
	    		<input type="text" class="form-control" value="<?php if(isset($_GET['edit_user'])){echo $_GET['lembaga'];} ?>" name="lembaga" placeholder="Nama Sub Users" >
	    	</div>
	    </div>
	    <div class="col-lg-12 mb-2">
	    	<div class="input-group">
	    		<?php
	    			$data=[0,0,0,0,0,0,0,0,0,0,0,0,0]; 
	    			if(isset($_GET['edit_user'])){
	    				 $status_data=explode(",",$_GET['status_data']);
	    				 for ($i=0; $i < count($status_data); $i++) { 
	    					$data[$i]=$status_data[$i];
	    				 }
	    			}
	    		?>
	    		<input type="radio" aria-label="Checkbox for following text input" value="1" name="data[setting]"<?php if($data[0]==1){echo 'checked';}?> ><a style="padding-left:2px;padding-right:3px;">Setting</a>
	    		<input type="radio" aria-label="Checkbox for following text input" value="0" name="data[setting]" <?php if($data[0]==0){echo 'checked';}?> ><a style="padding-right: 10px;">off</a>|&nbsp;

	    		<input type="radio" aria-label="Checkbox for following text input" value="1" name="data[absen]" <?php if($data[1]==1){echo 'checked';}?>><a style="padding-left:2px;padding-right:3px;">Absen</a>
	    		<input type="radio" aria-label="Checkbox for following text input" value="0" name="data[absen]" <?php if($data[1]==0){echo 'checked';}?> ><a style="padding-right: 10px;">off</a>|&nbsp;

	    		<input type="radio" aria-label="Checkbox for following text input" value="1" name="data[perpustakaan]" <?php if($data[2]==1){echo 'checked';}?>><a style="padding-left:2px;padding-right:3px;">Perpustakaan</a>
	    		<input type="radio" aria-label="Checkbox for following text input" value="0" name="data[perpustakaan]"  <?php if($data[2]==0){echo 'checked';}?> ><a style="padding-right: 10px;">off</a>|&nbsp;

	    		<input type="radio" aria-label="Checkbox for following text input" value="1" name="data[perpus_online]" <?php if($data[3]==1){echo 'checked';}?>><a style="padding-left:2px;padding-right:3px;">Perpustakaan Online</a>
	    		<input type="radio" aria-label="Checkbox for following text input" value="0" name="data[perpus_online]"  <?php if($data[3]==0){echo 'checked';}?> ><a style="padding-right: 10px;">off</a>|&nbsp;

	    		<input type="radio" aria-label="Checkbox for following text input" value="1" name="data[data_siswa]" <?php if($data[4]==1){echo 'checked';}?>><a style="padding-left:2px;padding-right:3px;">Data siswa</a>
	    		<input type="radio" aria-label="Checkbox for following text input" value="0" name="data[data_siswa]"  <?php if($data[4]==0){echo 'checked';}?> ><a style="padding-right: 10px;">off</a>|&nbsp;

	    		<input type="radio" aria-label="Checkbox for following text input" value="1" name="data[nilai]" <?php if($data[5]==1){echo 'checked';}?>><a style="padding-left:2px;padding-right:3px;">Nilai</a>
	    		<input type="radio" aria-label="Checkbox for following text input" value="0" name="data[nilai]"  <?php if($data[5]==0){echo 'checked';}?> ><a style="padding-right: 10px;">off</a>|&nbsp;

	    		<input type="radio" aria-label="Checkbox for following text input" value="1" name="data[topup]" <?php if($data[6]==1){echo 'checked';}?>><a style="padding-left:2px;padding-right:3px;">Topup</a>
	    		<input type="radio" aria-label="Checkbox for following text input" value="0" name="data[topup]" <?php if($data[6]==0){echo 'checked';}?> ><a style="padding-right: 10px;">off</a>|&nbsp;

	    		<input type="radio" aria-label="Checkbox for following text input" value="1" name="data[merchan]" <?php if($data[7]==1){echo 'checked';}?>><a style="padding-left:2px;padding-right:3px;">Toko</a>
	    		<input type="radio" aria-label="Checkbox for following text input" value="0" name="data[merchan]"  <?php if($data[7]==0){echo 'checked';}?> ><a style="padding-right: 10px;">off</a>|&nbsp;

	    		<input type="radio" aria-label="Checkbox for following text input" value="1" name="data[spp]" <?php if($data[8]==1){echo 'checked';}?>><a style="padding-left:2px;padding-right:3px;">Sumbangan Komite</a>
	    		<input type="radio" aria-label="Checkbox for following text input" value="0" name="data[spp]" <?php if($data[8]==0){echo 'checked';}?> ><a style="padding-right: 10px;">off</a>|&nbsp;

	    		<input type="radio" aria-label="Checkbox for following text input" value="1" name="data[akses_parkir]" <?php if($data[9]==1){echo 'checked';}?>><a style="padding-left:2px;padding-right:3px;">Akses Parkir</a>
	    		<input type="radio" aria-label="Checkbox for following text input" value="0" name="data[akses_parkir]"  <?php if($data[9]==9){echo 'checked';}?> ><a style="padding-right: 10px;">off</a>|&nbsp;

	    		<input type="radio" aria-label="Checkbox for following text input" value="1" name="data[akses_gerbang]" <?php if($data[10]==1){echo 'checked';}?>><a style="padding-left:2px;padding-right:3px;">Akses Gerbang</a>
	    		<input type="radio" aria-label="Checkbox for following text input" value="0" name="data[akses_gerbang]" <?php if($data[10]==0){echo 'checked';}?> ><a style="padding-right: 10px;">off</a>|&nbsp;

	    		<input type="radio" aria-label="Checkbox for following text input" value="1" name="data[tracking]" <?php if($data[11]==1){echo 'checked';}?>><a style="padding-left:2px;padding-right:3px;">Tracking</a>
	    		<input type="radio" aria-label="Checkbox for following text input" value="0" name="data[tracking]"  <?php if($data[11]==0){echo 'checked';}?> ><a style="padding-right: 10px;">off</a>|&nbsp;

	    		<input type="radio" aria-label="Checkbox for following text input" value="1" name="data[izin]" <?php if($data[12]==1){echo 'checked';}?>><a style="padding-left:2px;padding-right:3px;">Input Izin/Telat</a>
	    		<input type="radio" aria-label="Checkbox for following text input" value="0" name="data[izin]"  <?php if($data[12]==0){echo 'checked';}?> ><a style="padding-right: 10px;">off</a>|&nbsp;
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
                            <th>Nama</th>
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
                            	<a href="subusers.php?edit_user=<?php echo $data_user->id_user;?>&&username=<?php echo $data_user->username;?>&&lembaga=<?php echo $data_user->lembaga;?>&&status_data=<?php echo $data_user->status_data;?>"><i class="fa-solid fa-pen-to-square"></i></a>
                            	&nbsp
                            	<a href="subusers.php?hapus_user=<?php echo $data_user->id_user;?>"><i class="fa-solid fa-trash-can"></i></a>
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