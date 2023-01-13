<?php
$kunciRahasia = "dfs891237jhbsj2AAsds2";
function enkripsiDekripsi($teksAsli, $kataKunci = ''){
    // jika kata kunci kosong, maka teks tidak akan diproses
    // baik enkrip atau dekrip
    if ($kataKunci == '') {
        return $teksAsli;
    }
    // membuang karakter spasi pada kata kunci
    // jika karakter kurang dari 5, maka proses tidak dilanjutkan
    // kemudian muncul error, ingat batasan karakter terserah Anda, bisa juga gag pakai
    if (strlen(trim($kataKunci)) < 5) {
        exit('Kata Kunci Salah');
    }
    $kataKunci_len = strlen($kataKunci);
    $kataKunci_len = ($kataKunci_len > 32) ? 32 : $kataKunci_len;
    $k = array();
    for ($i = 0; $i < $kataKunci_len; ++$i) {
        $k[$i] = ord($kataKunci{$i}) & 0x1F;
    }
    for ($i = 0, $j = 0; $i < strlen($teksAsli); ++$i) {
        $e = ord($teksAsli{$i});
        if ($e & 0xE0) {
            $teksAsli{$i} = chr($e ^ $k[$j]);
        }
        $j = ($j + 1) % $kataKunci_len;
    }
    return $teksAsli;
}

function convertToRupiah($angka){
    
    $hasil_rupiah = "Rp " . number_format($angka,2,',','.');
    return $hasil_rupiah;
 
}
?>
