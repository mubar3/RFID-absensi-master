<?php 
  // require "partial/nav.php"; 
  require "partials/head.php";
  require "partials/sidebar.php";
  ?>
  <body>

    <div class="container">
      <?php if(!empty($_SESSION['id_user'])){
    // if($_SESSION['role']!=1){
    //   session_destroy();
    //   session_unset();
    //   header("Location: index.php");
    // } 
  }else{
    header("Location: index.php");
  } ?>
      <div class="row">
        <div class="col-md-8">
    <?php

    require "vendor/autoload.php";
    $dotenv = new \Dotenv\Dotenv(__DIR__);
                $dotenv->load();
    use StelinDB\Database\QueryBuilder;
    use Carbon\Carbon;

    $qb = new QueryBuilder(\StelinDB\Database\Connection::Connect());
    $jadwal = $qb->RAW("SELECT siswa.nama,siswa.NIM, rekap_absen.makul_absen, rekap_absen.tanggal_absen
        FROM siswa
        INNER JOIN rekap_absen ON siswa.norf=rekap_absen.norf
        GROUP BY makul_absen", []);
    if (array_key_exists(0, $jadwal)) {
    // foreach ($jadwal[0] as $value[0]) {
        $rekabAbsen = $qb->RAW("SELECT siswa.kelas, siswa.nama,siswa.NIM, rekap_absen.makul_absen, rekap_absen.tanggal_absen
        FROM siswa
        INNER JOIN rekap_absen ON siswa.norf=rekap_absen.norf
        AND rekap_absen.makul_absen = ? 
        WHERE user_input=".$_SESSION['id_user']." and rekap_absen.tanggal_absen >= DATE(NOW())
        group by rekap_absen.norf
        having count(rekap_absen.norf) >= 2
        ", [$jadwal[0]->makul_absen]);

        $daftar_kelas = $qb->RAW("SELECT * FROM kelas where id_user=".$_SESSION['id_user'], []);
        foreach ($daftar_kelas as $daftar_kelas) {
        echo '<h2 class=\"mt-4\">Kelas '.$daftar_kelas->kelas.'</h2>';
        // echo "<h2 class=\"mt-4\">{$value->makul_absen}</h2>";
        $i = 1;
        $table = "<table class=\"table\">
        <thead class=\"table-info\">
          <tr>
            <th>#</th>
            <th>Nama</th>
            <th>NIM</th>
            <th>Jam</th>
            <th>History</th>
          </tr>
        </thead>
        <tbody>";
        foreach ($rekabAbsen as $key => $nilai) {
          if(($nilai->kelas)==($daftar_kelas->id_kelas)){
            $date = Carbon::parse($nilai->tanggal_absen, 'Asia/Jakarta');
            $table .= "<tr>";
            $table .= "<td>$i</td>";
            $table .= "<td>$nilai->nama</td>";
            $table .= "<td>$nilai->NIM</td>";
            $table .= "<td>".$date->toDayDateTimeString()."</td>";
            $table .= "<td>".$date->diffForHumans()."</td>";
            $table .= "</tr>";
            $i++;
          }
        }
        $table .= "
        </tbody>
      </table>";
        echo $table;
    }}else{echo 'kosong';}
  // }
    ?>
  </div>
  <div class="col-md-4">

  </div>
</div>
</div>
    <script src="https://cdn.jsdelivr.net/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <?php require "partials/footer.php"; ?>
