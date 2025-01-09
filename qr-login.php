<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Log In</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <p> <b>Backoffice Login</b></p>
  </div>
  <!-- /.login-logo -->
  <?php
  session_start();
  use Google\Authenticator\GoogleAuthenticator;
  use Google\Authenticator\GoogleQrUrl;
  require_once "vendor/autoload.php";
  $secret ="";
  $googleAuthenticator = new GoogleAuthenticator();
  

  if (!isset($_SESSION['storedSecrect'])) {
   print_r("code is blank..".$secret);
    $secret = $googleAuthenticator->generateSecret();
  $_SESSION["storedSecrect"]=$secret;
  }
  else
  {
    $secret =$_SESSION['storedSecrect'];
    print_r("code exists...".$secret);
    
  }

  
 
  
  
  $qrCodeUrl = GoogleQrUrl::generate('Backend', $secret, 'bundaii.com/ama-bundai/index.php');

 
 //print_r($qrCodeUrl);
 //die("..");
 ?>
  
  <div class="card">
    <div class="card-body login-card-body">
      

      <form method="post" action="controllers/api.php?flag=qrscan">
       
       
        <div class="input-group mb-3" style="text-align:center">
          <?php 
if(!isset($_SESSION['qrscanned'])){
 
?>
   <scan style="margin-bottom:10px"> Scan QR code using Google authenticator</scan> 
         
          
          
         

            <img src="<?php echo $qrCodeUrl; ?>" alt="Scan this QR code with your Google Authenticator app"
              style="width: 200px; height=200px;margin-left:20%">

           
         

        <?php }else{
 print_r("qr scanned..".$_SESSION["qrscanned"]);

        } ?>
        </div>
      
          
          
         
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="2fa" id="2fa" required placeholder="Enter 2FA Code">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
      
          <!-- /.col -->
          <div class="col-4 ">
        <button type="submit" class="btn btn-primary">Sign In</button>
              <!-- <a class="btn btn-primary" href="dashboard.php" role="button">Sign In</a> -->
          </div>
          <!-- /.col -->
        </div>
      </form>

     
</div>

</body>
</html>
