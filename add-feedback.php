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
                <h3 class="card-title">Add FeedBack</h3>
              </div> 
              <form>
                <div class="card-body">

                <div class="form-group">
                <div class="btn-group">
                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Mechanic Name 
                </button>
                
                  <div class="dropdown-menu">
                 <a class="dropdown-item" href="#">Shobhit</a>
                 <a class="dropdown-item" href="#">Rahul</a>
                 <a class="dropdown-item" href="#">Yash</a>
                
                </div>
                </div>
                <div class="form-group">
                <h4>Star Rating</h4>
                <span class="fa fa-star"></span>
                <span class="fa fa-star"></span>
                <span class="fa fa-star"></span>
                <span class="fa fa-star"></span>
                <span class="fa fa-star"></span>


                <div class="form-group">
                 <label><h4>FeedBack</h4></label>
                 <textarea id="subject" name="subject" placeholder="Write something.." style="width:100%; height:150%"></textarea>
                  </div>
                  

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
 