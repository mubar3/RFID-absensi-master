<?php require "head.php"; ?>
<nav class="navbar navbar-expand-lg navbar-light bg-warning">
  <a class="navbar-brand" href="#">  <img src="asset/img/logo1.png" width="70" height="70" class="d-inline-block align-top" alt="">
</a>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
  <span class="navbar-toggler-icon"></span>
</button>
  <?php 
  session_start();
  if(!empty($_SESSION['id_user'])){?>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active" class="text-success">
        <a class="nav-link" href="logout.php"><i class="fa fa-sign-out fa-3x" data-toggle="tooltip" data-placement="bot" title="Debet" aria-hidden="true"></i></a>
      </li>
      <?php if($_SESSION['role']==1){ ?>
      <li class="nav-item active" class="text-danger">
        <a class="nav-link" href="absen.php"><i class="fa fa-home fa-3x" data-toggle="tooltip" data-placement="bot" title="Home" aria-hidden="true"></i><span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item active" class="text-success">
        <a class="nav-link" href="rekap.php"><i class="fa fa-archive fa-3x" data-toggle="tooltip" data-placement="bot" title="Rekap" aria-hidden="true"></i></a>
      </li>
      <?php } if($_SESSION['role']==2){ ?>
      <li class="nav-item active" class="text-success">
        <a class="nav-link" href="topup.php"><i class="fa fa-upload fa-3x" data-toggle="tooltip" data-placement="bot" title="Topup" aria-hidden="true"></i></a>
      </li>
      <?php } if($_SESSION['role']==3){?>
      <li class="nav-item active" class="text-success">
        <a class="nav-link" href="cek_saldo.php"><i class="fa fa-check fa-3x" data-toggle="tooltip" data-placement="bot" title="Cek Saldo" aria-hidden="true"></i></a>
      </li>
      <li class="nav-item active" class="text-success">
        <a class="nav-link" href="merchan.php"><i class="fa fa-download fa-3x" data-toggle="tooltip" data-placement="bot" title="Debet" aria-hidden="true"></i></a>
      </li>
      <?php } ?>
    </ul>
  </div>
<?php } ?>
</nav>
