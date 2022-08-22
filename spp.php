
    <?php require "partials/head.php";
    require "partials/sidebar.php"; ?>
    <div class="container"><span class="float-md-right"><i class="fa fa-clock-o" aria-hidden="true"></i><st id="time"></st></span>
    <?php if(!empty($_SESSION['id_user'])){
        // if($_SESSION['role']!=3){
            // session_destroy();
            // session_unset();
            // header("Location: index.php");
        // } 
    }else{
        header("Location: index.php");
    } ?>
        <h2 class="text-primary mt-4">Pembayaran SPP </h2>

        <div class="form-group">
            <label for="rfidnumber">RFID Tag Number</label>
            <input type="text" class="form-control" id="inputs" aria-describedby="rfidnumber" placeholder="No RFID">
            <small id="rfidnumber" class="form-text text-muted">This System Automatically</small>
        </div>

        <div class="container mb-4">
            <h3 id="classInformation"></h3>
            <div class="p-3 mb-2 text-white" id="tampilMessage">
                <!-- <b>Name</b> : Daniel Aditama <b>Course</b> : ERP Planning <b>Date/Time</b> : Mon,9-10-17/07:59:59 <b>Status</b>: Early -->
            </div>
            <div class="alert" role="alert"></div>
        </div>

        

        

    </div>
    <?php require "partial/footer.php"; ?>
    <script type="text/javascript">
        var timestamp = "<?=date('H:i:s');?>";

        function updateTime() {
            $('#time').html(Date(timestamp));
            timestamp++;
        }
        $(function() {
            setInterval(updateTime, 1000);
        });

$(document).ready(function() {

  // Input field langsung fokus
  $('#inputs').focus();

  // jika ada perubahan di input field [ENTER], akan mentrigger
  $("#inputs").change(function() {
    var id = $('#inputs').val();

    $.ajax({
        url: 'aksispp.php',
        type: 'post',
        data: {
          id: id
        }
      })
      .done(function(data1) {
        // console.log(data1);

        // hapus alert danger dan sukses agar bisa bergantian class
        // $('.alert').removeClass('alert-danger alert-success');
        $('#tampilMessage').removeClass('bg-danger bg-success');

        // if (data1 <= 0) {
        //   // $('.alert').addClass('alert-danger').html("RFID belum terdaftar di dalam system kami: " + "<b>{ " + id + " }</b>");
        //   $('#classInformation').html("Whoops, there was an error").addClass('display-4');
        //   $('#tampilMessage').addClass('bg-danger').html("Saldo tidak cukup");
        // } else {
        //   // $('.alert').addClass('alert-success').html(data);
        //   $('#classInformation').html("Class Information").addClass('display-4');
          $('#tampilMessage').html(data1);
        // }

        $('#inputs').val(""); //Mengkosongkan input field
        $('#inputs').focus(); //mengembalikan cursor ke input field

      })
      .fail(function(data1) {
        console.log(data1);
      });
  });
});

    </script>
<?php require "partials/footer.php"; ?>