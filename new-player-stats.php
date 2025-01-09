<?php
  include 'layout/header.php'; 
  ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
  <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>New Player Statistics</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">New Player Statistics</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
   
        
      
    <!-- /.content-header -->

    <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">New Player Statistics</h3>

                <div class="card-tools">
                <a class="btn btn-primary" href="add-feedback.php" role="button">Add</a>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <table class="table" >
                  <thead>
                    <tr>
                      <th >#</th>
                      <th>Player Name</th>
                      <th>Rating</th>
                      <th>Date</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>1.</td>
                      <td>Shobhit Garg</td>
                      <td>3 Star</td>
                      <td> 1-1-2022</td>
                     <td>
                      <button type="button" class="btn btn-success">Edit</button>
                      <button type="button" class="btn btn-danger">Delete</button></td>
                    </tr>


                    <tr>
                      <td >2.</td>
                      <td >Rahul</td>
                      <td>4 star</td>
                      <td>2-1-2022</td>
                      <td>
                      <button type="button" class="btn btn-success">Edit</button>
                      <button type="button" class="btn btn-danger">Delete</button></td>
                    </tr>

                    <tr>
                      <td>3.</td>
                      <td>Yash</td>
                      <td>5 Star</td>
                      <td>3-1-2022</td>
                      <td>
                      <button type="button" class="btn btn-success">Edit</button>
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
 