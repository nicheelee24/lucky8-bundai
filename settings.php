<?php
session_start();
require_once "vendor/autoload.php";
include 'layout/header.php';
//session_start();
$uid = "";
//if (isset($_GET["eid"])) {
   // $uid = $_GET["eid"];
//}
 $mongo = new MongoDB\Driver\Manager("mongodb+srv://nicheelee24:IEX63RWtZZ5CSSuD@serverlessinstance0.uesvbfh.mongodb.net/?retryWrites=true&w=majority&appName=ServerlessInstance0");
$filter = [];
 $options = [];
 $query = new MongoDB\Driver\Query($filter,$options);
 $rows = $mongo->executeQuery('gms2024.settings', $query);
 $agntArr = $rows->toArray();
$cnt = count($agntArr);
echo $cnt;
//die("");
// use Google\Authenticator\GoogleAuthenticator;
// use Google\Authenticator\GoogleQrUrl;

// $googleAuthenticator = new GoogleAuthenticator();
// $secret = $googleAuthenticator->generateSecret();
// $qrCodeUrl = GoogleQrUrl::generate('employeename', $secret, 'Backoffice');
?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                <div class="col-sm-3"></div>
                <h1>Settings</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Settings</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
</div>

    <!-- Main content -->
    <div class="col-md-12">
    <div class="card">
      <div class="card-header">
                        <form method="post" action="controllers/api.php?flag=settings">
                            <div class="card-body">
                               
                                <div class="form-group">
                                    <label>Percentage Value</label>
                                    <input type="text" name="percentage" class="form-control" required
                                        value="<?php 
                                            echo $agntArr[0]->percentage; ?>"
                                        placeholder="Percentage">
                                </div>
                               
                                

                                   
                                   
                                 


                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                        </form>
                    </div>
                </div>
                <!-- /.col -->

                <!-- /.col -->
            </div>
           

            <!-- /.row -->
        </div><!-- /.container-fluid -->
   
    <!-- /.content -->
</div>

<?php
include 'layout/footer.php';
?>