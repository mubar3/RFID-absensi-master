<?php

session_start();

require "vendor/autoload.php";
require "partial/head.php";
use StelinDB\Database\QueryBuilder;
use Carbon\Carbon;

$dotenv = new \Dotenv\Dotenv(__DIR__);
            $dotenv->load();
$now = new Carbon;
$now->setTimezone('Asia/Jakarta');

$qb = new QueryBuilder(\StelinDB\Database\Connection::Connect());

  if(empty($_POST['barang'])){echo "<div class='p-3 mb-2 bg-danger'>Pembelian Kosong<div>"; die();}
    else{
      $jumlah=$_POST['jumlah'];
      $satuan=$_POST['satuan'];
      $harga=$_POST['harga'];
      $barang=$_POST['barang'];
      $stok=array();
        // CARI TOTAL
        $total=0;
        for ($i=0; $i <  count($barang); $i++) { 
          $total=$total+($harga[$i]*$jumlah[$i]);
        }

        // CEK BARANG DAN STOK
        for ($i=0; $i <  count($barang); $i++) { 
          $data = $qb->RAW("SELECT * from toko_menu where id =?",[$barang[$i]]);
          if (!array_key_exists(0, $data)) {echo "<div class='p-3 mb-2 bg-danger'>Barang tidak tersedia<div>";die();}
          $data = $data[0];
          if($data->satuan == $satuan[$i] && $data->satuan != ''){
            array_push($stok,$jumlah[$i]);
          }else{
            $data2 = $qb->RAW("SELECT * from konversi where konversi =?",[$satuan[$i]]);
            if (!array_key_exists(0, $data2)) {echo "<div class='p-3 mb-2 bg-danger'>Satuan tidak valid<div>";die();}
            $data2 = $data2[0];
            $jumlah_p=$jumlah[$i]/$data2->nilai;
            array_push($stok,$jumlah_p);
          }
        
        }
        
        // TAMBAH STOK
        for ($i=0; $i <  count($barang); $i++) { 
          $data = $qb->RAW("SELECT * from toko_menu where id =?",[$barang[$i]]);
          $data = $data[0];
          $stok_akhir=$data->stok + $stok[$i];
          $data = $qb->RAW( "UPDATE toko_menu SET stok=? where id =?",[$stok_akhir,$barang[$i]]);
        }
        
        // input log
                $subuser='';
                if($_SESSION['role'] == 3){$subuser=$_SESSION['sub_user'];}
              $transaksi = $qb->insert('pembelian', [
                  'jumlah' => enkripsiDekripsi(strval($total), $kunciRahasia),
                  'pembayaran' => $_POST['tgl_bayar'],
                  'dibayar' => enkripsiDekripsi(strval($_POST['dibayar']), $kunciRahasia),
                  'b_lainnya' => enkripsiDekripsi(strval($_POST['b_lainnya']), $kunciRahasia),
                  'b_ket' => $_POST['b_ket'],
                  'user' => $_SESSION['id_user'],
                  'subuser' => $subuser
                ]);
              $transaksi=$qb->pdo->lastInsertId();

            for ($i=0; $i <  count($barang); $i++) { 
              $data_harga_menu = $qb->RAW(
              "SELECT * FROM toko_menu where id=?",[$barang[$i]]);
              $data_harga_menu=$data_harga_menu[0];
                $qb->insert('pembelian_detail', [
                    'barang' => $barang[$i],
                    'banyak' => enkripsiDekripsi(strval($harga[$i]), $kunciRahasia),
                    'jumlah' => $stok[$i],
                    'jumlah_satuan' => $jumlah[$i],
                    'id_pembelian' => $transaksi,
                    'satuan' => $satuan[$i]
                  ]);     
            }

            echo "<div class='p-3 mb-2 bg-success'>Berhasil</div>";
          

        }

