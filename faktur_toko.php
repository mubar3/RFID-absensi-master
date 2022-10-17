<html>
<head>
<title>Faktur Pembayaran</title>
<?php 
    require "vendor/autoload.php";
    require "partial/head.php";

    use StelinDB\Database\QueryBuilder;
    use Carbon\Carbon;

    $dotenv = new \Dotenv\Dotenv(__DIR__);
                $dotenv->load();
    $now = new Carbon;
    $now->setTimezone('Asia/Jakarta');

    $qb = new QueryBuilder(\StelinDB\Database\Connection::Connect());
    $aksi = $qb->RAW("SELECT 
        (select count(id_log) from saldo_log where ket=s1.ket) as jumlah,
        u1.lembaga as user,
        user.lembaga as user2,
        provinsi.nama_provinsi,  
        kabupaten.nama_kabupaten,  
        kecamatan.nama_kecamatan,
        siswa.nama,
        s1.waktu,
        s1.ket,
        s1.banyak
        from saldo_log s1
        left join siswa on siswa.norf=s1.id_rfid
        left join user on user.id_user=s1.subuser
        left join user u1 on u1.id_user=s1.user
        left join provinsi on provinsi.id_prov=u1.provinsi 
        left join kabupaten on kabupaten.id_kab=u1.kabupaten 
        left join kecamatan on kecamatan.id_kec=u1.kecamatan
        where s1.id_transaksi=? 
        group by s1.ket
     ",[$_GET['id']]);
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
<span style='font-size:15pt'><b>FAKTUR TOKO</b></span></br>
<span style='font-size:12pt'><?php echo $aksi1->user2; ?></span></br>
<?php echo $aksi1->nama_provinsi.', '.$aksi1->nama_kabupaten.', '.$aksi1->nama_kecamatan; ?>
</td>
<td style='vertical-align:top' width='30%' align='left'>
<b><span style='font-size:12pt'><?php echo $aksi1->user; ?></span></b></br>
Waktu : <?php echo $aksi1->waktu; ?></br>
</td>
</table>
<table style='width:550px; font-size:8pt; font-family:calibri; border-collapse: collapse;' border = '0'>
<td width='70%' align='left' style='padding-right:80px; vertical-align:top'>
Nama Pembeli : <?php echo $aksi1->nama; ?></br>
</td>
</table>
<table cellspacing='0' style='width:550px; font-size:8pt; font-family:calibri;  border-collapse: collapse;' border='1'>
 
<tr align='center'>
	<td width='10%'><b>Barang</b></td>
	<td width='20%'><b>Harga</b></td>
    <td width='20%'><b>Jumlah</b></td>
    <td width='20%'><b>Total</b></td>
</tr>
<?php 
$total=0;
foreach ($aksi as $data) { 
    $harga=enkripsiDekripsi($data->banyak, $kunciRahasia);
    $subtotal=$harga*$data->jumlah;
    $total=$total+$subtotal;
	?>
<tr>
	<td align="center"><?php echo $data->ket; ?></td>
	<td align="center"><?php echo convertToRupiah($harga); ?></td>
    <td align="center"><?php echo $data->jumlah; ?></td>
    <td align="center"><?php echo convertToRupiah($subtotal); ?></td>
</tr>
<?php } ?>
    <td align="center"></td>
    <td align="center"></td>
    <td align="center"><b>Total</b></td>
    <td align="center"><?php echo convertToRupiah($total); ?></td>
 
</table>
 
<table style='width:650; font-size:7pt;' cellspacing='2'>

</table>
</center>
</body>
</html>