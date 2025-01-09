<?php
session_start();
//print_r($_SESSION['agent']);
//die('..');
require 'vendor/autoload.php';
include 'layout/header.php';

$dateRange='';
$type='';
$stDate='';
$edDate='';
if(isset($_POST["type"]))
{
  $type=$_POST["type"];
  //echo $type;
}
//die('..');
if(isset($_POST["reservation"]))
{
  $dateRange=explode("-", $_POST["reservation"]);
//print_r($dateRange[0]);
//print_r($dateRange[1]);
//die("..");
$stDate = new MongoDB\BSON\UTCDateTime(strtotime($dateRange[0]) * 1000);
$edDate = new MongoDB\BSON\UTCDateTime(strtotime($dateRange[1]) * 1000);

}

$dotenv = new Symfony\Component\Dotenv\Dotenv();
$dotenv->load(__DIR__ . '/.env'); 
include "api__url.php";


$uid=0;
$agentid='';
$prefix='';
if(isset($_SESSION['prefix']))
{
  $prefix=$_SESSION['prefix'];
}
if(isset($_SESSION['uid']))
{
  
  $agentid=$_SESSION['uid'];
}
$filter;
if(isset($_GET['uid']))
{
  $uid =$_GET['uid'];
  $_SESSION["filterUID"]=$uid;
}
if(isset($_SESSION["filterUID"]))
{
  
  $uid =$_SESSION["filterUID"];
  
}


//echo $uid;
//die("..");
$mongourl = $_ENV['mongodburl'] ?? '';
$db = $_ENV['db'] ?? '';
$mongo = new MongoDB\Driver\Manager($mongourl);


if($uid==0)
{
  
    $uid=$_SESSION["platform"];
    $filter=['agentId'=>$uid];
  
  
  
}
else
{
  $_SESSION["filterUID"]=$uid;
  $filter=['userId'=>$uid];

  if($stDate!='')
  {
    $filter=['userId'=>$uid,'date' => ['$gte' => $stDate]];

    if(isset($_POST["type"]))
{
  if($type!=0)
  {
  $filter=['userId'=>$uid,'gameType'=>$type,'date'=>$stDate];
  }
  
}

  }
  if(isset($_POST["type"]) && $stDate=='')
{
  if($type!=0)
  {
  $filter=['userId'=>$uid,'gameType'=>$type];
  }
  
}
  
}
//print_r($uid);
//die("..");
//print_r($filter);

$options = ['sort' => ['betTime' => -1]];
$query = new MongoDB\Driver\Query($filter,$options);
$rows = $mongo->executeQuery($db.'.bets',$query);
$betsArr = $rows->toArray();

//print_r($query);

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">

      <div class="row mb-2">
        <div class="col-sm-6">

          <div class="col-sm-3"></div>
          <h1>History</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">History</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <div class="col-md-12">
    <div class="card">
      <div class="card-header">


        <br />
        <form method="post" action="history.php">
        <div class="row">

          <div class="form-group">
            <label>Date Time</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="far fa-clock"></i></span>
              </div>
              <input type="text" class="form-control float-right" id="reservation" name="reservation">
            </div>
            <br/>
          <?php if(isset($_POST['reservation'])){  ?>
          <span style="font-weight:bold;padding-top:35px">Selected Date Range: </span><?php echo $dateRange[0];?> To <?php echo $dateRange[1] ?> <?php } ?>
          </div>

          <div class="form-group">
            <label>Type</label>
            <select class="custom-select" id="type" name="type">
              <option value="0">Select Game / Other Type</option>
              <option value="EGAME" <?php if($type=='EGAME'){ ?>selected <?php } ?>>EGAME</option>
              <option value="ESPORT" <?php if($type=='ESPORT'){ ?>selected <?php } ?>>ESPORT</option>
              <option value="FISH" <?php if($type=='FISH'){ ?>selected <?php } ?>>FISH</option>
              <option value="LIVE" <?php if($type=='LIVE'){ ?>selected <?php } ?>>LIVE</option>
              <option value="LOTTO" <?php if($type=='LOTTO'){ ?>selected <?php } ?>>LOTTO</option>
              <option value="SLOT" <?php if($type=='SLOT'){ ?>selected <?php } ?> >SLOT</option>
              <option value="TABLE" <?php if($type=='TABLE'){ ?>selected <?php } ?>>TABLE</option>
              <option value="VIRTUAL" <?php if($type=='VIRTUAL'){ ?>selected <?php } ?>>VIRTUAL</option>
              <option value="DEALER_TIPPING" <?php if($type=='DEALER_TIPPING'){ ?>selected <?php } ?>>Other - DEALER_TIPPING</option>
              <option value="PROMOTION" <?php if($type=='PROMOTION'){ ?>selected <?php } ?>>Other - PROMOTION</option>
              <option value="STREAMER_TIPPING" <?php if($type=='STREAMER_TIPPING'){ ?>selected <?php } ?>>Other - STREAMER_TIPPING</option>
            </select>
          </div>


          <div class="form-group" placeholder="Select">
            <label>Currency</label>
            <select class="custom-select" id="currency" name="currency">
              
              <option value="THB">THB</option>

            </select>
          </div>
          <div class="form-group">

            <button type="submit" style="margin-top:32px;margin-left:10px" id="btnFilter" onclick="filterData()"
              class="btn btn-block btn-warning">Filter</button>
          </div>

        </div>
</form>
        <h3 class="card-title">Bets History</h3>
      </div>
      <!-- /.card-header -->
      <div class="card-body p-0" style="overflow-x:scroll">

        <table class="table" style="width:100%;overflow:scroll">
          <thead>
            <tr>
              <th style="width: 10px">#</th>
              <th>User ID</th>
              <th>Game Type</th>
              <th>Game Name</th>
              <th>Game Code</th>
              <th>Platform</th>
              <th>PlatformTxId</th>
              <th>Currency</th>
             
              <th>Bet Amount</th>
              <th>Turnover Amount</th>
              <th>Win Amount</th>





            </tr>
          </thead>
          <tbody>
            <?php 
            $cnt=1;
           
           // $players = array();

foreach($betsArr as $bet)
{
  $cnt;
 
  //print_r($_SESSION["utype"]);
      
 if(isset($_SESSION["utype"]))
 {
  
  if($_SESSION["utype"]!="PLAYER")
  {
  if(strpos($bet->userId, $prefix)!== false) {
    
            ?>
            <tr>
              
              <td><?php echo $cnt;?></td>
              <td><?php echo $bet->userId; ?></td>
              <td><?php echo $bet->gameType; ?></td>
              <td><?php 
              if(property_exists($bet, 'gameName'))
              {
                echo $bet->gameName; 
              }else
              {
                echo '';
              }
              ?></td>
              
              <td><?php echo $bet->gameCode; ?></td>
              <td><?php echo $bet->platform; ?></td>
              <td><?php echo $bet->platformTxId; ?></td>
              <td><?php 
              if(property_exists($bet, 'currency'))
              {
                echo $bet->currency; 
              }else
              {
                echo '';
              }
              ?></td>
              
             
            
               <td><?php 
              if(property_exists($bet, 'betAmount'))
              {
                echo $bet->betAmount; 
              }else
              {
                echo '';
              }
              ?></td>
              <td><?php 
              if(property_exists($bet, 'turnover'))
              {
                echo $bet->turnover; 
              }else
              {
                echo '';
              }
              ?></td>
              <td><?php 
              if(property_exists($bet, 'winAmount'))
              {
                if($bet->winAmount>0)
                {
                  echo '<span style="color:green;font-weight:bold;font-size:14px">'.$bet->winAmount.'</span>';
                }
                else
                {
                echo '<span style="color:#cfcfcf;font-size:14px">'.$bet->winAmount.'</span>';
                
              
                }
              }else
              {
                echo '';
              }
              ?></td>





            </tr>
<?php
$cnt++;

            }else{?>
              <tr>
    
              <td><?php echo $cnt;?></td>
              <td><?php echo $bet->userId; ?></td>
              <td><?php echo $bet->gameType; ?></td>
              <td><?php 
              if(property_exists($bet, 'gameName'))
              {
                echo $bet->gameName; 
              }else
              {
                echo '';
              }
              ?></td>
              
              <td><?php echo $bet->gameCode; ?></td>
              <td><?php echo $bet->platform; ?></td>
              <td><?php echo $bet->platformTxId; ?></td>
              <td><?php 
              if(property_exists($bet, 'currency'))
              {
                echo $bet->currency; 
              }else
              {
                echo '';
              }
              ?></td>
              
             
            
               <td><?php 
              if(property_exists($bet, 'betAmount'))
              {
                echo $bet->betAmount; 
              }else
              {
                echo '';
              }
              ?></td>
              <td><?php 
              if(property_exists($bet, 'turnover'))
              {
                echo $bet->turnover; 
              }else
              {
                echo '';
              }
              ?></td>
              <td><?php 
              if(property_exists($bet, 'winAmount'))
              {
                if($bet->winAmount>0)
                {
                  echo '<span style="color:green;font-weight:bold;font-size:14px">'.$bet->winAmount.'</span>';
                }
                else
                {
                echo '<span style="color:#cfcfcf;font-size:14px">'.$bet->winAmount.'</span>';
                
              
                }
              }else
              {
                echo '';
              }
              ?></td>





            </tr>
            
          
          
           

  
<?php

          }}
        }}
      
       //   print_r($players);
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
    $('#reservation').daterangepicker();
</script>