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
  <?php
  include 'layout/nav.php'; 
  ?>
  <!-- Main Sidebar Container -->
 
  <?php
  include 'layout/sidebar.php'; 
  ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    
    <section class="content">
      <div class="container-fluid">
        <div class="row">
        

          <div class="col-md-6">

          <div class="card card-primary"style = "margin-top:10px;">
              <div class="card-header">
                <h3 class="card-title">Add Category</h3>
              </div> 
              <form>
                <div class="card-body">
                <div class="form-group">
                    <label>Category Name</label>
                    <input type="text" class="form-control"  placeholder="Enter Category Name">
                  </div>

                  <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="check1" name="isactive" value="something" >
                   <label class="form-check-label" >InActive</label>
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
 