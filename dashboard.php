<?php
session_start();
require 'vendor/autoload.php';
//die("..");
include 'layout/header.php';


?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h2> Dashboard</h2>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <?php

  if (isset($_SESSION["utype"])) {

    if ($_SESSION["utype"] != "PLAYER") {

      $dotenv = new Symfony\Component\Dotenv\Dotenv();
      $dotenv->load(__DIR__ . '/.env');
      $mongourl = $_ENV['mongodburl'] ?? '';
      $db = $_ENV['db'] ?? '';
      $client = new MongoDB\Client($mongourl);
      $db = $client->gms2024;
      $today = new \DateTime();
      $startOfDay = $today->setTime(0, 0)->format('Y-m-d\TH:i:s'); // Start of today
      $endOfDay = $today->setTime(23, 59, 59)->format('Y-m-d\TH:i:s'); // End of today
      //$usersCollection = $db->transactions;
      // // Convert to MongoDB UTCDateTime
      $startOfDayUtc = new MongoDB\BSON\UTCDateTime(strtotime($startOfDay) * 1000);
      $endOfDayUtc = new MongoDB\BSON\UTCDateTime(strtotime($endOfDay) * 1000);

      $usersCollection = $db->users;
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

      $registerSums = $usersCollection->aggregate([

        [

          '$group' => [
            '_id' => NULL,
            'phone' => ['$first' => '$phone'],
            'name' => ['$first' => '$name'],

            'registerCount' => ['$sum' => ['$cond' => [
              ['$eq' => ['$platform', 'luckyama']],
              1,
              0
            ]]],
          ],
        ],
      ]);
      $transCollection = $db->transactions;
      $transSums = $transCollection->aggregate([
        [

          '$group' => [
            '_id' => NULL,


            'depositCount' => ['$sum' => ['$cond' => [
              [
                '$and' => [
                  ['$eq' => ['$platform', 'luckyama']],
                  ['$eq' => ['$type', 'deposit']]
                ]
              ],
              1,
              0
            ]]],
            'depositAmount' => ['$sum' => ['$cond' => [
              [
                '$and' => [
                  ['$eq' => ['$platform', 'luckyama']],
                  ['$eq' => ['$type', 'deposit']]
                ]
              ],
              '$payAmount',
              0
            ]]],
            'withdrawCount' => ['$sum' => ['$cond' => [
              [
                '$and' => [
                  ['$eq' => ['$platform', 'luckyama']],
                  ['$eq' => ['$type', 'withdrawal']]
                ]
              ],
              1,
              0
            ]]],
            'withdrawAmount' => ['$sum' => ['$cond' => [
              [
                '$and' => [
                  ['$eq' => ['$platform', 'luckyama']],
                  ['$eq' => ['$type', 'withdrawal']]
                ]
              ],
              '$payAmount',
              0
            ]]],

          ],
        ],
      ]);

      $pipelineTotalDepositedToday = [
        ['$match' => [
          'date' => ['$gte' => $startOfDayUtc, '$lte' => $endOfDayUtc],
          'platform' => 'luckyama',
          'type' => 'deposit'
        ]],
        ['$group' => [
          '_id' => null,
          'totalDeposited' => ['$sum' => 1]
        ]]
      ];

      $resultTotalDepositedToday = $transCollection->aggregate($pipelineTotalDepositedToday);
      $totalDepositedToday = $resultTotalDepositedToday->toArray()[0]->totalDeposited ?? 0;


      $valueSumsArray = iterator_to_array($registerSums);
      $transSumsArray = iterator_to_array($transSums);
      $totalBonusArray=iterator_to_array($bonusSums);

      $totalRegister = $valueSumsArray[0]->registerCount;
      $totalBonus=$totalBonusArray[0]->bonusCount;
      $totalBonusAmount=$totalBonusArray[0]->bonusAmount;
      $totalDeposit = $transSumsArray[0]->depositCount;
      $totalDepositAmount = $transSumsArray[0]->depositAmount;

      $totalWithdraw = $transSumsArray[0]->withdrawCount;
      $totalWithdrawAmount = $transSumsArray[0]->withdrawAmount;

      $difference = $totalDepositAmount - $totalWithdrawAmount;

      /****  Total registered users only those have not deposited any money  ****/



      // Find users who have not made any deposits
      $usersWithDeposits = $transCollection->distinct('userid', ['type' => 'deposit']);
      $usersWithDeposits = array_map(function ($id) {
        return new MongoDB\BSON\ObjectId($id);
      }, $usersWithDeposits);

      $noDepositUsers = $usersCollection->countDocuments([
        '_id' => ['$nin' => $usersWithDeposits]
      ]);

     

      //echo count($valueSumsArray);
      //die("..");
      //foreach($valueSumsArray as $value)
      //{
      //  echo  $value->registerCount;
      // }
  ?>
      <section class="content">

        <div class="container-fluid">

          <!-- Small boxes (Stat box) -->
          <div class="row">
            <div class="col-lg-2 col-6">
              <!-- small box -->
              <div class="small-box bg-info">
                <div class="inner">
                  <h3 id="depositAmt"><?php echo $totalDepositAmount ?></h3>

                  <p> Deposit</p>
                  <p><?php echo $totalDeposit ?> Times</p>
                </div>
                <div class="icon">
                  <i class="fa fa-person-add"></i>
                </div>

              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-2 col-6">
              <!-- small box -->
              <div class="small-box bg-info">
                <div class="inner" style="background:#fd7e14">
                  <h3 id="withdAmt"><?php echo $totalWithdrawAmount ?></h3>

                  <p>Withdraw</p>
                  <p><?php echo $totalWithdraw ?> Times</p>
                </div>
                <div class="icon">
                  <i class="ion ion-plus-square"></i>
                </div>

              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-2 col-6">
              <!-- small box -->
              <div class="small-box bg-info">
                <div class="inner">
                  <h3><?php echo $totalBonusAmount ?></h3>

                  <p>Bonus</p>
                  <p><?php echo $totalBonus ?> Times </p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>

              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-2 col-6">
              <!-- small box -->
              <div class="small-box bg-info">
                <div class="inner" style="background:#ffc107">
                  <h3 id="diffAmt"><?php echo $difference; ?></h3>

                  <p>Difference </p>
                  <p></p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>

              </div>
            </div>
            <div class="col-lg-2 col-6" style="display:none">
              <!-- small box -->
              <div class="small-box bg-info">
                <div class="inner">
                  <h3>10</h3>

                  <p>Active Users </p>
                  <p></p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>

              </div>
            </div>
            <div class="col-lg-2 col-6">
              <!-- small box -->
              <div class="small-box bg-info">
                <div class="inner" style="background:#e83e8c">
                  <h3 id="totalDepositedToday"><?php echo count($usersWithDeposits) ?></h3>

                  <p>Total Deposit Customers </p>
                  <p></p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>

              </div>
            </div>
            <div class="col-lg-2 col-6">
              <!-- small box -->
              <div class="small-box bg-success">
                <div class="inner">
                  <h3 id="onlyRegistered"><?php echo $noDepositUsers ?></h3>

                  <p>Account Created </p>
                  <p></p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>

              </div>
            </div>

          </div>



          <h3> Summarize your overview with graphs</h3>
          <div class="row">
            <div class="form-group" placeholder="Select">

              <select class="custom-select" id="period">
              <option value="0">--</option>
                <option value="1">Today</option>
                <option value="7">7 Days</option>
                <option value="30">30 Days</option>
                <option value="6">6 Months</option>
                <option value="12">12 Months</option>
                <option value="5">5 Years</option>
              </select>
            </div>
            <div class="row">
              <div class="card card-danger" style="margin-left:20px">

                <div class="card-body">
                  <canvas id="moneyChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <div class="card card-danger" style="margin-left:20px">
              <!-- <div class="card-header">
                <h3 class="card-title">People</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div> -->
              <div class="card-body">
                <canvas id="peopleChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

          </div>
          <div class="row">
            <div class="card-header">
              <h3>Summary of Bank deposit and withdraw</h3>
              <div class="card-body">
                <table class="table">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Bank</th>
                      <th>Deposit</th>
                      <th>Withdraw</th>



                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $cnt = 1;
                    // foreach ($agntArr as $emp) {
                    ?>
                    <tr>
                      <td><?php echo $cnt; ?></td>
                      <td><?php

                          echo "Prompt Pay";
                          ?>

                      </td>
                      <td><?php echo $totalDepositAmount ?> (<?php echo $totalDeposit ?> Times)</td>


                      <td><?php
                          echo $totalWithdrawAmount; ?> (<?php echo $totalWithdraw ?> Times)</td>


                    </tr>
                    <?php
                    $cnt++;
                    //  }

                    ?>


                  </tbody>
                </table>
              </div>

            </div>
          </div>
        </div>
      <?php } else { ?>

        <section class="content">

          <div class="container-fluid">

            <!-- Small boxes (Stat box) -->
            <div class="row">
              <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                  <div class="inner">
                    <h3>0</h3>

                    <p> Total Games Played</p>
                  </div>
                  <div class="icon">
                    <i class="fa fa-person-add"></i>
                  </div>

                </div>
              </div>
              <!-- ./col -->
              <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-warning">
                  <div class="inner">
                    <h3>0</h3>

                    <p>Total Bets</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-plus-square"></i>
                  </div>

                </div>
              </div>
              <!-- ./col -->

              <!-- ./col -->
              <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                  <div class="inner">
                    <h3>0</h3>

                    <p>Total Win Amount</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                  </div>

                </div>
              </div>
              <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                  <div class="inner">
                    <h3>0</h3>

                    <p>Best Played </p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                  </div>

                </div>
              </div>





              <div class="row">
                <!-- Left col -->

                <!-- Custom tabs (Charts with tabs)-->

                <div class="card card-success">
                  <div class="card-header">
                    <h3 class="card-title">Graph Data</h3>

                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                      </button>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="chart">
                      <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                  </div>
                  <!-- /.card-body -->
                </div>


        </section>
        <!-- /.Left col -->
        <!-- right col (We are only adding the ID to make the widgets sortable)-->
        <section class="col-lg-9 connectedSortable">

          <!-- Map card -->

          <!-- /.card -->

          <!-- solid sales graph -->

          <!-- /.card -->

          <!-- Calendar -->

          <!-- /.card -->
        </section>
        <!-- right col -->
</div>


<?php
    }
  } ?>

<?php
include 'layout/footer.php';
?>
<script>
  var totalDeposit = $("#depositAmt").text(); //totalDepositedToday
  var totalWithdraw = $("#withdAmt").text();
  var totalDifference = $("#diffAmt").text();
  var onlyRegisteredUsers = $("#onlyRegistered").text();
  var totalDepositedUsersToday = $("#totalDepositedToday").text();
  var TotalAppliedToday = parseInt(onlyRegisteredUsers) + parseInt(totalDepositedUsersToday);
  var areaChartData = {
    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
    datasets: [{
        label: 'Volunteer',
        backgroundColor: 'rgba(60,141,188,0.9)',
        borderColor: 'rgba(60,141,188,0.8)',
        pointRadius: false,
        pointColor: '#3b8bba',
        pointStrokeColor: 'rgba(60,141,188,1)',
        pointHighlightFill: '#fff',
        pointHighlightStroke: 'rgba(60,141,188,1)',
        data: [28, 48, 40, 19, 86, 27, 90]
      },
      {
        label: 'First Time Deposit',
        backgroundColor: 'rgba(210, 214, 222, 1)',
        borderColor: 'rgba(210, 214, 222, 1)',
        pointRadius: false,
        pointColor: 'rgba(210, 214, 222, 1)',
        pointStrokeColor: '#c1c7d1',
        pointHighlightFill: '#fff',
        pointHighlightStroke: 'rgba(220,220,220,1)',
        data: [65, 59, 80, 81, 56, 55, 40]
      },
    ]
  }


  //-MONEY DONUT CHART -
  //-------------
  // Get context with jQuery - using jQuery's .get() method.
  var donutChartCanvas = $('#moneyChart').get(0).getContext('2d')
  var donutData = {
    labels: [
      'Deposit',
      'Withdraw',
      'Difference',

    ],
    datasets: [{
      data: [totalDeposit, totalWithdraw, totalDifference],
      backgroundColor: ['#00c0ef', '#f39c12', '#00a65a'],
    }]
  }
  var donutOptions = {
    maintainAspectRatio: false,
    responsive: true,
  }
  //Create pie or douhnut chart
  // You can switch between pie and douhnut using the method below.
  var donutChart = new Chart(donutChartCanvas, {
    type: 'doughnut',
    data: donutData,
    options: donutOptions
  })
  //-------------
  //- PEOPLE DONUT CHART -
  //-------------
  // Get context with jQuery - using jQuery's .get() method.
  var donutChartCanvas = $('#peopleChart').get(0).getContext('2d')
  var donutData = {
    labels: [
      'People Applied',
      'Applied Only',
      'Applied & Deposit',



    ],
    datasets: [{
      data: [TotalAppliedToday, onlyRegisteredUsers, totalDepositedUsersToday],
      backgroundColor: ['#cfcfcf', '#f39c12', '#00c0ef'],
    }]
  }
  var donutOptions = {
    maintainAspectRatio: false,
    responsive: true,
  }
  //Create pie or douhnut chart
  // You can switch between pie and douhnut using the method below.
  var donutChart = new Chart(donutChartCanvas, {
    type: 'doughnut',
    data: donutData,
    options: donutOptions
  })

  //- BAR CHART -
  //-------------
  var barChartCanvas = $('#barChart').get(0).getContext('2d')
  var barChartData = $.extend(true, {}, areaChartData)
  var temp0 = areaChartData.datasets[0]
  var temp1 = areaChartData.datasets[1]
  barChartData.datasets[0] = temp1
  barChartData.datasets[1] = temp0

  var barChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    datasetFill: false
  }

  var barChart = new Chart(barChartCanvas, {
    type: 'bar',
    data: barChartData,
    options: barChartOptions
  })
</script>