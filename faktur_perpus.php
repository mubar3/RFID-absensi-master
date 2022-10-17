<html>
<head>
<title>Faktur Pembayaran</title>
<?php 
    require "vendor/autoload.php";

    use StelinDB\Database\QueryBuilder;
    use Carbon\Carbon;

    $dotenv = new \Dotenv\Dotenv(__DIR__);
                $dotenv->load();
    $now = new Carbon;
    $now->setTimezone('Asia/Jakarta');

    $qb = new QueryBuilder(\StelinDB\Database\Connection::Connect());
    $aksi = $qb->RAW("SELECT 
    	u1.lembaga as user,
    	user.lembaga as user2,
        provinsi.nama_provinsi,  
        kabupaten.nama_kabupaten,  
        kecamatan.nama_kecamatan,
        siswa.nama,
        -- buku.judul_buku,
        -- buku.induk,
        peminjaman.tanggal,
        peminjaman.buku
    	FROM peminjaman  
    	-- left join buku on buku.rfid=peminjaman.buku
    	left join siswa on siswa.norf=peminjaman.peminjam
    	left join user on user.id_user=peminjaman.subuser
    	left join user u1 on u1.id_user=peminjaman.user
	    left join provinsi on provinsi.id_prov=u1.provinsi 
	    left join kabupaten on kabupaten.id_kab=u1.kabupaten 
	    left join kecamatan on kecamatan.id_kec=u1.kecamatan 
    	where peminjaman.id_peminjaman=? ",[$_GET['id']]);
    $aksi1=$aksi[0];
    // print_r($aksi);die();
 ?>
<style>
#tabel
{
font-size:15px;
border-collapse:collapse;
}
#tabel  td
{
padding-left:5px;
border: 1px solid black;
}
</style>
</head>
<body style='font-family:tahoma; font-size:8pt;' onload="javascript:window.print()">
<!-- <body style='font-family:tahoma; font-size:8pt;'> -->
<center>
<table style='width:550px; font-size:8pt; font-family:calibri; border-collapse: collapse;' border = '0'>
<td width='70%' align='left' style='padding-right:80px; vertical-align:top'>
<span style='font-size:15pt'><b>FAKTUR PEMINJAMAN</b></span></br>
<span style='font-size:12pt'><?php echo $aksi1->user2; ?></span></br>
<?php echo $aksi1->nama_provinsi.', '.$aksi1->nama_kabupaten.', '.$aksi1->nama_kecamatan; ?>
</td>
<td style='vertical-align:top' width='30%' align='left'>
<b><span style='font-size:12pt'><?php echo $aksi1->user; ?></span></b></br>
Waktu : <?php echo $aksi1->tanggal; ?></br>
</td>
</table>
<table style='width:550px; font-size:8pt; font-family:calibri; border-collapse: collapse;' border = '0'>
<td width='70%' align='left' style='padding-right:80px; vertical-align:top'>
Nama Peminjam : <?php echo $aksi1->nama; ?></br>
</td>
</table>
<table cellspacing='0' style='width:550px; font-size:8pt; font-family:calibri;  border-collapse: collapse;' border='1'>
 
<tr align='center'>
	<td width='10%'><b>Kode Barang</b></td>
	<td width='20%'><b>Nama Barang</b></td>
</tr>
<?php 
$data=explode(',',$aksi1->buku);
$total_buku=count($data);
// foreach ($aksi as $data) { 
for($x=0;$x<$total_buku;$x++){
	 $nama_buku = $qb->RAW("SELECT * FROM buku where rfid=?",[$data[$x]]);
	?>
<tr>
	<td align="center"><?php echo $nama_buku[0]->induk; ?></td>
	<td align="center"><?php echo $nama_buku[0]->judul_buku; ?></td>
</tr>
<?php } ?>
 
</table>
 
<table style='width:650; font-size:7pt;' cellspacing='2'>

</table>
</center>
</body>
</html>