<?php 
require "partials/head.php";
require "partials/sidebar.php"; 
require "asset/phpqrcode/qrlib.php"; ?>

                <!-- Begin Page Content -->
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


    // if(isset($_POST['simpan_data'])){
    //         $rfid_lama=$_POST['rfid_lama'];
    //         $rfid_baru=$_POST['rfid'];
    //         $lanjut=0;
    //         $data_rfid_buku = $qb->RAW(
    //         "SELECT * FROM buku where not rfid=?",[$rfid_lama]);
    //         foreach ($data_rfid_buku as $data_rfid_buku) {
    //             if($rfid_baru==($data_rfid_buku->rfid)){
    //                 echo '<div class="col-lg-12 mb-4">
    //                 <div class="card bg-danger text-white shadow">
    //                     <div class="card-body">
    //                         Gagal
    //                         <div class="text-white-50 small">Data RFID Sudah Ada</div>
    //                     </div>
    //                  </div>
    //                 </div>';
    //             $lanjut=1;
    //             }
    //         }
    //     if($lanjut==0){
    //         $aksi = $qb->RAW(
    //         "UPDATE buku SET 
    //         judul_buku='".$_POST['judul'].
    //         "',tahun_terbit='".$_POST['tahun'].
    //         "',penerbit='".$_POST['penerbit'].
    //         "',rfid='".$rfid_baru.
    //         "',penulis='".$_POST['penulis'].
    //         "',user='".$_SESSION['id_user'].
    //         "' where id_buku=".$_POST['id_buku'],[]);
            
    //         if($aksi){
    //             echo '<div class="col-lg-12 mb-4">
    //                 <div class="card bg-success text-white shadow">
    //                     <div class="card-body">
    //                         Berhasil
    //                         <div class="text-white-50 small">Data Tersimpan</div>
    //                     </div>
    //                 </div>
    //             </div>';
    //         }else{
    //             echo '<div class="col-lg-12 mb-4">
    //             <div class="card bg-danger text-white shadow">
    //                 <div class="card-body">
    //                     Gagal
    //                     <div class="text-white-50 small">Data Gagal Tersimpan</div>
    //                 </div>
    //              </div>
    //             </div>';
    //         }
    //         echo '<script>setTimeout(function(){location.replace("daftar_buku.php"); }, 1000);</script>';
    //       }
    //     }

        if(isset($_POST['Upload'])){
        require "asset/excel_reader2.php";
        // upload file xls
        $target = basename($_FILES['excel']['name']) ;
        move_uploaded_file($_FILES['excel']['tmp_name'], $target);

        // beri permisi agar file xls dapat di baca
        chmod($_FILES['excel']['name'],0777);

        // mengambil isi file xls
        $data = new Spreadsheet_Excel_Reader($_FILES['excel']['name'],false);
        // menghitung jumlah baris data yang ada
        $jumlah_baris = $data->rowcount($sheet_index=0);

        // jumlah default data yang berhasil di import
        for ($i=2; $i<=$jumlah_baris; $i++){
            if($data->val($i, 3) == ''){break;}

                  $judul = $data->val($i, 3);
                  $tahun = $data->val($i, 4);
                  $penerbit = $data->val($i, 5);
                  $penulis = $data->val($i, 6);
                  $jumlah = $data->val($i, 7);
                  $induk = $data->val($i, 8);
                  $kategori = $data->val($i, 2);
                  $t_pengadaan = $data->val($i, 1);

                $user = $qb->RAW(
                    "SELECT * FROM buku WHERE induk>=".$induk." and induk<=".($induk+$jumlah)." and user='".$_SESSION['id_user']."'",[]);
                // print_r("SELECT * FROM buku WHERE induk>=".$induk." and induk<=".($induk+$jumlah)." and user='".$_SESSION['id_user']."'");die();

                  if (array_key_exists(0, $user)) {
                    echo'
                    <div class="col-lg-12 mb-4">
                        <div class="card bg-danger text-white shadow">
                            <div class="card-body">
                                Gagal
                                <div class="text-white-50 small">Buku dengan No Induk '.$induk.' Sudah Ada</div>
                            </div>
                        </div>
                    </div>
                    ';
                  }else{


                $buku = $qb->insert('master_buku', [
                          'user' => $_SESSION['id_user'],
                          'jumlah' => $jumlah,
                          't_pengadaan' => $t_pengadaan
                        ]);
                $buku=$qb->pdo->lastInsertId();
                for ($i=0; $i < $jumlah; $i++) { 
                    $id = $qb->RAW("SELECT count(id_buku) as id_buku FROM buku where user=?",[$_SESSION['id_user']]); 
                    if (array_key_exists(0, $id)) {
                        $id=$id[0];
                        $id=$id->id_buku + 1;
                        $id = sprintf("%04s", $id);
                    }else{
                        $id=1;
                        $id = sprintf("%04s", $id);
                    }
                    $norfid=$kategori.'.'.$id.'.'.$induk;
                    $qb->insert('buku', [
                              'rfid' => $norfid,
                              'judul_buku' => $judul,
                              'tahun_terbit' => $tahun,
                              'penerbit' => $penerbit,
                              'penulis' => $penulis,
                              'user' => $_SESSION['id_user'],
                              'master' => $buku,
                              'induk' => $induk,
                              'kategori' => $kategori
                            ]);

                            $nameqrcode    = $norfid.'.png';              
                            $tempdir        = "asset/qrcode_buku/"; 
                            $isiqrcode     = $norfid;
                            $quality        = 'H';
                            $Ukuran         = 10;
                            $padding        = 0;

                            QRCode::png($isiqrcode,$tempdir.$nameqrcode,$quality,$Ukuran,$padding);

                    $induk++;
                }


              
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

        unlink($_FILES['excel']['name']);
        }}

        if(isset($_POST['simpan_data'])){

        $buku = $qb->RAW(
        "SELECT rfid from buku where master=".$_POST['id_buku'],[]);
        $aksi = $qb->RAW(
        "DELETE from buku where master=".$_POST['id_buku'],[]);
        $aksi = $qb->RAW(
        "DELETE from master_buku where id=".$_POST['id_buku'],[]);
        foreach ($buku as $key) {
            unlink('asset/qrcode_buku/'.$key->rfid.'.png');
        }

          // $norfid = $_POST['rfid'];
          $judul = $_POST['judul'];
          $tahun = $_POST['tahun'];
          $penerbit = $_POST['penerbit'];
          $penulis = $_POST['penulis'];
          $penulis = $_POST['penulis'];
          $jumlah = $_POST['jumlah'];
          $induk = $_POST['induk'];
          $kategori = $_POST['kategori'];
          $t_pengadaan = $_POST['t_pengadaan'];

        // $user = $qb->RAW(
            // "SELECT * FROM buku WHERE rfid='".$norfid."'",[]);
        $user = $qb->RAW(
            "SELECT * FROM buku WHERE induk>=".$induk." and induk<=".($induk+$jumlah)." and user='".$_SESSION['id_user']."'",[]);


        // print_r("SELECT * FROM buku WHERE induk>=".$induk." and induk<=".($induk+$jumlah)." and user='".$_SESSION['id_user']."'");
        // die();
          if (array_key_exists(0, $user)) {
            echo'
            <div class="col-lg-12 mb-4">
                <div class="card bg-danger text-white shadow">
                    <div class="card-body">
                        Gagal
                        <div class="text-white-50 small">No Induk buku Sudah Ada</div>
                    </div>
                </div>
            </div>
            ';
          }else{


        // $rekapAbsen = $qb->insert('buku', [
        //           'rfid' => $norfid,
        //           'judul_buku' => $judul,
        //           'tahun_terbit' => $tahun,
        //           'penerbit' => $penerbit,
        //           'penulis' => $penulis,
        //           'user' => $_SESSION['id_user']
                // ]);
        $buku = $qb->insert('master_buku', [
                  'user' => $_SESSION['id_user'],
                  'jumlah' => $jumlah,
                  't_pengadaan' => $t_pengadaan
                ]);
        $buku=$qb->pdo->lastInsertId();
        for ($i=0; $i < $jumlah; $i++) { 
            $id = $qb->RAW("SELECT count(id_buku) as id_buku FROM buku where user=?",[$_SESSION['id_user']]); 
            if (array_key_exists(0, $id)) {
                $id=$id[0];
                $id=$id->id_buku + 1;
                $id = sprintf("%04s", $id);
            }else{
                $id=1;
                $id = sprintf("%04s", $id);
            }
            $norfid=$kategori.'.'.$id.'.'.$induk;
            $qb->insert('buku', [
                      'rfid' => $norfid,
                      'judul_buku' => $judul,
                      'tahun_terbit' => $tahun,
                      'penerbit' => $penerbit,
                      'penulis' => $penulis,
                      'user' => $_SESSION['id_user'],
                      'master' => $buku,
                      'induk' => $induk,
                      'kategori' => $kategori
                    ]);

            $nameqrcode    = $norfid.'.png';              
            $tempdir        = "asset/qrcode_buku/"; 
            $isiqrcode     = $norfid;
            $quality        = 'H';
            $Ukuran         = 10;
            $padding        = 0;

            QRCode::png($isiqrcode,$tempdir.$nameqrcode,$quality,$Ukuran,$padding);
            $induk++;
        }
        }


        if($buku){
           echo '
           <div class="col-lg-12 mb-4">
                <div class="card bg-success text-white shadow">
                    <div class="card-body">
                        Berhasil
                        <div class="text-white-50 small">Data Tersimpan</div>
                    </div>
                </div>
            </div>
            '; 
        }else{
            echo'
            <div class="col-lg-12 mb-4">
                <div class="card bg-danger text-white shadow">
                    <div class="card-body">
                        Gagal
                        <div class="text-white-50 small">Data Gagal Tersimpan</div>
                    </div>
                </div>
            </div>
            ';
        }
        }

        if(isset($_GET['hapus_buku'])){
            
            $buku = $qb->RAW(
            "SELECT rfid from buku where master=".$_GET['hapus_buku'],[]);
            $aksi = $qb->RAW(
            "DELETE from buku where master=".$_GET['hapus_buku'],[]);
            $aksi = $qb->RAW(
            "DELETE from master_buku where id=".$_GET['hapus_buku'],[]);
            foreach ($buku as $key) {
                unlink('asset/qrcode_buku/'.$key->rfid.'.png');
            }
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
            echo '<script>setTimeout(function(){location.replace("daftar_buku.php"); }, 1000);</script>'; 
        }

    $table='kelas';
    $data_buku = $qb->RAW(
    "SELECT * FROM buku where user=? group by master",[$_SESSION['id_user']]);
    // print_r($data_kelas);
    // die();

    ?>
    <h4 class="h5 mb-2 text-gray-800">Import Data Buku</h4><a href="asset/sampel_perpus.xls">Template Excel</a>
    <form  role="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
        <div class="col-lg-12 mb-2">
            <div class="input-group">
                <input type="file" name="excel" class="form-control">
            <div class="input-group-prepend">
                <button type="submit" name="Upload" class="input-group-text"><span  id="">Upload</span></button>
            </div>
            </div>
        </div>
    </form>

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Data Buku</h1>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Judul Buku</th>
                                            <th>Tahun Terbit</th>
                                            <th>Penulis</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        foreach ($data_buku as $buku) {
                                            ?>
                                        <tr>
                                            <td><?php echo $buku->judul_buku;?></td>
                                            <td><?php echo $buku->tahun_terbit;?></td>
                                            <td><?php echo $buku->penulis;?></td>
                                            <td>
                                            <center>
                                                <a target="_Blank" href="download_ZIP_buku.php?id=<?php echo $buku->master; ?>"><i class="fa-regular fa-file-zipper"></i></a>
                                                &nbsp
                                                <a target="_Blank" href="download_excel_buku.php?id=<?php echo $buku->master; ?>"><i class="fa-sharp fa-solid fa-file-excel"></i></a>
                                                &nbsp
                                                <a href="daftar_buku.php?edit_buku=<?php echo $buku->master;?>"><i class="fa-solid fa-pen-to-square"></i></a>
                                                &nbsp
                                                <a href="daftar_buku.php?hapus_buku=<?php echo $buku->master;?>"><i class="fa-solid fa-trash-can"></i></a>
                                            </center>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        
                                    </tbody>
                                </table>
                            </div>
    <?php if(isset($_GET['edit_buku'])){
        $data_edit_buku = $qb->RAW(
        "SELECT * FROM buku
        join master_buku on master_buku.id=buku.master 
        where buku.master=?",[$_GET['edit_buku']]);
        // print_r($data_edit_buku);die();
        ?>
    <br><br><br>
    <h1 class="h3 mb-2 text-gray-800">Edit Data</h1>
    <?php foreach ($data_edit_buku as $data_edit_buku) {?>
        <form  role="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
    <input name="id_buku" value="<?php echo $data_edit_buku->master;?>" type="hidden" >
    <!-- <input name="rfid_lama" value="<?php echo $data_edit_buku->rfid;?>" type="hidden" > -->
    <div class="mb-3"><label for="exampleFormControlInput1">Tanggal Pengadaan</label><input class="form-control" name="t_pengadaan" type="date" placeholder="Tanggal" value="<?php echo $data_edit_buku->t_pengadaan;?>" required></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Kategori</label>
    <select class="form-control" name="kategori" required>
        <option value="">Pilih</option>
        <?php $k_buku = $qb->RAW("SELECT * FROM k_buku",[]); 
        foreach ($k_buku as $kategori) {
            if($kategori->id == $data_edit_buku->kategori){
                echo '<option value="'.$kategori->id.'" selected>'.$kategori->nama.'</option>';
            }else{
                echo '<option value="'.$kategori->id.'">'.$kategori->nama.'</option>';
            }
        }
        ?>
    </select>
    </div>
    <div class="mb-3"><label for="exampleFormControlInput1">Judul Buku</label><input class="form-control" name="judul" value="<?php echo $data_edit_buku->judul_buku;?>" type="text" placeholder="Judul" required></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Tahun Terbit</label><input class="form-control" name="tahun" value="<?php echo $data_edit_buku->tahun_terbit;?>"type="text" placeholder="Tahun" required></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Penerbit</label><input class="form-control" name="penerbit" value="<?php echo $data_edit_buku->penerbit;?>" type="text" placeholder="Penerbit" required></div>
    <!-- <div class="mb-3"><label for="exampleFormControlInput1">RFID</label><input class="form-control" name="rfid" value="<?php echo $data_edit_buku->rfid;?>" type="text" placeholder="RFID" required></div> -->
    <div class="mb-3"><label for="exampleFormControlInput1">Penulis</label><input class="form-control" name="penulis" value="<?php echo $data_edit_buku->penulis;?>" type="text" placeholder="Penulis" required></div>
    <div class="mb-3"><label for="exampleFormControlInput1">Jumlah Exemplar</label><input class="form-control" name="jumlah" type="number" placeholder="jumlah" value="<?php echo $data_edit_buku->jumlah;?>" required></div>
    <div class="mb-3"><label for="exampleFormControlInput1">No Induk</label><input class="form-control" name="induk" type="number" placeholder="jumlah" value="<?php echo $data_edit_buku->induk;?>" required></div>
    <button name="simpan_data" type='submit' class="btn btn-primary btn-user btn-block">Simpan</button>
    </form>
    <?php break;}} ?>

                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->


            
     <?php require "partials/footer.php"; ?>