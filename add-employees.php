<?php
session_start();
//die("");
require_once "vendor/autoload.php";
include 'layout/header.php';

$uid = "";
if (isset($_GET["eid"])) {
    $uid = $_GET["eid"];
}

$mongo = new MongoDB\Driver\Manager("mongodb+srv://nicheelee24:IEX63RWtZZ5CSSuD@serverlessinstance0.uesvbfh.mongodb.net/?retryWrites=true&w=majority&appName=ServerlessInstance0");
$filter = ['userid' => $uid];
$options = [];
$query = new MongoDB\Driver\Query($filter, $options);
$rows = $mongo->executeQuery('gms2024.agents', $query);
$agntsArr = $rows->toArray();
//$cnt = count($agntsArr);
//die($uid);
//$cnt = count($agntsArr);
//echo $cnt;
//echo "hi";
// use Google\Authenticator\GoogleAuthenticator;
// use Google\Authenticator\GoogleQrUrl;

// $googleAuthenticator = new GoogleAuthenticator();
// $secret = $googleAuthenticator->generateSecret();
// $qrCodeUrl = GoogleQrUrl::generate('employeename', $secret, 'Backoffice');
?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <div class="col-sm-3"></div>
                    <h1>Create/Update Employee</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Add Employee</li><?php echo $uid; ?>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>

    <!-- Main content -->
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-6">


                <form method="post" <?php if ($uid == "") { ?> action="controllers/api.php?flag=newEmp" <?php } else { ?>
                        action="controllers/api.php?flag=updEmp&id=<?php echo $uid; ?>" <?php } ?>>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Employee Name</label>
                                    <input type="text" name="name" class="form-control" required value="<?php if ($uid != "") {
                                        echo $agntsArr[0]->agentname;
                                    } ?>" placeholder="Full Name">
                                </div>

                                <div class="form-group">
                                    <label>Employee Phone</label>
                                    <input type="text" name="phone" class="form-control" required value="<?php if ($uid != "") {
                                        echo $agntsArr[0]->phone;
                                    } ?>" placeholder="Phone">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" name="uname" class="form-control" required value="<?php if ($uid != "") {
                                        echo $agntsArr[0]->userid;
                                    } ?>" placeholder="Username">
                                </div>

                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" name="pass" class="form-control" value="<?php if ($uid != "") {
                                        echo $agntsArr[0]->pwd;
                                    } ?>" required placeholder="Password">
                                </div>

                                <div class="form-group">
                                    <label>Manual Deposit Limit(THB)</label>
                                    <input type="text" name="manualLimit" class="form-control" required value="<?php if ($uid != "") {
                                        echo $agntsArr[0]->manualLimit;
                                    } ?>" placeholder="Manual Deposit Limit(THB)">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">

                                <div class="form-group">
                                    <label>Shift Time</label>
                                    <select class="custom-select" name="shift" id="shift">
                                        <?php
                                        if ($agntsArr[0]->shift == "morning") {

                                            ?>
                                            <option value="morning" selected>Morning Shift - 8AM to 4PM</option>
                                            <?php
                                        } else {
                                            ?>
                                            <option value="morning">Morning Shift - 8AM to 4PM</option>
                                        <?php }

                                        ?>
                                        <?php
                                        if ($agntsArr[0]->shift == "evening") {

                                            ?>
                                            <option value="evening" selected>Evening Shift - 4PM to 12AM</option>
                                            <?php
                                        } else {
                                            ?>
                                            <option value="evening">Evening Shift - 4PM to 12AM</option>
                                        <?php }

                                        ?>
                                        <?php
                                        if ($agntsArr[0]->shift == "night") {

                                            ?>
                                            <option value="night" selected>Night Shift - 12AM to 8AM</option>
                                            <?php
                                        } else {
                                            ?>
                                            <option value="night">Night Shift - 12AM to 8AM</option>
                                        <?php }

                                        ?>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">

                                <div class="form-group">
                                    <label>Permissions</label>
                                    <div class="form-check">
                                        <input class="form-check-input all" onclick="checkAll()" type="checkbox"
                                            name="permissions[]" <?php if ($uid != "") if (in_array("All", $agntsArr[0]->permissions)) { ?> checked <?php } ?> value="All">
                                        <label class="form-check-label">All Permissions</label>
                                    </div>

                                    <div class="form-check">

                                        <label class="form-label">Manage Employees</label>


                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" onclick="checkUncheck(this)" type="checkbox"
                                            name="permissions[]" <?php if ($uid != "") if (in_array("viewEmp", $agntsArr[0]->permissions)) { ?> checked <?php } ?> value="viewEmp">
                                        <label class="form-check-label">View Employees</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" onclick="checkUncheck(this)"
                                            name="permissions[]" type="checkbox" <?php if ($uid != "") if (in_array("updEmp", $agntsArr[0]->permissions)) { ?> checked <?php } ?>
                                            value="updEmp">
                                        <label class="form-check-label">Update Employees</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" onclick="checkUncheck(this)"
                                            name="permissions[]" type="checkbox" <?php if ($uid != "") if (in_array("createEmp", $agntsArr[0]->permissions)) { ?> checked <?php } ?>
                                            value="createEmp">
                                        <label class="form-check-label">Create Employees</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">

                                        <label for="allMembers" class="form-label">Manage Members</label>


                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" onclick="checkUncheck(this)" type="checkbox"
                                            name="permissions[]" value="memView" <?php if ($uid != "") if (in_array("memView", $agntsArr[0]->permissions)) { ?> checked <?php } ?>>
                                        <label class="form-check-label">View Member</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" onclick="checkUncheck(this)" type="checkbox"
                                            name="permissions[]" value="memUpdate" <?php if ($uid != "") if (in_array("memUpdate", $agntsArr[0]->permissions)) { ?> checked <?php } ?>>
                                        <label class="form-check-label">Update Member</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" onclick="checkUncheck(this)"
                                            name="permissions[]" type="checkbox" <?php if ($uid != "") if (in_array("createMem", $agntsArr[0]->permissions)) { ?> checked <?php } ?>
                                            value="createMem">
                                        <label class="form-check-label">Create Member</label>
                                    </div>





                                    <div class="custom-control custom-checkbox">

                                        <label for="allReports" class="form-label">Reports</label>


                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" onclick="checkUncheck(this)" type="checkbox"
                                            name="permissions[]" value="bonusReport" <?php if ($uid != "") if (in_array("bonusReport", $agntsArr[0]->permissions)) { ?> checked <?php } ?>>
                                        <label class="form-check-label">Bonus Report</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" onclick="checkUncheck(this)" type="checkbox"
                                            name="permissions[]" value="history" <?php if ($uid != "") if (in_array("history", $agntsArr[0]->permissions)) { ?> checked <?php } ?>>
                                        <label for="history" class="form-check-label">History</label>


                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" onclick="checkUncheck(this)" type="checkbox"
                                            name="permissions[]" value="summaryReport" <?php if ($uid != "") if (in_array("summaryReport", $agntsArr[0]->permissions)) { ?> checked <?php } ?>>
                                        <label class="form-check-label">Summary Report</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" onclick="checkUncheck(this)" type="checkbox"
                                            name="permissions[]" value="winLossReport" <?php if ($uid != "") if (in_array("winLossReport", $agntsArr[0]->permissions)) { ?> checked <?php } ?>>
                                        <label class="form-check-label">Win/Loss Report</label>
                                    </div>

                                    <div class="custom-control custom-checkbox">

                                        <label for="allPromote" class="form-label">Manage Promotion</label>


                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" onclick="checkUncheck(this)" type="checkbox"
                                            name="permissions[]" value="promoView" <?php if ($uid != "") if (in_array("promoView", $agntsArr[0]->permissions)) { ?> checked <?php } ?>>
                                        <label class="form-check-label">View Promotion</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" onclick="checkUncheck(this)" type="checkbox"
                                            name="permissions[]" value="promoUpdate" <?php if ($uid != "") if (in_array("promoUpdate", $agntsArr[0]->permissions)) { ?> checked <?php } ?>>
                                        <label class="form-check-label">Update Promotion</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" onclick="checkUncheck(this)"
                                            name="permissions[]" type="checkbox" <?php if ($uid != "") if (in_array("createPromo", $agntsArr[0]->permissions)) { ?> checked <?php } ?>
                                            value="createPromo">
                                        <label class="form-check-label">Create Promotion</label>
                                    </div>

                                    <div class="custom-control custom-checkbox">

                                        <label for="payment" class="form-label">Manual Payment</label>


                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" onclick="checkUncheck(this)" type="checkbox"
                                            name="permissions[]" value="deposit" <?php if ($uid != "") if (in_array("deposit", $agntsArr[0]->permissions)) { ?> checked <?php } ?>>
                                        <label for="deposit" class="form-check-label">Deposit/Withdraw</label>


                                    </div>
                                    <!-- <div class="form-check">
                                        <input class="form-check-input" onclick="checkUncheck(this)" type="checkbox" name="permissions[]" value="withdraw" <?php if ($uid != "") if (in_array("withdraw", $agntsArr[0]->permissions)) { ?> checked <?php } ?>
                                            >
                                        <label for="withdraw" class="form-check-label">Withdraw</label>


                                    </div> -->
                                </div>
                            </div>

                        </div>













                        <!-- /.card-body -->

                        <div class="card-footer">
                            <?php if ($uid != "") { ?>
                                <button type="submit" class="btn btn-primary" <?php if (isset($_SESSION["access"])) {
                                    if (!in_array('updEmp', $_SESSION["access"])) { ?> style="display:none" <?php }
                                } ?>>Update</button>
                            <?php } else { ?>

                                <button type="submit" <?php if (isset($_SESSION["access"])) {
                                    if (!in_array('createEmp', $_SESSION["access"])) { ?> style="display:none" <?php }
                                } ?>
                                    class="btn btn-primary">Submit</button>
                            <?php } ?>
                        </div>
                </form>
            </div>
        </div>
        <!-- /.col -->

        <!-- /.col -->
    </div>


    <!-- /.row -->
</div><!-- /.container-fluid -->

<!-- /.content -->
</div>
<script>
    function checkAll() {
        var ele = document.getElementsByName('permissions[]');
        //alert(ele);  
        for (var i = 0; i < ele.length; i++) {
            if (ele[i].checked == false)
                ele[i].checked = true;


        }
    }

    function checkUncheck(ele) {
        // alert(ele.checked);
        if (ele.checked == false) {
            var elem = document.querySelector('.all');
            // alert(elem.value);
            elem.checked = false;

        }

    }
</script>

<?php
include 'layout/footer.php';
?>