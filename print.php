<?php
session_start();
error_reporting(1);
include '../assets/config/koneksi.php';

if(empty($_SESSION))
{
    header("Location: ../login");
}

	function xpld($charr) {
		$hasil = explode("-",$charr);
		return $hasil;
	}

	function format_date($_date_) {
		if ($_date_) {
			$xpld = xpld($_date_);
			return $xpld[2]."-".$xpld[1]."-".$xpld[0];
		} else {
			return "";
		}
	}

?>

<script>
	// window.open('../../../../cetak.php');
</script>
<style>
    body{
        color: white;
        font-size:20px ;
        font-weight: bold;
        font-family: Arial;font-size: 13px;
    }
    table{
        color: white;
        font-size:20px ;
        font-weight: bold;
        font-family: Arial;font-size: 13px;
    }
/*body {
    -webkit-transform: scaleX(-1);
     transform: scaleX(-1);
}*/
</style>
<body style="
 padding-left: 100px;  padding-top: 30px;padding-bottom: -40px;">
<!-- A4 -->
<!-- <div style="  position: absolute; margin-left: 672px;
            height: 90%;  border-left: 2px dashed;" ></div> -->
<?php //echo count($anggota);?>
<?php //echo ($_POST["kartu"][24]);?>
<?php
    
    $id=$_POST['selector'];
    $qr=array();
    $date=array();
    // $rr[qrcode]

    for($i=0; $i < 10; $i++) {
        $sql = "SELECT * FROM siswa 
          WHERE id='$id[$i]'";
          // echo $sql."<br>";
        $rr=mysqli_fetch_array(mysqli_query($koneksi, $sql));
        $qr[$i]=$rr['qrcode'];
        $date[$i]=$rr['created'];
        // array_push($q,$rr['qrcode']);
    }
    // print_r($qr);
    // die();

        // $N = count($id);
    $N = 10;
    for($i=0; $i < $N; $i++) {
        // $tgl_skr = date("Y-m-d G:i:s");
        mysqli_query($koneksi, "UPDATE siswa SET cetak=1 WHERE id= '$id[$i]'");
        // print_r($id[$i]);
        // end();
		$sql = "SELECT * FROM siswa 
		  -- LEFT JOIN desa ON user.id_kel= desa.id_desa
    --       LEFT JOIN tb_jk ON user.jk= tb_jk.id
		  -- LEFT JOIN kabupaten ON user.id_kab= kabupaten.id_kab 
		  -- LEFT JOIN kecamatan ON user.id_kec= kecamatan.id_kec 
		  -- LEFT JOIN provinsi ON user.id_prov= provinsi.id_prov
		  -- LEFT JOIN tb_pekerjaan ON user.pekerjaan= tb_pekerjaan.id
		  -- LEFT JOIN banom ON user.banom= banom.idbanom
		  WHERE siswa.id='$id[$i]'";
		  // echo $sql."<br>";
		$rr=mysqli_fetch_array(mysqli_query($koneksi, $sql));

		
?>
<div style=" float: left;  padding-left: 30px; width: 550px;height: 350px; border-left: 2px dashed red;">
<div style=" margin-left: 0px;  float: left;  margin-right: 30px; margin-top:-4px;width: 550px;height: 350px;margin-bottom: 6px;background-size: 550px 350px;
    <?php if(!empty($rr[gambar])){?>
    /*background-image: url('kartu/depan.jpg');*/
    <?php } ?>
    background-image: url('asset/foto/DSCF2001.JPG');
    ">

  <?php if(!empty($rr[gambar])){?>
  <img style="position: absolute;margin-left: 37px;margin-top: 130px; width: 105px; height: 140px;overflow: hidden;" class="img-responsive img" src="../assets/img/user/<?php echo "$rr[gambar]";?>">
  <?php } ?>
                <!-- <div style="display: block; position: absolute;margin-left: 288px;margin-top: 190px; line-height: 15px; width: 220px;height:35px;text-align:left;position: left;float: left">
                       <?php echo $rr[alamat].", RT.".$rr[rt]."/RW.".$rr[rw].", ".$rr[nama_desa].", ".$rr[nama_kecamatan].", ".$rr[nama_kabupaten].", ".$rr[nama_provinsi].", Indonesia (".$rr[kodepos].")";?>
                   </div> -->
           
                <p style="position: absolute;margin-left: 190px;margin-top: 110px;width: 50px;height:10px;text-align:center;position: center;float: center">
                    
                    

                    <p style="position: absolute;margin-left: 160px;margin-top: 310px; line-height: 15px; width: 400px;height:35px;text-align:left;position: center;float: center">
                       <b style="border-top: 1px solid white;">Masa Berlaku : S</b><b>eumur Hidup</b>
                   </p>
                  
					   
                <table cellpadding="" cellspacing="" style="margin-top: -16px;padding-top: 145px;padding-left: 155px; position: relative;transition-property: 600px;width: 510px;height: 170px;">
                  
                    <tr>
                        <td width="30%">Nama</td>
                        <td>:</td>
                          <td style="text-transform: uppercase;"><?php echo $rr["nama"];?></td>
                    </tr>
                    <tr>
                        <td>NIA</td>
                        <td>:</td>
                        <td><?php echo $rr["nokartanu"];?></td>
                    </tr>
                    <tr>
                        <td>TTL</td>
                        <td>:</td>
                        <td><?php echo strtoupper($rr["tmp_lhr"]);?>, <?php echo tgl_indo($rr["tgl_lhr"]);
                        // format_date($rr["tgl_lhr"]);
                        ?></td>
                    </tr>
                    <tr>
                        <td>Jenis Kelamin</td>
                        <td>:</td>
                        <td><?php echo "$rr[jk]";?></td>
                    </tr>
                    <tr>
                        <td style="vertical-align:top">Alamat</td>
                        <td style="vertical-align:top">:</td>
                        <td style="vertical-align:top"><?php echo $rr[alamat].", RT.".$rr[rt]."/RW.".$rr[rw].", ".$rr[nama_desa].", ".$rr[nama_kecamatan].", ".$rr[nama_kabupaten].", ".$rr[nama_provinsi].", Indonesia (".$rr[kodepos].")";?></td>
                        
                    </tr>
                    
                </table>

                </p>

            </div>
        </div>

<?php
    $foto_qr='';
    $date_input='';
    //baris 1
        if ($i==0){$foto_qr=$qr[1]; $date_input=$date[1];}
        elseif ($i==1){$foto_qr=$qr[0]; $date_input=$date[0];}

    //baris 2
        if ($i==2){$foto_qr=$qr[3];  $date_input=$date[3];}
        elseif ($i==3){$foto_qr=$qr[2]; $date_input=$date[2];}

    //baris 3
        if ($i==4){$foto_qr=$qr[5]; $date_input=$date[5];}
        elseif ($i==5){$foto_qr=$qr[4]; $date_input=$date[4];}

    //baris 4
        if ($i==6){$foto_qr=$qr[7]; $date_input=$date[7];}
        elseif ($i==7){$foto_qr=$qr[6]; $date_input=$date[6];}

    //baris 5
        if ($i==8){$foto_qr=$qr[9]; $date_input=$date[9];}
        elseif ($i==9){$foto_qr=$qr[8]; $date_input=$date[8];}
?>

<div style="float: left; 
    margin-right: 30px;  margin-left: 30px;
     margin-top:-4px;width: 550px;height: 350px;margin-bottom: 6px;background-size: 550px 350px;
     <?php if(!empty($foto_qr)){?>
     /*background-image: url('kartu/belakang.jpg');*/
     <?php } ?>
     background-image: url('asset/foto/DSCF2001.JPG');
     ">
    <!-- <a><?php echo $i;?></a> -->

    <div style="display: block; position: absolute;margin-left: 220px;margin-top: 190px; line-height: 15px; width: 220px;height:35px;text-align:left; font-size: 10px; font-weight: normal!important; position: left;float: left">
                       Dikeluarkan <?php echo tgl_indo($date_input);
                        // format_date($rr["tgl_input"]);
                        ?>
                   </div>

                <?php if(!empty($foto_qr)){?>
                <img style="border: 5px solid white; border-radius: 5px; position: absolute;margin-left: 32px;margin-top: 216px; width: 75px; height: 75px;overflow: hidden;" class="img-responsive img" src="../assets/img/qrcode/<?php 
                echo $foto_qr;
                ?>">
                <?php } ?>

           

            </div>



<?php } ?>
</body>

<script>
        window.print();
    </script>

    <?php
function tgl_indo($tanggal){
$bulan = array (
  1 =>   'Januari',
  'Februari',
  'Maret',
  'April',
  'Mei',
  'Juni',
  'Juli',
  'Agustus',
  'September',
  'Oktober',
  'November',
  'Desember'
);
$pecahkan = explode('-', $tanggal);
$tgl=explode(' ', $pecahkan[2]);

// variabel pecahkan 0 = tanggal
// variabel pecahkan 1 = bulan
// variabel pecahkan 2 = tahun

return $tgl[0] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}
?>