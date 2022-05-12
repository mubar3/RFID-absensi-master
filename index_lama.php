<!DOCTYPE html>
<html>
<body>
	<?php require "partial/nav.php"; 
	?>
	<div class="container"><span class="float-md-right"><i class="fa fa-clock-o" aria-hidden="true"></i><st id="time"></st></span>
		<h2 class="text-primary mt-4">Login </h2>

		<div class="form-group">
		<form  role="form" action="" method="post" autocomplete="off">
			<label for="rfidnumber">Username</label>
			<input type="text" name="username" class="form-control" aria-describedby="rfidnumber" placeholder="Username" required>
			<label for="rfidnumber">Pass</label>
			<input type="password" class="form-control" name="pass" aria-describedby="rfidnumber" placeholder="pass" required>
			<br>
			<button name="login" type='submit' class='btn bg-orange btn-block'><i class="fa fa-sign-in"></i> MASUK</button>
		</form>
			
		</div>

		<div class="container mb-4">
			<h3 id="classInformation"></h3>
			<div class="p-3 mb-2 text-white" id="tampilMessage">
				<!-- <b>Name</b> : Daniel Aditama <b>Course</b> : ERP Planning <b>Date/Time</b> : Mon,9-10-17/07:59:59 <b>Status</b>: Early -->
			</div>
			<div class="alert" role="alert"></div>
		</div>

	<?php
		require "vendor/autoload.php";

use StelinDB\Database\QueryBuilder;
use Carbon\Carbon;

$dotenv = new \Dotenv\Dotenv(__DIR__);
            $dotenv->load();
$now = new Carbon;
$now->setTimezone('Asia/Jakarta');

$qb = new QueryBuilder(\StelinDB\Database\Connection::Connect());


		if(isset($_POST['login'])){

  $username = $_POST['username'];
  $pass = md5($_POST['pass']);

    $user = $qb->RAW(
    "SELECT * FROM user WHERE username='".$username."'and pass=?",[$pass]);



  if (array_key_exists(0, $user)) {
  	$user = $user[0]; 
  	$_SESSION['id_user']     = $user->id_user;
    $_SESSION['role']=$user->role;
    // print_r($_SESSION['role']);
    // die();
    if($_SESSION['role']==1){
		header("Location: absen.php");
		}
    if($_SESSION['role']==2){
		header("Location: topup.php");
		}
    if($_SESSION['role']==3){
		header("Location: merchan.php");
		}


  }

}
	?>

		

	</div>
	<?php require "partial/footer.php"; ?>
</body>

</html>
