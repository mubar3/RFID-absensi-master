<?php require "partials/head.php"; ?>

<body class="bg-gradient-primary">

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
    $_SESSION['nama_user']     = $user->username;
    $_SESSION['role']=$user->role;
    // print_r($_SESSION['role']);
    // die();
    // if($_SESSION['role']==1){
    //     header("Location: absen.php");
    //     }
    // if($_SESSION['role']==2){
    //     header("Location: topup.php");
    //     }
    // if($_SESSION['role']==3){
    //     header("Location: merchan.php");
    //     }
    header("Location: home.php");


  }

}
    ?>

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block" style="background: url('https://baradesain.files.wordpress.com/2021/03/tut-wuri-handayani-logo-featured-03.jpg?w=1200');
                                background-position: center;
                                background-size: cover;
                                "
                                ></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Selamat Datang</h1>
                                    </div>
                                    <form class="user" role="form" action="" method="post" autocomplete="off">
                                        <div class="form-group">
                                            <input type="text" name="username" class="form-control form-control-user"
                                                id="exampleInputEmail" aria-describedby="emailHelp"
                                                placeholder="Username...">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="pass" class="form-control form-control-user"
                                                id="exampleInputPassword" placeholder="Password">
                                        </div>
                                        <!-- <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck">
                                                <label class="custom-control-label" for="customCheck">Remember
                                                    Me</label>
                                            </div>
                                        </div> -->
                                        <button name="login" type='submit' class="btn btn-primary btn-user btn-block">
                                            Login
                                        </button>
                                        <hr>
                                       <!--  <a href="index.html" class="btn btn-google btn-user btn-block">
                                            <i class="fab fa-google fa-fw"></i> Login with Google
                                        </a>
                                        <a href="index.html" class="btn btn-facebook btn-user btn-block">
                                            <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook
                                        </a> -->
                                    </form>
                                    <hr>
                                    <!-- <div class="text-center">
                                        <a class="small" href="forgot-password.html">Forgot Password?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="register.html">Create an Account!</a>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</body>
<?php require "partials/footer.php"; ?>