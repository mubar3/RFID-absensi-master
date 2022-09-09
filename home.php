<?php 
require "partials/head.php";
require "partials/sidebar.php"; ?>

        
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <!-- <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                    </div> -->

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-5 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Jumlah Siswa</div>
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
            $total_siswa= $qb->RAW(
            "SELECT * FROM siswa where user_input=".$_SESSION['id_user']."",[]);
            if (array_key_exists(0, $total_siswa)) {$total_siswa=count($total_siswa);}else{$total_siswa=0;}
            
            ?>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo number_format($total_siswa);?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-5 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Presentase Kehadiran Siswa Hari Ini
                                            </div>
                                            <div class="row no-gutters align-items-center">
        <?php
        $total_siswa_masuk= $qb->RAW(
            "SELECT * FROM rekap_absen
            join siswa on siswa.norf=rekap_absen.norf
             where siswa.user_input=".$_SESSION['id_user']." and rekap_absen.tanggal_absen >= DATE(NOW()) GROUP BY rekap_absen.norf",[]);
        if (array_key_exists(0, $total_siswa_masuk)) {$total_siswa_masuk=count($total_siswa_masuk);}else{$total_siswa_masuk=0;}
        if(empty($total_siswa)){$total_siswa=1;}
        $persentase=($total_siswa_masuk/$total_siswa)*100;
        ?>
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                        <?php echo $persentase;?>%</div>
                                                </div>
                                                <div class="col">
                                                    <div class="progress progress-sm mr-2">
                                                        <div class="progress-bar bg-info" role="progressbar"
                                                            style="width: <?php echo $persentase;?>%" aria-valuenow="50" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                    </div>

                    <!-- Content Row -->

                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Presentase Kehadiran Siswa (30 Hari)</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Dropdown Header:</div>
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="myAreaChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                            <div class="card" style="width: 18rem; background;">
                              <div class="card-body">
                                <h5 class="card-title">Input Data Siswa</h5>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                              </div>
                            </div>
                            </div>
                        </div> -->
                        <!-- Pie Chart -->
                       <!--  <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Keaktifan Peminjam Buku (Hari Ini)</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Dropdown Header:</div>
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="chart-pie pt-4 pb-2">
                                        <canvas id="myPieChart"></canvas>
                                    </div>
                                    <div class="mt-4 text-center small">
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-primary"></i> Meminjam
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-success"></i> Tidak Meminjam
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->

               

    
    <?php require "partials/footer.php"; ?>

    <script type="text/javascript">
        // Area Chart Example
var ctx = document.getElementById("myAreaChart");
var myLineChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: [
    <?php 
                    $data_tanggal = $qb->RAW(
                    "SELECT ((count(x.id)/(SELECT COUNT(id) FROM siswa where user_input=".$_SESSION['id_user']."))*100) as banyak,date(x.tanggal_absen) as tanggal FROM (SELECT * from rekap_absen GROUP by norf,date(tanggal_absen))x WHERE x.tanggal_absen>(now()-interval 30 DAY) GROUP by DATE(x.tanggal_absen)",[]);
                    foreach ($data_tanggal as $data_tanggal) {
          ?>
            "<?php echo $data_tanggal->tanggal; ?>",
          <?php } ?>

    ],
    datasets: [{
      label: "Kehadiran",
      lineTension: 0.3,
      backgroundColor: "rgba(78, 115, 223, 0.05)",
      borderColor: "rgba(78, 115, 223, 1)",
      pointRadius: 3,
      pointBackgroundColor: "rgba(78, 115, 223, 1)",
      pointBorderColor: "rgba(78, 115, 223, 1)",
      pointHoverRadius: 3,
      pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
      pointHoverBorderColor: "rgba(78, 115, 223, 1)",
      pointHitRadius: 10,
      pointBorderWidth: 2,
      data: [
      <?php 
                    $data_tanggal = $qb->RAW(
                    "SELECT ((count(x.id)/(SELECT COUNT(id) FROM siswa where user_input=".$_SESSION['id_user']."))*100) as banyak,date(x.tanggal_absen) as tanggal FROM (SELECT * from rekap_absen GROUP by norf,date(tanggal_absen))x WHERE x.tanggal_absen>(now()-interval 30 DAY) GROUP by DATE(x.tanggal_absen)",[]);
                    foreach ($data_tanggal as $data_tanggal) {
          ?>
            <?php echo $data_tanggal->banyak; ?>,
          <?php } ?>
      ],
    }],
  },
  options: {
    maintainAspectRatio: false,
    layout: {
      padding: {
        left: 10,
        right: 25,
        top: 25,
        bottom: 0
      }
    },
    scales: {
      xAxes: [{
        time: {
          unit: 'date'
        },
        gridLines: {
          display: false,
          drawBorder: false
        },
        ticks: {
          maxTicksLimit: 7
        }
      }],
      yAxes: [{
        ticks: {
          maxTicksLimit: 5,
          max: 100,
          min: 0,
          padding: 10,
          // Include a dollar sign in the ticks
          callback: function(value, index, values) {
            return number_format(value);
          }
        },
        gridLines: {
          color: "rgb(234, 236, 244)",
          zeroLineColor: "rgb(234, 236, 244)",
          drawBorder: false,
          borderDash: [2],
          zeroLineBorderDash: [2]
        }
      }],
    },
    legend: {
      display: false
    },
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      titleMarginBottom: 10,
      titleFontColor: '#6e707e',
      titleFontSize: 14,
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      intersect: false,
      mode: 'index',
      caretPadding: 10,
      callbacks: {
        label: function(tooltipItem, chart) {
          var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
          return datasetLabel + ': ' + number_format(tooltipItem.yLabel)+ '%';
        }
      }
    }
  }
});


        // Pie Chart Example
    var ctx = document.getElementById("myPieChart");
    var myPieChart = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ["Meminjam", "Tidak Meminjam"],
        datasets: [{
          data: [
          <?php 
                    $data_pinjam = $qb->RAW(
                    "SELECT count(x.id_peminjaman) as yang_meminjam,((SELECT COUNT(id) FROM siswa)-(count(x.id_peminjaman))) as tidak_pinjam FROM (select * from peminjaman GROUP by peminjam)x WHERE date(x.tanggal)>= DATE(NOW());",[]);
                    foreach ($data_pinjam as $data_pinjam) {
          ?>
            <?php echo $data_pinjam->yang_meminjam.','.$data_pinjam->tidak_pinjam; ?>
          <?php } ?>
          ],
          backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
          hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
          hoverBorderColor: "rgba(234, 236, 244, 1)",
        }],
      },
      options: {
        maintainAspectRatio: false,
        tooltips: {
          backgroundColor: "rgb(255,255,255)",
          bodyFontColor: "#858796",
          borderColor: '#dddfeb',
          borderWidth: 1,
          xPadding: 15,
          yPadding: 15,
          displayColors: false,
          caretPadding: 10,
          callbacks: {
            label: function(tooltipItem, data) { 
                var indice = tooltipItem.index;                 
                return  data.labels[indice] +': '+data.datasets[0].data[indice] + ' Siswa';
            }
          }   
        },
        legend: {
          display: false
        },
        cutoutPercentage: 80,
      },
    });
    </script>