<?php
session_start();
require_once "vendor/autoload.php";
include 'layout/header.php';
//session_start();
$uid = "";

if (isset($_GET["agtid"])) {
    $uid = $_GET["agtid"];
}
$mongo = new MongoDB\Driver\Manager("mongodb+srv://nicheelee24:B0wrmtGcgtXKoXWN@cluster0.8yb8idj.mongodb.net/games2024?retryWrites=true&w=majority&appName=Cluster0");
$filter = ['agentid' => $uid];
$options = [];
$query = new MongoDB\Driver\Query($filter, $options);
$rows = $mongo->executeQuery('gms2024.agents', $query);
$agntsArr = $rows->toArray();
$cnt = count($agntsArr);

use Google\Authenticator\GoogleAuthenticator;
use Google\Authenticator\GoogleQrUrl;

$googleAuthenticator = new GoogleAuthenticator();
$secret = $googleAuthenticator->generateSecret();
$qrCodeUrl = GoogleQrUrl::generate('employeename', $secret, 'Backoffice');
?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">

        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Manage Agent</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-3"></div>

        <div class="col-md-6">

          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Add Agent</h3>
            </div>
            <?php
            if ($uid != ""){            
            ?>
            <form method="post" action="controllers/api.php?flag=updAgent&id=<?php echo $uid; ?>">
              <?php } ?>
              <?php
            if ($uid == ""){            
            ?>
            <form method="post" action="controllers/api.php?flag=newAgent">
              <?php } ?>
              <div class="card-body">
                <div class="form-group">
                  <label>Agent ID</label>
                  <?php if ($uid != ""){?>
                  <input type="text" class="form-control"  value="<?php if ($uid != "") 
                                            echo $agntsArr[0]->agentid; ?>"  name="agentid" placeholder="Agent ID">
                                            <?php } ?>
                                            <?php if ($uid == ""){?>
                  <input type="text" class="form-control"  name="agentid" placeholder="Agent ID">
                                            <?php } ?>
                </div>
                <div class="form-group">
                  <label>Agent Name</label>
                  <input type="text" class="form-control"  value="<?php if ($uid != "")
                                            echo $agntsArr[0]->agentname; ?>" name="agentname" placeholder="Agent Name">
                </div>
                <div class="form-group">
                  <label>User ID</label>
                  <input type="text" class="form-control"  value="<?php if ($uid != "")
                                            echo $agntsArr[0]->userid; ?>" name="userid"  placeholder="User ID">
                </div>

                <div class="form-group">
                  <label>Password</label>
                  <input type="password"  class="form-control" value="<?php if ($uid != "")
                                            echo $agntsArr[0]->pwd; ?>" name="password"  placeholder="Password">
                </div>
                <div class="form-group">
                  <label>Platform</label>
                  <input type="text" class="form-control"  name="platform" value="<?php if ($uid != "")
                                            echo $agntsArr[0]->platform; ?>" placeholder="Platform">
                </div>
                <div class="form-group">
                  <label>Percentage</label>
                  <input type="text" class="form-control" name="percentage"  value="<?php if ($uid != "")
                                            echo $agntsArr[0]->percentage; ?>"  placeholder="Percentage">
                </div>
                <div class="form-group">
                  <label>Parent</label>
                  <input type="text" class="form-control" name="parent"  value="<?php if ($uid != "")
                                            echo $agntsArr[0]->parent; ?>"  placeholder="Parent">
                </div>
                <div class="form-group">
                  <label>Prefix</label>
                  <input type="text" class="form-control"  name="prefix" value="<?php if ($uid != "")
                                            echo $agntsArr[0]->prefix; ?>"  placeholder="Prefix">
                </div>
                <div class="form-group">
                  <label>URL</label>
                  <input type="text"  class="form-control" name="url" value="<?php if ($uid != "")
                                            echo $agntsArr[0]->url; ?>"  placeholder="URL">
                </div>
               

                <div class="form-group">

                                
                
                 
                  

                  <!-- /.card-body -->

                  <div class="card-footer">
                  <?php
            if ($uid != ""){            
            ?>
                    <button type="submit" style="display:none" class="btn btn-primary">Save</button>
                    <?php }else{?>
                      <button type="submit"  class="btn btn-primary">Save</button>

                      <?php } ?>
                  </div>
            </form>
          </div>
        </div>
        <!-- /.col -->

        <!-- /.col -->
      </div>
      <div class="col-md-3"></div>

      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>

<?php
include 'layout/footer.php';
?>