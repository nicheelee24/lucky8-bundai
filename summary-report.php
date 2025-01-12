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
$bonusCollection = $db->bonus;
$bonusSums = $bonusCollection->aggregate([

  [

    '$group' => [
      '_id' => NULL,

      'bonusAmount' => ['$sum' => ['$cond' => [
        ['$eq' => ['$__v', 0]],
        '$topUp',
        0
      ]]],

      'bonusCount' => ['$sum' => 1
      ],
    ],
  ],
]);

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
$usersWithDeposits = $transactionsCollection->distinct('userid', ['type' => 'deposit']);
      $usersWithDeposits = array_map(function ($id) {
        return new MongoDB\BSON\ObjectId($id);
      }, $usersWithDeposits);
      $usersWithWithdraw = $transactionsCollection->distinct('userid', ['type' => 'withdrawal']);
      $usersWithWithdraw = array_map(function ($id) {
        return new MongoDB\BSON\ObjectId($id);
      }, $usersWithWithdraw);

      $bonusCustomers = $bonusCollection->distinct('userid');
      $bonusCustomers = array_map(function ($id) {
        return new MongoDB\BSON\ObjectId($id);
      }, $bonusCustomers);
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




$mongo = new MongoDB\Driver\Manager("mongodb+srv://nicheelee24:B0wrmtGcgtXKoXWN@cluster0.8yb8idj.mongodb.net/gms2024?retryWrites=true&w=majority&appName=Cluster0&serverSelectionTryOnce=false&serverSelectionTimeoutMS=30");

$filter = ['platform' => 'luckyagent'];
$options = [];
$query = new MongoDB\Driver\Query($filter, $options);
$rows = $mongo->executeQuery('gms2024.users', $query);
$usersArr = $rows->toArray();
$recsCount=count($usersArr);

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
$totalBonusArray=iterator_to_array($bonusSums);
$totalBonus=$totalBonusArray[0]->bonusCount;
      $totalBonusAmount=$totalBonusArray[0]->bonusAmount;

include 'layout/header.php';
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" id="dvcontent">
  <div class="content-header">
    <div class="container-fluid">

      <div class="row mb-2">
        <div class="col-sm-6">

          <div class="col-sm-3"></div>
          <h1>Summary Report</h1>
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
        <h3 class="card-title">Total Stats</h3>

        
      </div>
      <!-- /.card-header -->
      <div class="card-body p-0">
        <table class="table">
          <thead>
            <tr>
              <th style="width: 10px">Total Items</th>
             
              <th>Deposit (Times)</th>
              <th>Withdrawl (Times)</th>
              <th>Bonus (Times)</th>
              <th>Total Deposit</th>
              <th>Total Withdrawl</th>
              <th>Difference</th>
              <th>Total Bonus</th>
              <th>All Customers</th>
              <th>Customer Deposit</th>
              <th>Customer Withdrawl</th>
              <th>Bonus Customers</th>
              <th>Applied</th> 
              <th>First Deposit</th>
              <th>Total First Deposit</th>
             
            </tr>
          </thead>
          <tbody>
            <?php
            $cnt = 1;
            $totalItems=0;
            $totalDepositCount=0;
            $totalWithdrawCount=0;
            $totalBonusCount=0;
            $totalDepositAmnt=0;
            $totalWithdrawAmnt=0;
            $totalBonusAmnt=0;
            $totalProfit=0;
            $totalCustomers=$recsCount;
            $totalFirstTimeDeposit=0;
            $totalFirstDepositCount=0;
            foreach ($userStats as $usr) {
                $cnt++;
                $totalDepositCount += $usr["TotalDepositTimes"];
                $totalWithdrawCount += $usr["TotalWithdrawTimes"];
                $totalBonusCount=$totalBonus;
                $totalDepositAmnt += $usr["TotalDepositAmount"];
                $totalWithdrawAmnt += $usr["TotalWithdrawAmount"];
                $totalBonusAmnt=$totalBonusAmount;
                $totalProfit += $usr["Profit"];
                $totalFirstTimeDeposit +=$usr["FirstTimeDepositedAmount"];
                
                if($usr["FirstTimeDepositedAmount"]!='N/A')
                {
                    $totalFirstDepositCount +=1;
                }
                

            }
            $totalItems=$totalDepositCount+$totalWithdrawCount+$totalBonusCount;
              ?>
              <tr>
                <td><?php echo $totalItems; ?></td>
               
                <td><?php echo $totalDepositCount; ?></td>
                <td><?php echo $totalWithdrawCount; ?></td>
                <td><?php echo $totalBonusCount; ?></td>
                <td><?php echo $totalDepositAmnt; ?></td>

                <td>
                <?php echo $totalWithdrawAmnt; ?>




                </td>
               
                 <td><?php echo $totalProfit; ?></td>
                 <td ><?php echo  $totalBonusAmnt; ?></td>
                 <td><?php echo $totalCustomers; ?></td>
                 <td><?php echo count($usersWithDeposits); ?></td>
                 <td><?php echo count($usersWithWithdraw); ?></td>
                 <td><?php echo count($bonusCustomers); ?></td>
                 <td><?php echo $totalCustomers; ?></td>
                 <td><?php echo $totalFirstDepositCount; ?></td>
                 <td><?php echo $totalFirstTimeDeposit; ?></td>
                 
                
                
              </tr>
              <?php
              
            

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
