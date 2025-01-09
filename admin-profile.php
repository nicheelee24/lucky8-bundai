<?php
session_start();
$uid="";
$filter='';
if(isset($_SESSION["uid"]))
{
  $uid=$_SESSION["uid"];
}
$mongo = new MongoDB\Driver\Manager("mongodb+srv://nicheelee24:B0wrmtGcgtXKoXWN@cluster0.8yb8idj.mongodb.net/gms2024?retryWrites=true&w=majority&appName=Cluster0&serverSelectionTryOnce=false&serverSelectionTimeoutMS=30");
if($_SESSION["utype"]!="PLAYER")
{
  $filter=['userid'=>$uid];
  $options = [];
$query = new MongoDB\Driver\Query($filter,$options);
$rows = $mongo->executeQuery('gms2024.agents',$query);
$agntsArr = $rows->toArray();
}
else
{
  $filter=['name'=>$uid];
  $options = [];
$query = new MongoDB\Driver\Query($filter,$options);
$rows = $mongo->executeQuery('gms2024.users',$query);
$agntsArr = $rows->toArray();
}



  include 'layout/header.php'; 
  ?>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">User Profile</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
        <div class="col-md-3"></div>

          <div class="col-md-6">

          <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">User Profile</h3>
              </div> 
<?php 
if($_SESSION["utype"]=="PLAYER"){
?>
              <form>

                <div class="card-body">
                  <div class="form-group">
                    <label>User ID</label>
                    <input type="text" class="form-control" readonly value="<?php echo $agntsArr[0]->userid;?>" placeholder="User ID">
                  </div>
                  <div class="form-group">
                    <label>User Name</label>
                    <input type="text" class="form-control" readonly value="<?php echo $agntsArr[0]->agentname;?>"  placeholder="User Name">
                  </div>
                  <div class="form-group">
                    <label>Currency</label>
                    <input type="text" class="form-control" readonly value="THB"  placeholder="Currency">
                  </div>
                  <div class="form-group">
                    <label>Account Type</label>
                    <?php 
                    $type="";
                    if(property_exists($agntsArr[0], 'type'))
                    {
                      $type=$agntsArr[0]->type;
                    }
                    else
                    {
                      $type="Agent";
                    }
                   
                    ?>
                    <input type="text" class="form-control" readonly value="<?php echo $type;?>"  placeholder="Account Type">
                  </div>
                  <div class="form-group">
                    <label>Created On</label>
                    <?php 
                    $dt="";
                    if(property_exists($agntsArr[0], 'date'))
                    {
                      $dt=$agntsArr[0]->date;
                    }
                    
                   
                    ?>
                    <input type="text" class="form-control" readonly value="<?php echo $dt;?>" placeholder="Created On">
                  </div>
                  <div class="form-group">
                    <label >Password</label>
                    <input type="password" class="form-control" readonly value="<?php echo $agntsArr[0]->pwd;?>" placeholder="Password">
                  </div>

                 
                  
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Update</button>
                </div>
              </form>
              <?php } else{?>
                <form>

<div class="card-body">
  <div class="form-group">
    <label>User ID</label>
    <input type="text" class="form-control" readonly value="<?php echo $agntsArr[0]->_id;?>" placeholder="User ID">
  </div>
  <div class="form-group">
    <label>User Name</label>
    <input type="text" class="form-control" readonly value="<?php echo $agntsArr[0]->name;?>"  placeholder="User Name">
  </div>
  <div class="form-group">
    <label>Phone</label>
    <input type="text" class="form-control" readonly value="<?php echo $agntsArr[0]->phone;?>"  placeholder="User Phone">
  </div>
  <div class="form-group">
    <label>Currency</label>
    <input type="text" class="form-control" readonly value="THB"  placeholder="Currency">
  </div>
  <div class="form-group">
    <label>Account Type</label>
    <?php 
    $type="Player";
    
   
    ?>
    <input type="text" class="form-control" readonly value="<?php echo $type;?>"  placeholder="Account Type">
  </div>
  <div class="form-group">
    <label>Created On</label>
    <?php 
    $dt="";
    if(property_exists($agntsArr[0], 'date'))
    {
      $dt=$agntsArr[0]->date;
    }
    
   
    ?>
    <input type="text" class="form-control" readonly value="<?php echo $dt;?>" placeholder="Created On">
  </div>
  <div class="form-group">
    <label >Password</label>
    <input type="password" class="form-control" readonly value="" placeholder="Password">
  </div>

 
  
<!-- /.card-body -->

<div class="card-footer">
  <button type="submit" class="btn btn-primary">Update</button>
</div>
</form>
                <?php } ?>
            </div>
          </div>
          <!-- /.col -->
     
          <!-- /.col -->
        </div>
        <div class="col-md-3"></div>

        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>            

  <?php
  include 'layout/footer.php'; 
  ?>
 