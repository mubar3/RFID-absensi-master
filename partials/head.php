<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- <link rel="icon" href="https://media.istockphoto.com/vectors/contactless-payment-vector-icon-credit-card-hand-wireless-nfc-pay-vector-id1281706307?k=20&m=1281706307&s=170667a&w=0&h=u4ZZmlb_c9IDZnQ4f8GxfFGFxgRbiZjjHmDn9w7SRXQ=" type="image/icon type"> -->
    <link rel="icon" href="asset/desain/icon.png" type="image/icon type">
    <title>KlikTap NFC</title>

    <!-- tags input -->
    
    <?php $server="http://localhost/RFID-absensi-master/";?>


    <!-- Custom fonts for this template-->
    <link href="./asset/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="./asset/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="./asset/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>
<?php session_start();  
function resizer ($source, $destination, $size, $quality=null) {
// $source - Original image file
// $destination - Resized image file name
// $size - Single number for percentage resize
// Array of 2 numbers for fixed width + height
// $quality - Optional image quality. JPG & WEBP = 0 to 100, PNG = -1 to 9
 
  // (A) FILE CHECKS
  // Allowed image file extensions
  $ext = strtolower(pathinfo($source)['extension']);
  // print_r($ext);die();
  if (!in_array($ext, ["bmp", "gif", "jpg", "jpeg", "png", "PNG", "webp"])) {
    // throw new Exception('Invalid image file type');
    echo'<div class="col-lg-12 mb-4">
              <div class="card bg-danger text-white shadow">
                  <div class="card-body">
                      Gagal
                      <div class="text-white-50 small">Foto tidak didukung</div>
                  </div>
              </div>
          </div>';die();
  }
 
  // Source image not found!
  if (!file_exists($source)) {
    throw new Exception('Source image file not found');
  }

  // (B) IMAGE DIMENSIONS
  $dimensions = getimagesize($source);
  $width = $dimensions[0];
  $height = $dimensions[1];

  if (is_array($size)) {
    $new_width = $size[0];
    $new_height = $size[1];
  } else {
    $new_width = ceil(($size/100) * $width);
    $new_height = ceil(($size/100) * $height);
  }

  // (C) RESIZE
  // Respective PHP image functions
  $fnCreate = "imagecreatefrom" . ($ext=="jpg" ? "jpeg" : $ext);
  $fnOutput = "image" . ($ext=="jpg" ? "jpeg" : $ext);

  // Image objects
  $original = $fnCreate($source);
  $resized = imagecreatetruecolor($new_width, $new_height); 

  // Transparent images only
  if ($ext=="png" || $ext=="gif" || $ext=="jpg") {
    imagealphablending($resized, false);
    imagesavealpha($resized, true);
    imagefilledrectangle(
      $resized, 0, 0, $new_width, $new_height,
      imagecolorallocatealpha($resized, 255, 255, 255, 127)
    );
  }

  // Copy & resize
  imagecopyresampled(
    $resized, $original, 0, 0, 0, 0, 
    $new_width, $new_height, $width, $height
  );

  // Correct image orientation
if (!in_array($ext, ["png", "PNG"])) {
  $exif = exif_read_data($source);
  if ($exif && isset($exif['Orientation'])) {
    $orientation = $exif['Orientation'];
    switch ($orientation) {
      case 1:
        // no rotation necessary
        break;

      case 3: 
        $resized = imagerotate($resized,180,0);
        break;
                                 
      case 6: 
        $resized = imagerotate($resized,270,0);
        break;
                     
      case 8: 
        $resized = imagerotate($resized,90,0);  
        break;
    }
  }
}

  // (D) OUTPUT & CLEAN UP
  if (is_numeric($quality)) {
    $fnOutput($resized, $destination, $quality);
  } else {
    $fnOutput($resized, $destination);
  }
  imagedestroy($original);
  imagedestroy($resized);
}

?>