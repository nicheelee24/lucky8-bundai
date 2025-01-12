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

// Step 1: Aggregate transaction data for users with transactions on 'luckyagent' platform
$transactionPipeline = [
    [
        '$match' => [
            'type' => ['$in' => ['deposit', 'withdrawal']],
            'platform' => 'luckyagent' // Filter by platform
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
            'ApplyDate'=>'$user.date',
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
      'UserID'=>$transaction->_id,
        'UserName' => $transaction->UserName ?? 'N/A',
        'Phone' => $transaction->Phone ?? 'N/A',
        'Status' => $transaction->Status ?? 'N/A',
        'ApplyDate' => $transaction->ApplyDate ? $transaction->ApplyDate->toDateTime()->format('Y-m-d H:i:s') : 'N/A',
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
      <form method="post" action="manage-members.php">
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
          <span style="font-weight:bold;padding-top:35px">Selected Date Range: </span><?php echo $posteddates[0];?> To <?php echo $posteddates[1] ?> <?php } ?>
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
         
           <form method="POST" action="manage-members.php">
           <input type="text" <?php if($searchvalue){?> value="<?php echo $searchvalue?>" <?php }?> name="search"/><button class="btn btn-warning" style="margin:5px" type="submit">Search</button> 
          </form>
        </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body p-0">
        <table class="table">
          <thead>
            <tr>
              <th style="width: 10px">#</th>
              <th>Username</th>
              <th>Type</th>
              <th>Quantity</th>
              <th>Bank</th>
              <th>Bank Balance First</th>
              <th>Bank Balance After</th>
              <th>Credit First</th>
              <th>Credit After</th>
              <th>Number of TF</th>
              <th>Credit First</th>
              <th>TF First</th>
              <th>TF Back</th>
              <th>Data Entry Staff</th>
              <th>Bank Teller</th>
              <th>Filling Staff</th>
              <th>Web</th>
              <th>Add Time</th>
              <th>When Checking Bank</th>
              <th>Time To Add Credit</th>
              <th>Channel</th>
              <th>Slip</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $cnt = 1;
            foreach ($userStats as $usr) {
              ?>
              <tr>
                <td <?php if($usr["Status"]=="Blacklist"){ ?> style="font-weight:bolder" <?php }?><?php if($usr["Status"]=="Block"){ ?> style="color:red" <?php }?>><?php echo $cnt; ?></td>
                <td <?php if($usr["Status"]=="Blacklist"){ ?> style="font-weight:bolder" <?php }?><?php if($usr["Status"]=="Block"){ ?> style="color:red" <?php }?>><?php 
               
                echo $usr["FirstDepositDate"];
              ?>
                
              </td>
                <td <?php if($usr["Status"]=="Blacklist"){ ?> style="font-weight:bolder" <?php }?><?php if($usr["Status"]=="Block"){ ?> style="color:red" <?php }?>><?php echo $usr["FirstTimeDepositedAmount"]; ?></td>

                <td <?php if($usr["Status"]=="Blacklist"){ ?> style="font-weight:bolder" <?php }?><?php if($usr["Status"]=="Block"){ ?> style="color:red" <?php }?>><?php echo 'luckyagent'; ?></td>
                <td <?php if($usr["Status"]=="Blacklist"){ ?> style="font-weight:bolder" <?php }?><?php if($usr["Status"]=="Block"){ ?> style="color:red" <?php }?>><a href="member-details.php?uid=<?php echo $usr["UserName"];?>&_id=<?php echo $usr['UserID'] ?>&_dt=<?php echo $usr["ApplyDate"];?>" target="_self"><?php echo $usr["UserName"]; ?></a></td>
               
              
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

  function acti_deactiv(un,sts)
  {
//alert(un+"-"+sts);
if(sts=='')
{

  if(confirm("Are you sure to UNBLOCK this player?"))
  {
    
        $.ajax({
            url:"controllers/api.php?flag=actDeact",    
            type: "get",    //request type,
            data: {uname: un, stats: sts},
            success:function(){
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
            url:"controllers/api.php?flag=actDeact",    
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
