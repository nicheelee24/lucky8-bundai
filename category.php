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
  <div class="content-header">
      <div class="container-fluid">
        
        <div class="row mb-2">
          <div class="col-sm-6">

          <div class="col-sm-3"></div>
            <h1>Categories</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Categories</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">All Categories</h3>

                <div class="card-tools">
                <a class="btn btn-primary" href="add-category.php" role="button">Add Category</a>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <table class="table" >
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Name</th>
                      <th>Status</th>
                      <th colspan="2" style="width: 90px">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>1.</td>
                      <td>XYZ</td>
                      <td>Active</td>
                    

                      <td><button type="button" class="btn btn-info">View</button>
                      <button type="button" class="btn btn-success">Update</button>
                      <button type="button" class="btn btn-danger">Delete</button></td>
                    </tr>
                    <tr>
                      <td>2.</td>
                      <td>ABC</td>
                      <td style="color:red;">InActive</td>
                    

                      <td><button type="button" class="btn btn-info">View</button>
                      <button type="button" class="btn btn-success">Update</button>
                      <button type="button" class="btn btn-danger">Delete</button></td>
                    </tr>

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
 