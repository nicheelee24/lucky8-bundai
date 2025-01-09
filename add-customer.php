<!DOCTYPE html>
<html lang="en">
<head>
  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>VSA ADMIN Panel</title>
  <?php
  include 'layout/link.php'; 
  ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div>

  <!-- Navbar -->
 
  <!-- /.navbar -->
  <?php
  include 'layout/nav.php'; 
  ?>
  <!-- Main Sidebar Container -->
 
  <?php
  include 'layout/sidebar.php'; 
  ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
     

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
      

          <div class="col-md-6">

          <div class="card card-primary" style="margin-top:5px;">
              <div class="card-header">
                <h3 class="card-title">Add Customer</h3>
              </div> 
              <form>
                <div class="card-body">

                <div class="form-group">
                    <label>Name</label>
                    <input type="text" class="form-control"  placeholder="Enter Name">
                  </div>

                  <div class="form-group">
                    <label>Email address</label>
                    <input type="email" class="form-control"  placeholder="Enter email">
                  </div>

                  <div class="form-group">
                    <label>Mobile</label>
                    <input type="number" class="form-control"  placeholder="Enter Mobile">
                  </div>

                  <div class="form-group">
                    <label>Date Of Birth</label>
                    <input type="date" class="form-control"  placeholder="Enter Date Of Birth">
                  </div>

                  <div class="form-group">
                    <label >Password</label>
                    <input type="password" class="form-control"  placeholder="Enter Password">
                  </div>

                  
                  
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Save</button>
                  <button type="submit" class="btn btn-danger">Cancel</button>
                </div>
              </form>
            </div>
          </div>
          <!-- /.col -->
     
          <!-- /.col -->
        </div>
        

        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>            

  <?php
  include 'layout/footer.php'; 
  ?>
 