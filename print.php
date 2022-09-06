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
        color: black;
        font-size:20px ;
        font-weight: bold;
        font-family: Arial;font-size: 12px;
    }
    table{
        color: black;
        font-size:20px ;
        font-weight: bold;
        font-family: Arial;font-size: 12px;
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
require "vendor/autoload.php";

        use StelinDB\Database\QueryBuilder;
        use Carbon\Carbon;

        $dotenv = new \Dotenv\Dotenv(__DIR__);
                    $dotenv->load();
        $now = new Carbon;
        $now->setTimezone('Asia/Jakarta');

        $qb = new QueryBuilder(\StelinDB\Database\Connection::Connect());
    
    $id=$_POST['selector'];
    $qr=array();
    $date=array();
    // $rr[qrcode]
    // print_r($id);die();

    $N = count($id);
    for($i=0; $i < $N; $i++) {
        // $sql = "SELECT * FROM siswa WHERE id='$id[$i]'";
        // if(isset($id[$i])){
            $rr = $qb->RAW(
              "SELECT * FROM siswa WHERE id=".$id[$i],[]);
            $rr=$rr[0];
            // $rr=json_decode(json_encode($rr), true);
        // print_r($rr->nisn);
        // die();
              // echo $sql."<br>";
            // $rr=mysqli_fetch_array(mysqli_query($koneksi, $sql));
            $qr[$i]=$rr->nisn.'.png';
            $date[$i]=$rr->id;
        // }
    }
    for($i=0; $i < $N; $i++) {
        mysqli_query($koneksi, "UPDATE siswa SET cetak=1 WHERE id= '$id[$i]'");
        $qb->RAW("UPDATE siswa SET cetak=1 WHERE id=".$id[$i],[]);
		// $sql = "SELECT * FROM siswa WHERE siswa.id='$id[$i]'";
        $rr = $qb->RAW(
          "SELECT * FROM siswa 
          left join kelas on kelas.id_kelas=siswa.kelas
          left join tb_agama on tb_agama.id=siswa.agama
          left join tb_jk on tb_jk.id=siswa.jk
          left join provinsi on provinsi.id_prov=siswa.provinsi
          left join kabupaten on kabupaten.id_kab=siswa.kabupaten
          left join kecamatan on kecamatan.id_kec=siswa.kecamatan
          left join desa on desa.id_desa=siswa.desa
          WHERE siswa.id=".$id[$i],[]);
        $rr=$rr[0];

        $urutan = $qb->RAW(
          "SELECT * FROM data_kartu
          WHERE user=".$rr->user_input." order by urutan asc",[]);

        $data_user = $qb->RAW(
        "SELECT * FROM user where id_user=?",[$rr->user_input]);
        $depan='depan.jpg';
        $belakang='belakang.jpg';
        if (array_key_exists(0, $data_user)) {
            $data_user=$data_user[0];
            if(!empty($data_user->kar_depan)){
                $depan=$data_user->kar_depan;
            }
            if(!empty($data_user->kar_belakang)){
                $belakang=$data_user->kar_belakang;
            }
        }
        // $rr=json_decode($rr[0]);
		 // $rr=mysqli_fetch_array(mysqli_query($koneksi, $sql));


    // $foto_qr='';
    // $date_input='';
    // //baris 1
    //     if ($i==0){$foto_qr=$qr[1]; $date_input=$date[1];}
    //     elseif ($i==1){$foto_qr=$qr[0]; $date_input=$date[0];}

    // //baris 2
    //     if ($i==2){$foto_qr=$qr[3];  $date_input=$date[3];}
    //     elseif ($i==3){$foto_qr=$qr[2]; $date_input=$date[2];}

    // //baris 3
    //     if ($i==4){$foto_qr=$qr[5]; $date_input=$date[5];}
    //     elseif ($i==5){$foto_qr=$qr[4]; $date_input=$date[4];}

    // //baris 4
    //     if ($i==6){$foto_qr=$qr[7]; $date_input=$date[7];}
    //     elseif ($i==7){$foto_qr=$qr[6]; $date_input=$date[6];}

    // //baris 5
    //     if ($i==8){$foto_qr=$qr[9]; $date_input=$date[9];}
    //     elseif ($i==9){$foto_qr=$qr[8]; $date_input=$date[8];}
?>

<div style=" float: left;  padding-left: 30px; width: 550px;height: 350px; border-left: 2px dashed red;">
<div style=" margin-left: 0px;  float: left;  margin-right: 30px; margin-top:-4px;width: 550px;height: 350px;margin-bottom: 6px;background-size: 550px 350px;
    <?php if(!empty($rr->foto)){?>
    /*background-image: url('kartu/depan.jpg');*/
    /*background-image: url('asset/desain/depan.jpg');*/
    background-image: url('asset/desain/<?php echo $depan;?>');
    <?php } ?>
    ">

  <?php if(!empty($rr->foto)){?>
  <img style="position: absolute;margin-left: 37px;margin-top: 120px; width: 105px; height: 140px;overflow: hidden;" class="img-responsive img" src="asset/foto/<?php echo $rr->foto;?>">
  <?php } ?>
                <!-- <div style="display: block; position: absolute;margin-left: 288px;margin-top: 190px; line-height: 15px; width: 220px;height:35px;text-align:left;position: left;float: left">
                       <?php echo $rr->alamat.", ".$rr->desa.", ".$rr->kecamatan.", ".$rr->kabupaten.", ".$rr->provinsi.", Indonesia";?>
                   </div> -->
           
                <p style="position: absolute;margin-left: 190px;margin-top: 110px;width: 50px;height:10px;text-align:center;position: center;float: center">
                    
                    

                    <p style="position: absolute;margin-left: 160px;margin-top: 310px; line-height: 15px; width: 400px;height:35px;text-align:left;position: center;float: center">
                       <!-- <b style="border-top: 1px solid white;">Masa Berlaku : S</b><b>eumur Hidup</b> -->
                   </p>
                  
					   
                <table cellpadding="" cellspacing="" style="margin-top: -16px;padding-top: 135px;padding-left: 155px; position: relative;transition-property: 600px;width: 510px;height: 170px;">
               <?php 
                foreach ($urutan as $urut) {
               ?>   
                    <?php if($urut->code == 'nama' && $urut->status == 1){ ?>
                    <tr>
                        <td width="30%">Nama</td>
                        <td>:</td>
                          <td style="text-transform: uppercase;"><?php echo $rr->nama;?></td>
                    </tr>
                    <?php } else if($urut->code == 'ttl' && $urut->status == 1){?>
                    <tr>
                        <td>TTL</td>
                        <td>:</td>
                        <td><?php echo strtoupper($rr->tmp_lhr);?>, <?php echo tgl_indo($rr->tgl_lhr);
                        // format_date($rr["tgl_lhr"]);
                        ?></td>
                    </tr>
                    <?php } else if($urut->code == 'nis' && $urut->status == 1){?>
                    <tr>
                        <td>NIS</td>
                        <td>:</td>
                        <td><?php echo $rr->nim;?></td>
                    </tr>
                    <?php } else if($urut->code == 'nisn' && $urut->status == 1){?>
                    <tr>
                        <td>NISN</td>
                        <td>:</td>
                        <td><?php echo $rr->nisn;?></td>
                    </tr>
                    <?php } else if($urut->code == 'jk' && $urut->status == 1){?>
                    <tr>
                        <td>Jenis Kelamin</td>
                        <td>:</td>
                        <td><?php echo $rr->jk;?></td>
                    </tr>
                    <?php } else if($urut->code == 'agama' && $urut->status == 1){?>
                    <tr>
                        <td>Agama</td>
                        <td>:</td>
                        <td><?php echo $rr->agama;?></td>
                    </tr>
                    <?php } else if($urut->code == 'alamat' && $urut->status == 1){?>
                    <tr>
                        <td style="vertical-align:top">Alamat</td>
                        <td style="vertical-align:top">:</td>
                        <td style="vertical-align:top"> <?php echo $rr->alamat.", ".$rr->nama_desa.", ".$rr->nama_kecamatan.", ".$rr->nama_kabupaten.", ".$rr->nama_provinsi;?></td>
                        
                    </tr>
                    <?php } else if($urut->code == 'kelas' && $urut->status == 1){?>
                    <tr>
                        <td>Kelas</td>
                        <td>:</td>
                        <td><?php echo $rr->kelas;?></td>
                    </tr>
                    <?php } else if($urut->code == 'rfid' && $urut->status == 1){?>
                    <tr>
                        <td>No RFID</td>
                        <td>:</td>
                        <td><?php echo $rr->norf;?></td>
                    </tr>
                <?php
                        }
                    }
                ?>
                        <img style="border: 0px solid white; border-radius: 5px; position: absolute;margin-left: 445px;margin-top: 260px; width: 70px; height: 70px;overflow: hidden;" class="img-responsive img" src="asset/qrcode/<?php 
                        echo $rr->nisn.'.png';
                        ?>">
                    
                </table>

                </p>

            </div>
        </div>



<div style="float: left; 
    margin-right: 30px;  margin-left: 30px;
     margin-top:-4px;width: 550px;height: 350px;margin-bottom: 6px;background-size: 550px 350px;
     /*background-image: url('kartu/belakang.jpg');*/
     /*background-image: url('asset/desain/belakang.jpg');*/
     background-image: url('asset/desain/<?php echo $belakang;?>');
     ">
    <!-- <a><?php echo $i;?></a> -->

    <div style="display: block; position: absolute;margin-left: 220px;margin-top: 190px; line-height: 15px; width: 220px;height:35px;text-align:left; font-size: 10px; font-weight: normal!important; position: left;float: left">
                       <!-- Dikeluarkan  -->
                       <?php 
                       // echo tgl_indo($date_input);
                        // format_date($rr["tgl_input"]);
                        ?>
                   </div>

                

           

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