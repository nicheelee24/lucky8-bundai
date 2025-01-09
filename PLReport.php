<?php
session_start();
require 'vendor/autoload.php';
$dotenv = new Symfony\Component\Dotenv\Dotenv();
$dotenv->load(__DIR__ . '/.env');
$mongourl = $_ENV['mongodburl'] ?? '';
$db = $_ENV['db'] ?? '';
$client = new MongoDB\Client($mongourl);
$db = $client->gms2024;
$filter = [];
$agentid = '';
$uid = '';
$prefix = '';
if (isset($_SESSION['agent'])) //master or other
{
  $agentid = $_SESSION['agent'];
}
if (isset($_SESSION['uid'])) //agent/user name
{
  $uid = $_SESSION['uid'];
}
if (isset($_SESSION['prefix'])) {
  $prefix = $_SESSION['prefix'];
}
echo $agentid;
echo ".." . $uid;
echo ".." . $prefix;
//die("");
$options = ['sort' => ['betTime' => -1]];
//$query = new MongoDB\Driver\Query($filter,$options);
//$rows = $mongo->executeQuery($db.'.bets',$query);
//$betsArr = $rows->toArray();
$sum = 0;
$platform = '';
$betcnt = 0;
$turnover = 0;
$winAmnt = 0;
$lossAmnt = 0;
$currency = '';

$overallTotalBetsCount = 0;
$overallTotalBetAmount = 0;
$overallTotalWinAmnt = 0;
$overallAgentWinLoss = 0;
$overallMasterAgentWinLoss = 0;
$overallCompanyWinLoss = 0;
$overallFranchiseWinLoss = 0;
$totalWinAmount = 0;
$totalLossAmount = 0;
$betsCollection = $db->bets;

if ($_SESSION["prefix"] != 'ADMIN') {
  $betsSums = $betsCollection->aggregate([
    [

      '$group' => [
        '_id' => '$userId',
        'userid' => ['$first' => '$userId'],
        'currency' => ['$first' => '$currency'],

        'totalBetAmount' => [

          // '$sum' => [
          //     '$cond' => [
          //         ['$eq' => ['$type', 'deposit']],
          //         '$payAmount',
          //         0
          //     ]
          // ]

          '$sum' => [
            '$cond' => [
              [
                '$and' => [
                  ['$eq' => ['$agentId', $uid]],
                  ['$eq' => ['$action', 'betNSettle']],
                ]
              ],


              '$betAmount',
              0
            ]
          ]
        ],


        'betsCount' => [
          '$sum' => [
            '$cond' => [
              [
                '$and' => [
                  ['$eq' => ['$agentId', $uid]],
                  ['$eq' => ['$action', 'betNSettle']],
                ]
              ],
              1,
              0
            ]
          ]
        ],

        'totalWinAmount' => [

          '$sum' => [
            '$cond' => [
              [
                '$and' => [
                  ['$eq' => ['$agentId', $uid]],
                  ['$eq' => ['$action', 'betNSettle']],
                ]
              ],


              '$winAmount',
              0
            ]
          ]




        ],




      ]
    ],


  ]);
} else {
  $betsSums = $betsCollection->aggregate([
    [

      '$group' => [
        '_id' => '$userId',
        'userid' => ['$first' => '$userId'],
        'currency' => ['$first' => '$currency'],

        'totalBetAmount' => [

          // '$sum' => [
          //     '$cond' => [
          //         ['$eq' => ['$type', 'deposit']],
          //         '$payAmount',
          //         0
          //     ]
          // ]

          '$sum' => [
            '$cond' => [

              ['$eq' => ['$action', 'betNSettle']],




              '$betAmount',
              0
            ]
          ]
        ],


        'betsCount' => [
          '$sum' => [
            '$cond' => [

              ['$eq' => ['$action', 'betNSettle']],


              1,
              0
            ]
          ]
        ],

        'totalWinAmount' => [

          '$sum' => [
            '$cond' => [

              ['$eq' => ['$action', 'betNSettle']],



              '$winAmount',
              0
            ]
          ]




        ],




      ]
    ],
    // [
    //   '$match'=> [
    //   'agentId'=> $uid
    // ]
    // ],

  ]);
}

$betSumsArray = iterator_to_array($betsSums);
//echo count($betSumsArray);
//die("..");


include 'layout/header.php';
?>
<!-- Content Wrapper. Contains page content -->
<style>
  .lightbl {
    background: #EBF5FB;
    text-align: right;
  }

  .lightgrn {
    background: #FBEEE6;
    text-align: right;
  }

  .lightorng {
    background: #E9F7EF;
    text-align: right;
  }

  .lightpurpl {
    background: #EAECEE;
    text-align: right;
  }
</style>
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">

      <div class="row mb-2">
        <div class="col-sm-6">

          <div class="col-sm-3"></div>
          <h1>Win / Loss Report</h1>
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
      <div class="card-header">


        <br />
        <form method="post" action="PLReport.php">
          <div class="row">

            <div class="form-group" placeholder="Select">
              <label>Period</label>
              <select class="custom-select" id="period">
                <option value="0">Any</option>
                <option value="Today">Today</option>
                <option value="Yesterday">Yesterday</option>
                <option value="7">7 Days</option>
                <option value="1">This Month</option>
                <option value="6">6 Months</option>
              </select>
            </div>
            <div class="form-group">
              <label>Date Time</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="far fa-clock"></i></span>
                </div>
                <input type="text" class="form-control float-right" id="reservationtime">
              </div>
            </div>
            <div class="form-group" style="display:none">
              <label>Timezone</label>
              <input type="text" class="form-control" placeholder="Timezone">
            </div>
            <div class="form-group" style="display:none">
              <label>Platform</label>
              <input type="text" class="form-control" placeholder="Platform">
            </div>
            <div class="form-group" style="display:none">
              <label>Type</label>
              <select class="custom-select">
                <option value="-1">Select Game / Other Type</option>
                <option value="All">All</option>
                <option value="EGAME">EGAME</option>
                <option value="ESPORT">ESPORT</option>
                <option value="FISH">FISH</option>
                <option value="LIVE">LIVE</option>
                <option value="LOTTO">LOTTO</option>
                <option value="SLOT">SLOT</option>
                <option value="TABLE">TABLE</option>
                <option value="VIRTUAL">VIRTUAL</option>
                <option value="DEALER_TIPPING">Other - DEALER_TIPPING</option>
                <option value="PROMOTION">Other - PROMOTION</option>
                <option value="STREAMER_TIPPING">Other - STREAMER_TIPPING</option>
              </select>
            </div>

            <div class="form-group" style="display:none">
              <label>Location</label>
              <input type="text" class="form-control" placeholder="Location">
            </div>
            <div class="form-group" placeholder="Select">
              <label>Currency</label>
              <select class="custom-select">

                <option value="CNY">THB</option>


              </select>
            </div>
            <div class="form-group">

              <button type="button" style="margin-top:32px;margin-left:10px"
                class="btn btn-block btn-warning">Filter</button>
            </div>

          </div>
        </form>
        <h3 class="card-title">Win / Loss Stats</h3>
      </div>
      <!-- /.card-header -->
      <div class="card-body p-0" style="overflow-x:scroll">

        <table class="table  table-bordered" style="width:100%;overflow:scroll">
          <thead>
            <tr>
              <th style="width: 10px">#</th>
              <th>User ID</th>
              <th>Currency</th>
              <th>Bet Amount</th>
              <th>Bet Count</th>
              <th>Gross Commission</th>
              <?php
              // echo $_SESSION['agent'];
              if ($_SESSION['agent'] != 'master' || $_SESSION['agent'] == 'master') {

                ?>

                <th rows="2" class="lightbl">Member W/L</th>
                <th rows="2" class="lightbl">Member Com.</th>


                <th class="lightgrn">Agent Win/Loss</th>
                <th class="lightgrn">Agent Com.</th>
                <?php
                if ($_SESSION['agent'] == 'master' || $_SESSION["prefix"] == 'ADMIN') {

                  ?>
                  <th class="lightorng">Master Agent Win/Loss</th>
                  <th class="lightorng">Master Agent Com.</th>
                  <?php
                }
                ?>
                <?php
                if ($_SESSION["prefix"] == 'ADMIN') {

                  ?>
                  <th class="lightpurpl">Master Franchise Win/Loss</th>
                  <th class="lightpurpl">Master Franchise Com.</th>
                <?php } ?>

                <th>Company Win/Loss</th>
                <th>Company Com.</th>

                <?php

              }
              ?>




            </tr>
          </thead>
          <tbody>
            <?php
            // echo $_SESSION['agent'];
            //echo "type..".$_SESSION["utype"];
            foreach ($betSumsArray as $bet) {
              if ($bet->betsCount) {
                $totalWinAmount = $bet->totalWinAmount - $bet->totalBetAmount;
                $overallTotalBetsCount += $bet->betsCount;
                $overallTotalBetAmount += $bet->totalBetAmount;


                ?>
                <tr>
                  <td></td>
                  <td><?php echo $bet->userid ?></td>
                  <td><?php echo $bet->currency ?></td>
                  <td style="text-align:right;"><?php echo number_format($bet->totalBetAmount, 2); ?></td>
                  <td style="text-align:right;"><?php echo $bet->betsCount; ?></td>
                  <td style="text-align:right;"><?php echo '0.00'; ?></td>

                  <?php
                  if ($_SESSION['agent'] != 'master' || $_SESSION['agent'] == 'master') {

                    ?>
                    <td class="lightbl">

                      <?php
                      $overallTotalWinAmnt += $totalWinAmount;
                      if ($totalWinAmount > 0) {
                        echo '<span style=color:green;>' . number_format($totalWinAmount, 2) . '</span>';
                      } else {
                        echo '<span style=color:red>' . number_format($totalWinAmount, 2) . '</span>';
                      }
                      ?>
                    </td>


                    <td class="lightbl"> 0.00
                    </td>


                    <td class="lightgrn"><?php
                    $AgtWinLoss = $totalWinAmount * .90;


                    if ($totalWinAmount > 0) {


                      $AgtWinLoss = -$AgtWinLoss;
                      $overallAgentWinLoss += $AgtWinLoss;
                      ?>

                        <span style="color:red;"><?php echo number_format($AgtWinLoss, 2); ?></span>
                        <?php
                    } else {

                      $AgtWinLoss = 0 - $AgtWinLoss;
                      $overallAgentWinLoss += $AgtWinLoss;
                      ?>
                        <span style="color:green;"><?php echo number_format($AgtWinLoss, 2); ?></span>
                        <?php


                    }



                    ?>
                    </td>
                    <td class="lightgrn">0.00</td>


                    <?php
                    if ($_SESSION['agent'] == 'master' || $_SESSION["prefix"] == 'ADMIN') {

                      ?>
                      <td class="lightorng">
                        <?php
                        $masAgWinLoss = $totalWinAmount * .90;


                        if ($totalWinAmount > 0) {


                          $masAgWinLoss = -$masAgWinLoss;
                          $overallMasterAgentWinLoss += $masAgWinLoss;
                          ?>

                          <span style="color:red;"><?php echo number_format($masAgWinLoss, 2); ?></span>
                          <?php
                        } else {

                          $masAgWinLoss = 0 - $masAgWinLoss;
                          $overallMasterAgentWinLoss += $masAgWinLoss;
                          ?>
                          <span style="color:green;"><?php echo number_format($masAgWinLoss, 2); ?></span>
                          <?php


                        }



                        ?>


                      </td>
                      <td class="lightorng">0.00</td>
                    <?php } ?>

                    <?php
                    if ($_SESSION["prefix"] == 'ADMIN') {
                      ?>
                      <td class="lightpurpl"><?php
                      $franchiseWinLoss = $totalWinAmount * .08;
                      $overallFranchiseWinLoss += $franchiseWinLoss;
                      if ($totalWinAmount > 0) {

                        $franchiseWinLoss = -$franchiseWinLoss;
                        $overallFranchiseWinLoss = -$overallFranchiseWinLoss;

                        echo '<span style=color:red;>' . number_format($franchiseWinLoss, 2) . '</span>';
                      } else {

                        $franchiseWinLoss = 0 - $franchiseWinLoss;
                        $overallFranchiseWinLoss = 0 - $overallFranchiseWinLoss;

                        echo '<span style=color:green;>' . number_format($franchiseWinLoss, 2) . '</span>';
                      }
                      ?>
                      </td>
                      <td class="lightpurpl">0.00</td>
                    <?php } ?>


                    <td style="text-align:right;"> <?php
                    $compWinLoss = $totalWinAmount * .10;

                    if ($totalWinAmount > 0) {

                      $compWinLoss = -$compWinLoss;
                      $overallCompanyWinLoss += $compWinLoss;

                      ?>

                        <span style="color:red;"><?php echo number_format($compWinLoss, 2); ?></span>
                        <?php
                    } else {


                      $compWinLoss = 0 - $compWinLoss;
                      $overallCompanyWinLoss += $compWinLoss;

                      ?>
                        <span style="color:green;"><?php echo number_format($compWinLoss, 2); ?></span>
                        <?php


                    }



                    ?>
                    </td>
                    <td style="text-align:right;">0.00</td>
                    <?php
                  }
                  ?>

                </tr>
              <?php }
            } ?>
            <thead>
              <tr>
                <th style="width: 10px"></th>
                <th>Total</th>
                <th>THB</th>
                <th style="text-align:right;"><?php echo number_format($overallTotalBetAmount, 2); ?></th>
                <th style="text-align:right;"><?php echo $overallTotalBetsCount; ?></th>

                <th style="text-align:right;">0.00</th>
                <?php
                if ($_SESSION['agent'] != 'master' || $_SESSION['agent'] == 'master') {

                  ?>
                  <th class="lightbl" <?php if ($overallTotalWinAmnt < 0) ?> style="color:red">
                    <?php echo number_format($overallTotalWinAmnt, 2); ?> </th>


                  <th class="lightbl">0.00</th>


                  <th class="lightgrn" <?php if ($overallAgentWinLoss < 0) { ?> style="color:red" <?php } ?>>
                    <?php echo number_format($overallAgentWinLoss, 2) ?></th>

                  <th class="lightgrn">0.00</th>

                  <?php
                  if ($_SESSION['agent'] == 'master' || $_SESSION["prefix"] == 'ADMIN') {

                    ?>
                    <th class="lightorng" <?php if ($overallMasterAgentWinLoss < 0) ?> style="color:red">
                      <?php echo number_format($overallMasterAgentWinLoss, 2) ?></th>

                    <th class="lightorng">0.00</th>
                  <?php } ?>
                  <?php
                  if ($_SESSION['agent'] != 'master' && $_SESSION["prefix"] != 'SGBT' && $_SESSION["prefix"] == 'ADMIN') {
                    ?>
                    <th class="lightpurpl" <?php if ($overallFranchiseWinLoss < 0) ?> style="color:red;">
                      <?php echo number_format($overallFranchiseWinLoss, 2) ?></th>
                    <th class="lightpurpl">0.00</th>
                  <?php } ?>



                  <th <?php if ($overallCompanyWinLoss < 0) { ?> style="font-weight:bold;text-align:right;color:red" <?php } ?>><?php echo number_format($overallCompanyWinLoss, 2); ?></th>
                  <th style="text-align:right;">0.00</th>
                <?php } ?>

              </tr>
            </thead>

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
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<?php
include 'layout/footer.php';
?>