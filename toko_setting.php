<?php 
require "partials/head.php";
require "partials/sidebar.php";
require "asset/phpqrcode/qrlib.php"; ?>
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

     	if(isset($_POST['simpan_menu'])){
     		$filename=$_FILES['gambar']['name'];
     	   if(!empty($filename)){
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
            }
     		$aksi = $qb->insert('toko_menu', [
	          'harga' => $_POST['harga'],
	          'nama' => $_POST['nama'],
	          'gambar' => $filename,
	          'id_user' => $_SESSION['id_user']

	        ]);

			$id_toko=$qb->pdo->lastInsertId();

			$nameqrcode    = $id_toko.'.png';              
            $tempdir        = "asset/qrcode_toko/"; 
            $isiqrcode     = $id_toko;
            $quality        = 'H';
            $Ukuran         = 10;
            $padding        = 0;

            QRCode::png($isiqrcode,$tempdir.$nameqrcode,$quality,$Ukuran,$padding);

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

     	if(isset($_POST['update_menu'])){

     		$filename=$_FILES['gambar']['name'];
     		if(!empty($filename)){
            	unlink('asset/menu/'.$_GET['foto']);
        	}
     	   if(!empty($filename)){
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
            }

     		$aksi = $qb->RAW("UPDATE toko_menu set harga=?,nama=?,gambar=? WHERE id=?",[$_POST['harga'],$_POST['nama'],$filename,$_POST['id_menu']]);

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
            echo '<script>setTimeout(function(){location.replace("toko_setting.php"); }, 1000);</script>';
     	}
     	
     	if(isset($_GET['hapus_menu'])){
	        $aksi = $qb->RAW(
    		"DELETE from toko_menu where id=".$_GET['hapus_menu'],[]);
			if(file_exists('asset/qrcode_toko/'.$_GET['hapus_menu'].'.png')){
				unlink('asset/qrcode_toko/'.$_GET['hapus_menu'].'.png');
			}
	        if($aksi){
	        	if ($_GET['foto_menu'] != '') {
                	unlink('asset/menu/'.$_GET['foto_menu']);
            	}
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
            echo '<script>setTimeout(function(){location.replace("toko_setting.php"); }, 1000);</script>';
     	}

     	 if(isset($_POST['Upload'])){
        require "asset/excel_reader2.php";
        // upload file xls
        $target = strtotime(date("Y-m-d H:i:s")).$_SESSION['id_user'].basename($_FILES['excel']['name']);
        // print_r(strtotime(date("Y-m-d H:i:s")).$target);die();
        move_uploaded_file($_FILES['excel']['tmp_name'], $target);

        // beri permisi agar file xls dapat di baca
        chmod($target,0777);

        // mengambil isi file xls
        $data = new Spreadsheet_Excel_Reader($target,false);
        // menghitung jumlah baris data yang ada
        $jumlah_baris = $data->rowcount($sheet_index=0);

        // jumlah default data yang berhasil di import
        for ($i=2; $i<=$jumlah_baris; $i++){
            if($data->val($i, 2) == ''){break;}

                    $qb->insert('toko_menu', [
                              'nama' => $data->val($i, 1),
                              'harga' => $data->val($i, 2),
                              'id_user' => $_SESSION['id_user']
                            ]);

					$id_toko=$qb->pdo->lastInsertId();
		
					$nameqrcode    = $id_toko.'.png';              
					$tempdir        = "asset/qrcode_toko/"; 
					$isiqrcode     = $id_toko;
					$quality        = 'H';
					$Ukuran         = 10;
					$padding        = 0;
		
					QRCode::png($isiqrcode,$tempdir.$nameqrcode,$quality,$Ukuran,$padding);

        }           
                   echo '
                   <div class="col-lg-12 mb-4">
                        <div class="card bg-success text-white shadow">
                            <div class="card-body">
                                Berhasil
                                <div class="text-white-50 small">Import selesai</div>
                            </div>
                        </div>
                    </div>
                    '; 

        unlink($target);
        }


    $data_menu = $qb->RAW(
    "SELECT * FROM toko_menu where id_user=?",[$_SESSION['id_user']]);

    ?>

    <!-- setting ukuran kartu -->
    <h1 class="h3 mb-2 text-gray-800">Pengaturan Toko</h1>
    <?php 
	    $data_user = $qb->RAW(
			"SELECT * FROM user where id_user=?",[$_SESSION['id_user']]);
	    $data_user=$data_user[0];
    ?>
    <div class="card shadow mb-4">
        <div class="card-body">
        	<form  role="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
        	<table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Saldo Maksimal Kartu/saldo maksimal topup</td>
                            <td><input type="text" class="form-control" value="<?php echo $data_user->saldo_max; ?>" name="data[saldo_max]"></td>
                        </tr>
                    </tbody>
                </table>
                <center><button type="submit" name="simpan_ukuran" class="btn btn-primary"><span  id="">Simpan</span></button></center>
            	</form>
        </div>
    </div>


    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Daftar Barang Toko</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
		<a class="btn btn-primary" target="_Blank" href="download_excel_toko.php">
                                    <span class="glyphicon glyphicon-download"></span>
                                    Download excel</a>
		<a class="btn btn-primary" target="_Blank" href="download_zip_toko.php">
                                    <span class="glyphicon glyphicon-download"></span>
                                    Download Qrcode</a>

    <!-- <h4 class="h3 mb-2 text-gray-800">Import Data Toko</h4><a href="asset/sampel_toko.xls">Template Excel</a> -->
    <form  role="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
		<div class="col-lg-12 mb-2">
			<h4 class="h3 mb-2 text-gray-800">Import Data Toko</h4><a href="asset/sampel_toko.xls">Template Excel</a>
            <div class="input-group">
                <input type="file" name="excel" class="form-control">
            <div class="input-group-prepend">
                <button type="submit" name="Upload" class="input-group-text"><span  id="">Upload</span></button>
            </div>
            </div>
        </div>
    </form>

    
    <form  role="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
	    <div class="col-lg-12 mb-2">
	        	<?php if(isset($_GET['edit_menu'])){ ?>
				<h1 class="h3 mb-2 text-gray-800">Edit</h1>
	        		<?php }else{ ?>
				<h1 class="h3 mb-2 text-gray-800">Tambah</h1>
	        		<?php } ?>
	        <div class="input-group">
	        	<?php if(isset($_GET['edit_menu'])){ ?>
	        	<input type="hidden" name="id_menu" value="<?php echo $_GET['edit_menu']; ?>" class="form-control">
	        		<?php } ?>
			  <input type="file" name="gambar" placeholder="gambar" class="form-control">
			  <input type="text" name="nama" placeholder="Nama" value="<?php if(isset($_GET['edit_menu'])){ echo $_GET['menu'];}?>" class="form-control" required>
			  <input type="number" name="harga" placeholder="Harga" value="<?php if(isset($_GET['edit_menu'])){ echo $_GET['harga'];}?>" class="form-control" required>
			  <div class="input-group-prepend">
	        	<?php if(isset($_GET['edit_menu'])){ ?>
			  	<button type="submit" name="update_menu" class="input-group-text"><span  id="">Update</span></button>
	        		<?php }else{ ?>
			  	<button type="submit" name="simpan_menu" class="input-group-text"><span  id="">Simpan</span></button>
	        		<?php } ?>
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
                            <td><?php if($data_user->gambar != ''){?><img style="display: block; margin-left: auto;  margin-right: auto;   width: 70px;" class="img-responsive img" src="asset/menu/<?php echo $data_user->gambar;?>"><?php } ?></td>
                            <td><?php echo $data_user->nama;?></td>
                            <td><?php echo $data_user->harga;?></td>
                            <td>
                            <center>
                            	<a href="toko_setting.php?edit_menu=<?php echo $data_user->id;?>&&menu=<?php echo $data_user->nama;?>&&harga=<?php echo $data_user->harga;?>&&foto=<?php echo $data_user->gambar;?>"><i class="fa-solid fa-pen-to-square"></i></a>
                            	&nbsp
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