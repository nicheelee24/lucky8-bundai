<?php
include 'layout/header.php';
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">

      <div class="row mb-2">
        <div class="col-sm-6">

          <div class="col-sm-3"></div>
          <h1>Add Member</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Manage Members</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->


  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <form method="post" action="manage-members.php" style="width:100%">
          <!-- TABS START -->
         
            <div class="col-md-12">
              <div class="card card-primary card-tabs">
                <div class="card-header p-0 pt-1">
                  <ul class="nav nav-tabs" id="custom-tabs-five-tab" role="tablist">

                    <li class="nav-item">
                      <a class="nav-link active" id="custom-tabs-five-normal-tab" data-toggle="pill"
                        href="#custom-tabs-five-normal" role="tab" aria-controls="custom-tabs-five-normal"
                        aria-selected="false">Basic</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="custom-tabs-five-normal2-tab" data-toggle="pill"
                        href="#custom-tabs-five-normal2" role="tab" aria-controls="custom-tabs-five-normal2"
                        aria-selected="false">Product</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="custom-tabs-five-normal3-tab" data-toggle="pill"
                        href="#custom-tabs-five-normal3" role="tab" aria-controls="custom-tabs-five-normal3"
                        aria-selected="false">Currency</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="custom-tabs-five-normal4-tab" data-toggle="pill"
                        href="#custom-tabs-five-normal4" role="tab" aria-controls="custom-tabs-five-normal4"
                        aria-selected="false">PT Settings</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="custom-tabs-five-normal5-tab" data-toggle="pill"
                        href="#custom-tabs-five-normal5" role="tab" aria-controls="custom-tabs-five-normal5"
                        aria-selected="false">Product Setting</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="custom-tabs-five-normal6-tab" data-toggle="pill"
                        href="#custom-tabs-five-normal6" role="tab" aria-controls="custom-tabs-five-normal6"
                        aria-selected="false">Bet Limit</a>
                    </li>
                  </ul>
                </div>
                <div class="card-body">
                  <div class="card-footer" style="float:right">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="submit" class="btn btn-danger">Cancel</button>
                  </div>
                  <div class="tab-content" id="custom-tabs-five-tabContent">

                    <div class="tab-pane fade show active" id="custom-tabs-five-normal" role="tabpanel"
                      aria-labelledby="custom-tabs-five-normal-tab">


                      <label style="color:#007bff;font-size:16px">Basic Details</label>
                      <div class="form-group">
                        <label>Account Type</label>
                        <input type="text" class="form-control" placeholder="Account Type">
                      </div>
                      <div class="form-group">
                        <label>User ID</label>
                        <input type="text" class="form-control" placeholder="User ID">
                      </div>

                      <div class="form-group">
                        <label>User Name</label>
                        <input type="text" class="form-control" placeholder="User Name">
                      </div>

                      <div class="form-group">
                        <label>Prefix</label>
                        <input type="number" class="form-control" placeholder="Enter Prefix">
                      </div>

                      <div class="form-group">
                        <label>Client API Secret</label>
                        <input type="text" class="form-control" placeholder="Client API Secret">
                      </div>
                      <div class="form-group">
                        <label>Callback URL</label>
                        <input type="text" class="form-control" placeholder="Callback URL">
                      </div>

                      <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" placeholder="Enter Password">
                      </div>
                      <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" class="form-control" placeholder="Confirm Password">
                      </div>


                      <!-- /.card-body -->



                    </div>

                    <div class="tab-pane fade" id="custom-tabs-five-normal2" role="tabpanel"
                      aria-labelledby="custom-tabs-five-normal2-tab">
                      <label style="color:#007bff;font-size:16px">Product</label>
                      <div class="form-group">

                        <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" type="checkbox" id="chkAllProducts"
                            value="selectAllProducts">
                          <label for="chkAllProducts" class="custom-control-label">Select Product</label>
                        </div>
                      </div>

                      <div class="form-group">

                        <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" type="checkbox" id="chkTable" value="Table">
                          <label for="chkTable" class="custom-control-label">Table</label>
                        </div>
                      </div>
                      <div class="form-group">

                        <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" type="checkbox" id="chkFish" value="FISH">
                          <label for="chkFish" class="custom-control-label">FISH</label>
                        </div>
                      </div>

                      <div class="form-group">

                        <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" type="checkbox" id="chkLotto" value="LOTTO">
                          <label for="chkLotto" class="custom-control-label">LOTTO</label>
                        </div>
                      </div>

                      <div class="form-group">

                        <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" type="checkbox" id="chkSlot" value="SLOT">
                          <label for="chkSlot" class="custom-control-label">SLOT</label>
                        </div>
                      </div>

                      <div class="form-group">

                        <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" type="checkbox" id="chkEsport" value="ESPORTS">
                          <label for="chkEsport" class="custom-control-label">ESPORTS</label>
                        </div>
                      </div>

                      <div class="form-group">

                        <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" type="checkbox" id="chkEgame" value="EGAME">
                          <label for="chkEgame" class="custom-control-label">EGAME</label>
                        </div>
                      </div>

                      <div class="form-group">

                        <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" type="checkbox" id="chkLive" value="LIVE">
                          <label for="chkLive" class="custom-control-label">LIVE</label>
                        </div>
                      </div>

                      <div class="form-group">

                        <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" type="checkbox" id="chkVirtual" value="VIRTUAL">
                          <label for="chkVirtual" class="custom-control-label">VIRTUAL</label>
                        </div>
                      </div>
                    </div>

                    <div class="tab-pane fade" id="custom-tabs-five-normal3" role="tabpanel"
                      aria-labelledby="custom-tabs-five-normal3-tab">
                      Mauris tincidunt mi at erat gravida, eget tristique urna bibendum. Mauris pharetra purus ut ligula
                      tempor, et vulputate metus facilisis. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                      Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas
                      sollicitudin, nisi a luctus interdum, nisl ligula placerat mi, quis posuere purus ligula eu
                      lectus. Donec nunc tellus, elementum sit amet ultricies at, posuere nec nunc. Nunc euismod
                      pellentesque diam.
                    </div>

                    <div class="tab-pane fade" id="custom-tabs-five-normal4" role="tabpanel"
                      aria-labelledby="custom-tabs-five-normal4-tab">
                      Mauris tincidunt mi at erat gravida, eget tristique urna bibendum. Mauris pharetra purus ut ligula
                      tempor, et vulputate metus facilisis. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                      Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas
                      sollicitudin, nisi a luctus interdum, nisl ligula placerat mi, quis posuere purus ligula eu
                      lectus. Donec nunc tellus, elementum sit amet ultricies at, posuere nec nunc. Nunc euismod
                      pellentesque diam.
                    </div>

                    <div class="tab-pane fade" id="custom-tabs-five-normal5" role="tabpanel"
                      aria-labelledby="custom-tabs-five-normal5-tab">
                      Mauris tincidunt mi at erat gravida, eget tristique urna bibendum. Mauris pharetra purus ut ligula
                      tempor, et vulputate metus facilisis. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                      Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas
                      sollicitudin, nisi a luctus interdum, nisl ligula placerat mi, quis posuere purus ligula eu
                      lectus. Donec nunc tellus, elementum sit amet ultricies at, posuere nec nunc. Nunc euismod
                      pellentesque diam.
                    </div>

                    <div class="tab-pane fade" id="custom-tabs-five-normal6" role="tabpanel"
                      aria-labelledby="custom-tabs-five-normal6-tab">
                      Mauris tincidunt mi at erat gravida, eget tristique urna bibendum. Mauris pharetra purus ut ligula
                      tempor, et vulputate metus facilisis. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                      Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas
                      sollicitudin, nisi a luctus interdum, nisl ligula placerat mi, quis posuere purus ligula eu
                      lectus. Donec nunc tellus, elementum sit amet ultricies at, posuere nec nunc. Nunc euismod
                      pellentesque diam.
                    </div>
                  </div>


                </div>
                <!-- /.card -->
              </div>
            </div>
        

          <!-- TABS END -->
        </form>

      </div><!-- /.container-fluid -->

  </section>
  <!-- /.content -->
</div>

<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>

<?php
include 'layout/footer.php';
?>