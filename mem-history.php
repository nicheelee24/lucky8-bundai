<?php
session_start();
require 'vendor/autoload.php';
$dotenv = new Symfony\Component\Dotenv\Dotenv();
$dotenv->load(__DIR__ . '/.env'); 
$agentid ='';
$recsCount=0;
$searchvalue='';
if(isset($_POST["search"]))
{
$searchvalue=$_POST["search"];
//echo $searchvalue;
//die($searchvalue);
}
if(isset($_SESSION['uid']))
{
  $agentid=$_SESSION['uid'];
}
//print_r($agentid);die('..');
$mongourl = $_ENV['mongodburl'] ?? '';
$db = $_ENV['db'] ?? '';
$stDate='';
$edDate='';
if(isset($_POST["reservation"]))
{
$posteddates=explode("-", $_POST["reservation"]);
//print_r($posteddates[0]);
//print_r($posteddates[1]);
$stDate = new MongoDB\BSON\UTCDateTime(strtotime($posteddates[0]) * 1000);
$edDate = new MongoDB\BSON\UTCDateTime(strtotime($posteddates[1]) * 1000);
//die();
}
else
{
  $stDate = new MongoDB\BSON\UTCDateTime(strtotime("-180 days") * 1000);
$edDate = new MongoDB\BSON\UTCDateTime(strtotime("-0 days") * 1000);
}
$client = new MongoDB\Client($mongourl);

$db = $client->gms2024;


$usersCollection = $db->users;
$transactionsCollection = $db->transactions;

// Step 1: Aggregate transaction data for users with transactions on 'luckyama' platform
$transactionPipeline = [
    [
        '$match' => [
            'type' => ['$in' => ['deposit', 'withdrawal']],
            'platform' => 'luckyama', // Filter by platform
            'date' => ['$gte' => $stDate, '$lte' => $edDate]
        ]
    ],
    [
        '$group' => [
            '_id' => '$userid',
            'totalDeposits' => [
                '$sum' => [
                    '$cond' => [['$eq' => ['$type', 'deposit']], '$payAmount', 0]
                ]
            ],
            'totalWithdrawals' => [
                '$sum' => [
                    '$cond' => [['$eq' => ['$type', 'withdrawal']], '$payAmount', 0]
                ]
            ],
            'depositCount' => [
                '$sum' => [
                    '$cond' => [['$eq' => ['$type', 'deposit']], 1, 0]
                ]
            ],
            'withdrawalCount' => [
                '$sum' => [
                    '$cond' => [['$eq' => ['$type', 'withdrawal']], 1, 0]
                ]
            ],
            'firstDepositDate' => [
                '$min' => [
                    '$cond' => [['$eq' => ['$type', 'deposit']], '$date', null]
                ]
            ],
            'firstDepositAmount' => [
                '$first' => [
                    '$cond' => [['$eq' => ['$type', 'deposit']], '$payAmount', null]
                ]
            ],
            'platform' => ['$first' => '$platform'] // Get platform info from transactions
          ]
         
    ],
    [
        '$lookup' => [
            'from' => 'users',
            'localField' => '_id',
            'foreignField' => '_id',
            'as' => 'user'
        ]
    ],
    [
        '$unwind' => '$user'
    ],
    [
        '$project' => [
            'UserName' => '$user.name',
            'Phone' => '$user.phone',
            'Status'=>'$user.status',
            'Platform' => '$platform',
            'FirstDepositDate' => '$firstDepositDate',
            'FirstTimeDepositedAmount' => '$firstDepositAmount',
            'TotalDepositTimes' => '$depositCount',
            'TotalWithdrawTimes' => '$withdrawalCount',
            'TotalDepositAmount' => '$totalDeposits',
            'TotalWithdrawAmount' => '$totalWithdrawals',
            'Profit' => ['$subtract' => ['$totalDeposits', '$totalWithdrawals']]
        ]
    ]
];

$transactionsData = $transactionsCollection->aggregate($transactionPipeline)->toArray();

// Handle DataTables server-side processing
// $request = $_GET;
// $draw = intval($request['draw']);
// $start = intval($request['start']);
// $length = intval($request['length']);
// $search = $request['search']['value']; // Search value

// Filter data based on search
$userStats = [];
foreach ($transactionsData as $transaction) {
    $userStats[] = [
        'UserName' => $transaction->UserName ?? 'N/A',
        'Phone' => $transaction->Phone ?? 'N/A',
        'Status' => $transaction->Status ?? 'N/A',
        'Platform' => $transaction->Platform ?? 'N/A',
        'FirstDepositDate' => $transaction->FirstDepositDate ? $transaction->FirstDepositDate->toDateTime()->format('Y-m-d H:i:s') : 'N/A',
        'FirstTimeDepositedAmount' => $transaction->FirstTimeDepositedAmount ?? 0,
        'TotalDepositTimes' => $transaction->TotalDepositTimes ?? 0,
        'TotalWithdrawTimes' => $transaction->TotalWithdrawTimes ?? 0,
        'TotalDepositAmount' => $transaction->TotalDepositAmount ?? 0,
        'TotalWithdrawAmount' => $transaction->TotalWithdrawAmount ?? 0,
        'Profit' => $transaction->Profit ?? 0
        
    ];
}

// Apply search filter
if ($searchvalue) {
    $userStats = array_filter($userStats, function($row) use ($searchvalue) {
        return stripos($row['UserName'], $searchvalue) !== false || stripos($row['Phone'], $searchvalue) !== false;
    });
}

// Pagination
//$totalRecords = count($userStats);
//$filteredRecords = array_slice($userStats);

// Return JSON response
//$response = [
   // 'draw' => $draw,
   // 'recordsTotal' => $totalRecords,
   // 'recordsFiltered' => $totalRecords, // Adjust if needed for more advanced filtering
 //   'data' => $filteredRecords
//];

//echo json_encode($response);

//print_r($transactionSumsArray);
//echo 'data.................................................';
//echo $_SESSION["prefix"];


//$query = new MongoDB\Driver\Query($filter, $options);
//$rows = $mongo->executeQuery($db.'.users', $query);
//$usersArr = $rows->toArray();
//$recsCount=count($transactionSumsArray);
include 'layout/header.php';
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" id="dvcontent">
  <div class="content-header">
    <div class="container-fluid">

      <div class="row mb-2">
        <div class="col-sm-6">

          <div class="col-sm-3"></div>
          <h1>Manage Members</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Manage Members</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <div class="col-md-12">
    <div class="card">
      <form method="post" action="mem-history.php">
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
          <?php if(isset($_POST['reservation'])){  ?>
          <span style="font-weight:bold;padding-top:35px">Selected Date Range: </span><?php echo $posteddates[0];?> To <?php echo $posteddates[1] ?><a style="margin-left:30px" href="mem-history.php?clearFilter">Clear Filter</a> <?php } ?>
          <!-- /.input group -->
        </div>

        <div class="form-group">
          <label>Cut Time</label>
          <select class="custom-select">
            <option value="-1">Select Time</option>
            <option value="00:00">00:00</option>
            <option value="01:00">01:00</option>
            <option value="02:00">02:00</option>
            <option value="03:00">03:00</option>
            <option value="04:00">04:00</option>
            <option value="05:00">05:00</option>
            <option value="06:00">06:00</option>
            <option value="07:00">07:00</option>
            <option value="08:00">08:00</option>
            <option value="09:00">09:00</option>
            <option value="10:00">10:00</option>
            <option value="11:00">11:00</option>
            <option value="12:00">12:00</option>
            <option value="13:00">13:00</option>
            <option value="14:00">14:00</option>
            <option value="15:00">15:00</option>
            <option value="16:00">16:00</option>
            <option value="17:00">17:00</option>
            <option value="18:00">18:00</option>
            <option value="19:00">19:00</option>
            <option value="20:00">20:00</option>
            <option value="21:00">21:00</option>
            <option value="22:00">22:00</option>
            <option value="23:00">23:00</option>
          </select>
        </div>


        <div class="form-group" placeholder="Select">
          <label>Origin</label>
          <select class="custom-select">
            
            <option value="app">APP</option>
           

          </select>
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
        <h3 class="card-title">All Members</h3>

        <div class="card-tools">
          <!-- <a class="btn btn-primary" href="add-member.php" role="button">Add Member</a> -->
           <form method="POST" action="mem-history.php">
           <input type="text" <?php if($searchvalue){?> value="<?php echo $searchvalue?>" <?php }?> name="search"/><button class="btn btn-primary" type="submit">Search</button>
          </form>
        </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body p-0">
        <table class="table">
          <thead>
            <tr>
              <th style="width: 10px">#</th>
              <th>Date</th>
              <th>First Deposit</th>
              <th>Agent</th>
              <th>Player</th>
              <th>Level</th>
              <th>Origin</th>
              <th>Deposit (Times)</th>
              <th>Deposit (Amount)</th>
              <th>Withdrawl (Times)</th>
              <th>Withdrawl (Amount)</th>

              <th>Profit</th>
              <th colspan="3" style=" width: 90px;">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $cnt = 1;
            foreach ($userStats as $usr) {
              ?>
              <tr>
                <td><?php echo $cnt; ?></td>
                <td><?php 
               
                echo $usr["FirstDepositDate"];
              ?>
                
              </td>
                <td><?php echo $usr["FirstTimeDepositedAmount"]; ?></td>

                <td><?php echo 'luckyama'; ?></td>
                <td><a href="history.php?uid=<?php echo $usr["UserName"];?>" target="_self"><?php echo $usr["UserName"]; ?></a></td>
                <td><?php echo 'NORMAL'; ?></td>
                <td><?php echo 'APP'; ?></td>
                <td><?php echo $usr["TotalDepositTimes"]; ?></td>
                <td><?php echo $usr["TotalDepositAmount"]; ?></td>
                <td><?php echo $usr["TotalWithdrawTimes"]; ?></td>
                <td><?php echo $usr["TotalWithdrawAmount"]; ?></td>

                <td>
                  <?php

                  $profit=$usr["Profit"];
                  
                  echo $profit;

                  ?>




                </td>
                <td>

              <?php 
              $status="";
              $un=$usr['UserName'];
              
            
              if($usr["Status"]=="Active")
              {
                $status="Active";
                ?>
                <button style="background: url(dist/img/active.png);width:26px;height:26px;border:0px" title="Active" type="button" class="btn btn-info" onclick="acti_deactiv('<?php echo $un;?>','<?php echo $usr['Status'];?>')"></button>
                <?php
              }
              else
              {
                $status="Blocked";
                ?>
                <button type="button" title="Access Blocked" class="btn btn-info" onclick="acti_deactiv('<?php echo $un;?>','<?php echo $usr['Status'];?>')" style="background: url(dist/img/inactive.png);width:30px;height:30px;border:0px"></button>
                <?php
              }
              ?>
                  

                  <!-- <button type="button" class="btn btn-danger">Delete</button> -->
                </td>
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

  function acti_deactive(un,sts)
  {
alert(un+"-"+sts);
if(sts=='')
{

  if(confirm("Are you sure to UNBLOCK this player?"))
  {
    
        $.ajax({
            url:"controllers/api.php?flag=actDeactt",    
            type: "get",    //request type,
            data: {uname: un, stats: sts},
            success:function(dt){
              alert(dt);
              location.reload();
            }
        });
    

  }
}
else
{
  if(confirm("Are you sure to BLOCK this player?"))
{
  $.ajax({
            url:"controllers/api.php?flag=actDeactt",    
            type: "get",    //request type,
            data: {uname: un, stats: sts},
            success:function(){
              location.reload();
            }
        });
    
}
}
  }
  //Date range picker
  $('#reservation').daterangepicker();
  $(document).ready(function () {
    //setInterval(function() {
       // $.get("manage-members.php", function (result) {
        //  alert(result);
          //  $('#dvcontent').html(result);
      //  });
   // }, 3000);
});
</script>
