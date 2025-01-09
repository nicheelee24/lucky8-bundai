<?php
session_start();
require 'vendor/autoload.php';
$dotenv = new Symfony\Component\Dotenv\Dotenv();
$dotenv->load(__DIR__ . '/.env');
$agentid = '';
$utype='';
$recsCount = 0;

if (isset($_SESSION['agent'])) {
    $agentid = $_SESSION['agent'];

}
if ($agentid == 'master') {
    $agentid = "";
}
if(isset($_SESSION["utype"]))
{
    $utype=$_SESSION["utype"];
}
//echo $_SESSION['agent'];
//die('');
$db = $_ENV['db'] ?? '';

$mongo = new MongoDB\Driver\Manager("mongodb+srv://nicheelee24:B0wrmtGcgtXKoXWN@cluster0.8yb8idj.mongodb.net/gms2024?retryWrites=true&w=majority&serverSelectionTryOnce=false&serverSelectionTimeoutMS=30&appName=Cluster0");
if ($utype=="EMPLOYEE" || $utype=="SBGT") {
    $filter = ['platform' => 'luckyama','type'=>'EMPLOYEE', 'agentname'=> array('$ne' => $agentid)];
} else {
    $filter = ['type'=>'EMPLOYEE'];
}
$options = ['sort' => ['_id' => -1]];
$query = new MongoDB\Driver\Query($filter, $options);
$rows = $mongo->executeQuery($db . '.agents', $query);
$agntArr = $rows->toArray();
$recsCount = count($agntArr);
include 'layout/header.php';
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">

            <div class="row mb-2">
                <div class="col-sm-6">

                    <div class="col-sm-3"></div>
                    <h1>Manage Employees</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Manage Employees</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <div class="col-md-12">
        <div class="card">





            <div class="card-header">
                <h3 class="card-title">All Employees</h3>
                <div class="card-tools">
                    <a class="btn btn-primary" href="add-employees.php" <?php if(isset($_SESSION["access"])){ if(!in_array('createEmp',$_SESSION["access"])){ ?> style="display:none" <?php }} ?> role="button">Add Employee</a>
                </div>
                <div class="card-tools">
                    <!-- <a class="btn btn-primary" href="add-member.php" role="button">Add Member</a> -->
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Username</th>


                            <th>Status</th>
                            <th colspan="3" style=" width: 90px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cnt = 1;
                        foreach ($agntArr as $emp) {
                            ?>
                            <tr>
                                <td><?php echo $cnt; ?></td>
                                <td><?php

                                echo $emp->agentname;
                                ?>

                                </td>
                                <td><?php echo $emp->phone; ?></td>


                                <td><?php echo $emp->userid; ?></td>

                                <td style="color:green">

                                    <?php $sts = "InActive";
                                    if ($emp->__v == 1) {
                                        $sts = "Active";
                                    }

                                    echo $sts;
                                    ?>

                                </td>
                                <td>

                                    <a href="add-employees.php?eid=<?php echo $emp->userid;?>" class="btn btn-info">View</a>

                                    <!-- <button type="button" class="btn btn-danger" style="display:none">Delete</button> -->
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
    //Date range picker
    $('#reservation').daterangepicker();
</script>