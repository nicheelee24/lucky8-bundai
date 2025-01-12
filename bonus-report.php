<?php
session_start();
require 'vendor/autoload.php';

$dotenv = new Symfony\Component\Dotenv\Dotenv();

$dotenv->load(__DIR__ . '/.env'); 
$agentid ='';
$recsCount=0;
$searchvalue='';

if(isset($_SESSION['uid']))
{
  $agentid=$_SESSION['uid'];
}

$mongourl = $_ENV['mongodburl'] ?? '';
$db = $_ENV['db'] ?? '';
$stDate='';
$edDate='';


$mongo=new MongoDB\Driver\Manager($mongourl);
$options = [];
$filter=[];
$query = new MongoDB\Driver\Query($filter,$options);
$rows = $mongo->executeQuery($db.'.bonus',$query);
$bonusData = $rows->toArray();

include 'layout/header.php';

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" id="dvcontent">
  <div class="content-header">
    <div class="container-fluid">

      <div class="row mb-2">
        <div class="col-sm-6">

          <div class="col-sm-3"></div>
          <h1>Bonus Report</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Reports</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <div class="col-md-12">
    <div class="card">
      <form method="post" action="">
      <div style="margin-left:20px" class="row">
     
        <div class="form-group">
          <label>Date range:</label>

          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">
                <i class="far fa-calendar-alt"></i>
              </span>
            </div>
            <input type="text" class="form-control float-right" id="reservation" name="reservation">
          </div>
          <br/>
         
          <!-- /.input group -->
        </div>

       


       
        <div class="form-group">

          <button type="submit" style="margin-top:32px;margin-left:10px"
            class="btn btn-block btn-warning">Filter</button>
        </div>

      </div>
      </form>

      <div class="row" style="margin-left:20px;display:none">
        <div class="col-lg-2 col-6">
          <!-- small box -->
          <div class="small-box bg-info">
            <div class="inner">
              <h3><?php echo 0;?></h3>

              <p> Volunteer</p>
            </div>
            <div class="icon">
              <i class="fa fa-users"></i>
            </div>

          </div>
        </div>

        <div class="col-lg-2 col-6">
          <!-- small box -->
          <div class="small-box bg-info">
            <div class="inner">
              <h3>0</h3>

              <p> Signup & Deposit</p>
            </div>
            <div class="icon">
              <i class="fa fa-user-plus"></i>
            </div>

          </div>
        </div>

        <div class="col-lg-2 col-6">
          <!-- small box -->
          <div class="small-box bg-info">
            <div class="inner">
              <h3><?php echo ''; ?></h3>

              <p> Deposit</p>
            </div>
            <div class="icon">
              <i class="fa fa-credit-card"></i>
            </div>

          </div>
        </div>

        <div class="col-lg-2 col-6">
          <!-- small box -->
          <div class="small-box bg-info">
            <div class="inner">
              <h3><?php echo '' ?></h3>

              <p> Withdraw</p>
            </div>
            <div class="icon">
              <i class="fa fa-minus-square"></i>
            </div>

          </div>
        </div>
        <div class="col-lg-2 col-3">
          <!-- small box -->
          <div class="small-box bg-info">
            <div class="inner">
              <h3>0</h3>

              <p> Profit</p>
            </div>
            <div class="icon">
              <i class="fa fa-usd"></i>
            </div>

          </div>
        </div>
      </div>

      <div class="card-header">
        <h3 class="card-title">Bonus Report</h3>

        <div class="card-tools">
         
          
        </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body p-0">
        <table class="table">
          <thead>
            <tr>
              <th style="width: 10px"></th>
              <th>Username</th>
              <th>Type</th>
              <th>Bonus Type</th>
              <th>Quantity</th>
              <th>Top Up</th>
              <th>Cash Balance First</th>
              <th>Cash Balance After</th>
              <th>The Board Before</th>
              <th>Backboard</th>
              <th>Before Credit</th>
              <th>After Credit</th>
              <th>Credit First</th>
              <th>Credit After</th>
              <th>Number of TF</th>
             
              <th>TF First</th>
              <th>TF Back</th>
              <th>Data Entry Staff</th>
              <th>Credit Top-up Staff</th>
              <th>Web</th>
              <th>Add Time</th>
             
            </tr>
          </thead>
          <tbody>
            <?php
            $cnt = 1;
            foreach ($bonusData as $usr) {
             
              ?>

              <tr>
                <td><?php echo $cnt; ?></td>
                <td><?php echo $usr->username; ?></td>
                <td><?php echo 'Bonus'; ?></td>
                <td><?php echo ''; ?></td>
                <td><?php echo $usr->quantity; ?></td>
                <td><?php echo $usr->topUp; ?></td>
                <td><?php echo '-'; ?></td>
                <td><?php echo '-'; ?></td>
                <td><?php echo '-'; ?></td>
                <td><?php echo '-'; ?></td>
                <td><?php echo $usr->cashBalanceFirst; ?></td>
                <td><?php echo $usr->cashBalanceAfter; ?></td>
                <td><?php echo '-'; ?></td>
                <td><?php echo '-'; ?></td>
                <td><?php echo ''; ?></td>
                
                <td><?php echo '0.00'; ?></td>
                <td><?php echo '0.00'; ?></td>
                <td><?php echo 'luckyagent'; ?></td>
                <td><?php echo 'luckyagent'; ?></td>
                <td><?php echo "lucky8online.com" ?></td>
                <td><?php echo $usr->addTime; ?></td>
              
              </tr>
              <?php
              $cnt++;
            }

            ?>


          </tbody>
        </table>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->




    </table>
  </div>
  <!-- /.card-body -->
</div>
<!-- /.card -->
</div>

<?php
include 'layout/footer.php';
?>


<script>

  
</script>
