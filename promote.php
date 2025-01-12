<?php
session_start();
require 'vendor/autoload.php';
$dotenv = new Symfony\Component\Dotenv\Dotenv();
$dotenv->load(__DIR__ . '/.env');
$agentid = '';
$utype = '';
$recsCount = 0;

if (isset($_SESSION['agent'])) {
    $agentid = $_SESSION['agent'];

}
if ($agentid == 'master') {
    $agentid = "";
}
if (isset($_SESSION["utype"])) {
    $utype = $_SESSION["utype"];
}
//echo $_SESSION['agent'];
//die('');
$db = $_ENV['db'] ?? '';

$mongo = new MongoDB\Driver\Manager("mongodb+srv://nicheelee24:IEX63RWtZZ5CSSuD@serverlessinstance0.uesvbfh.mongodb.net/?retryWrites=true&w=majority&appName=ServerlessInstance0");
if ($utype == "EMPLOYEE" || $utype == "SBGT") {
    $filter = ['agentname' => 'luckyagent'];
} else {
    $filter = [];
}
$options = ['sort' => ['_id' => -1]];
$query = new MongoDB\Driver\Query($filter, $options);
$rows = $mongo->executeQuery($db . '.promotions', $query);
$agntArr = $rows->toArray();
$recsCount = count($agntArr);
//print_r($agntArr);
//echo count($agntArr);
//die('');;
include 'layout/header.php';
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">

            <div class="row mb-2">
                <div class="col-sm-6">

                    <div class="col-sm-3"></div>
                    <h1>Manage Promotion</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Promote</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <div class="col-md-12">
        <div class="card">





            <div class="card-header">
                <h3 class="card-title">All Promotions</h3>
                <div class="card-tools">
                    <a class="btn btn-primary" href="create-promotion.php" <?php if (isset($_SESSION["access"])) {
                        if (!in_array('createPromo', $_SESSION["access"])) { ?> style="display:none" <?php }
                    } ?>
                        role="button">Create New</a>
                </div>

            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Bonus Pic</th>
                            <th>Description</th>
                            <th>Promotion Title</th>
                            <th>Deposit</th>
                            <th>Bonus</th>
                            <th>Conditions</th>
                            <th>Expiry</th>
                            <th>Is Active</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cnt = 1;
                        foreach ($agntArr as $prom) {
                            ?>
                            <tr>
                                <td><?php echo $cnt; ?></td>
                                <td><img src="/ama-bundai/uploads/<?php echo $prom->photo; ?>" width="250px"
                                        height="150px" />

                                </td>
                                <td><?php
                                $string_array = explode(",", $prom->details);


                                for ($i = 0; $i < sizeof($string_array); $i++) {
                                    echo $string_array[$i].'<br/>';
                                } ?></td>
                                <td><?php echo $prom->title; ?></td>




                                <td>



                                    <?php echo $prom->depositAmnt . ' THB' ?>

                                </td>
                                <td>


                                    <?php

                                    if ($prom->bonusCategory == 'percent') {
                                        echo $prom->percentBonus . ' % (Max. ' . $prom->highestPercent . '%)';
                                    } else {
                                        echo $prom->bonusAmnt . ' THB';
                                    }
                                    ?>



                                </td>
                                <td>


                                    <?php
                                    foreach ($prom->permissions as $p) {

                                        if ($p == 'turnover') {
                                            echo '<img src=dist/img/active.png style=height:18px;width:18px />' . $p . ' (' . $prom->turnover . '),<br/>';
                                        } else {
                                            echo '<img src=dist/img/active.png style=height:18px;width:18px />' . $p . ',<br/>';
                                        }

                                    }


                                    ?>


                                </td>
                                <td> <?php echo $prom->expDate; ?></td>
                                <td> <?php echo $prom->status; ?></td>

                                <td><a href="create-promotion.php?eid=<?php echo $prom->_id ?>"
                                        class="btn btn-info" <?php if (isset($_SESSION["access"])) {
                                    if (!in_array('promoUpdate', $_SESSION["access"])) { ?> style="display:none" <?php }
                                } ?> >Edit</a><a
                                        href="controllers/api.php?flag=delPromo&pid=<?php echo $prom->_id ?>"
                                        class="btn btn-danger" <?php if (isset($_SESSION["access"])) {
                                    if (!in_array('promoUpdate', $_SESSION["access"])) { ?> style="display:none" <?php }
                                } ?>>Delete</a></td>
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
    //Date range picker
    $('#reservation').daterangepicker();
</script>