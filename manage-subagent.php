<?php
session_start();
require 'vendor/autoload.php';
$dotenv = new Symfony\Component\Dotenv\Dotenv();
$dotenv->load(__DIR__ . '/.env');
$agentid = '';
$recsCount = 0;
if (isset($_SESSION["uid"])) {
  $agentid = $_SESSION["uid"];
}

$db = $_ENV['db'] ?? '';

$mongo = new MongoDB\Driver\Manager("mongodb+srv://nicheelee24:B0wrmtGcgtXKoXWN@cluster0.8yb8idj.mongodb.net/games2024?retryWrites=true&w=majority&appName=Cluster0");
if ($agentid != '') {
  $filter = ['parent' => $agentid];
} else {
  $filter = [];
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
          <h1>Manage Sub Agents</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Manage Sub Agents</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">All Sub Agents </h3>

        <div class="card-tools">
          <a class="btn btn-primary" href="add-subagent.php" role="button">Add Sub Agent</a>
        </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body p-0">
        <table class="table">
          <thead>
            <tr>
              <th style="width: 10px">#</th>
              <th>Agent ID</th>
              <th>Agent Name</th>
              <th>User ID</th>
              <th>Password</th>
             
              <th>Platform</th>
              <th>Percentage</th>
              <th>Parent</th>
              <th>Prefix</th>
              <th>Status</th>
              <th colspan="4" style="width:90px">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $cnt = 1;
            foreach ($agntArr as $sbagnt) {
              ?>
              <tr>
                <td><?php echo $cnt; ?></td>
                <td><?php echo $sbagnt->agentid; ?></td>
                <td><?php echo $sbagnt->agentname; ?></td>
                <td><?php echo $sbagnt->userid; ?></td>
                <td title="<?php echo $sbagnt->pwd; ?>"><i>Hidden (mouse hover to view)</i></td>
                
                <td><?php echo $sbagnt->platform; ?></td>
                <td><?php echo $sbagnt->percentage; ?></td>
                <td><?php echo $sbagnt->parent; ?></td>
                <td><?php echo $sbagnt->prefix; ?></td>
                
                <td style="color:green;">Active</td>
                <td>
                  <a href="add-agent.php?agtid=<?php echo $sbagnt->agentid;?>" class="btn btn-info">View</a>
                  <!-- <button type="button" class="btn btn-danger">Delete</button> -->
                </td>
              </tr>
            <?php $cnt++;} ?>







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