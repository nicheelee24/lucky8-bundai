 <?php 
 //session_start();
  ?>
 
 <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
   

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">  </div>
        
        <div class="info">
        <a href="index.php" class="d-block far fa-user-circle"> Bundai</a>
        
        <span style="font-size:18px;color:white">Welcome, <?php 
        if(isset($_SESSION["utype"]))
        {
 
 echo $_SESSION["utype"]; 
 } 
 else
 {
  header('Location: index.php?sts=sessionExpired', false);
 }
 ?> </span>

        </div>
  
      </div>

      <!-- SidebarSearch Form -->
      

      <!-- Sidebar Menu -->
  
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
               <li class="nav-item">
            <a href="dashboard.php" id="lnkDashboard" class="nav-link">
              <i class="nav-icon fa fa-home"></i>
              <p>Dashboard</p>
            </a>
          </li>
          

         <?php 
         
          if($_SESSION["prefix"]=="ADMIN"  || $_SESSION["utype"]=="EMPLOYEE" || $_SESSION["utype"]=="SBGT")
          { 
           
            ?>

            <li class="nav-item" <?php if(isset($_SESSION["access"])) if(!in_array('viewEmp',$_SESSION["access"])){ ?> style="display:none" <?php } ?>>
            <a href="manage-employees.php" id="lnkEmployees" class="nav-link">
              <i class="nav-icon fa fa-user-plus"></i>
              <p>Manage Employees</p>
            </a>
          </li>
         <?php } 

if($_SESSION["prefix"]=="ADMIN" || $_SESSION["utype"]=="EMPLOYEE" || $_SESSION["utype"]=="SBGT" )
{ ?>


<li class="nav-item" <?php if(isset($_SESSION["access"])) if(!in_array('memView',$_SESSION["access"])){ ?> style="display:none" <?php } ?>>
  <a href="manage-members.php" id="lnkMembers" class="nav-link">
    <i class="nav-icon fa fa-users"></i>
    <p>Manage Members</p>
  </a>
</li>

<?php

}
         
         if($_SESSION["prefix"]=="ADMIN" || $_SESSION["utype"]=="EMPLOYEE" || $_SESSION["utype"]=="SBGT" )
          { ?>
         
        
          <li class="nav-item" <?php if(isset($_SESSION["access"])) if(!in_array('history',$_SESSION["access"])){ ?> style="display:none" <?php } ?>>
            <a href="mem-history.php" id="lnkPlayersHistory" class="nav-link">
              <i class="nav-icon fa fa-history"></i>
              <p>Players / History Bet</p>
            </a>
          </li>
         
          <?php
         
          }
         
          if($_SESSION["prefix"]=="SADMIN")
          {
         ?>
         <li class="nav-item" <?php if(isset($_SESSION["access"])) if(!in_array('viewAgt',$_SESSION["access"])){ ?> style="display:none" <?php } ?>>
            <a href="#" id="lnkAgents" class="nav-link">
              <i class="nav-icon fa fa-users"></i>
              <p>Manage Agents</p>
            </a>
          </li>

        
          <?php }
         if(isset($_SESSION["utype"]))
         {
          if($_SESSION["utype"]!="SBGT" && $_SESSION["utype"]!="EMPLOYEE" && $_SESSION["utype"]!="PLAYER" && $_SESSION["prefix"]!="ADMIN")
          { ?>
          <li class="nav-item">
            <a href="#" id="lnkSubAgents" class="nav-link">
              <i class="nav-icon fas fa-sitemap"></i>
              
              <p> Manage Sub Agents </p>
            </a>
          </li>
          <?php
         
        }
       }
       
       ?>
<?php 

if(isset($_SESSION["utype"]))
         {
          if($_SESSION["utype"]=="SBGT" || $_SESSION['agent']=="master" || $_SESSION["prefix"]=="ADMIN" || $_SESSION["prefix"]=="EMPLOYEE" )
          {

?>
          <li class="nav-item" <?php if(isset($_SESSION["access"])) if(!in_array('winLossReport',$_SESSION["access"])){ ?> style="display:none" <?php } ?>>
            <a href="PLReport.php" class="nav-link" id="lnkReports">
              <i class="nav-icon fa fa-list-alt"></i>
              <p> Win / Loss Report </p>
            </a>
          </li>
          <li class="nav-item" <?php if(isset($_SESSION["access"])) if(!in_array('bonusReport',$_SESSION["access"])){ ?> style="display:none" <?php } ?>>
            <a href="bonus-report.php" class="nav-link" id="lnkBonusRep">
              <i class="nav-icon fa fa-list-alt"></i>
              <p> Bonus Report </p>
            </a>
          </li>
          <li class="nav-item" <?php if(isset($_SESSION["access"])) if(!in_array('summaryReport',$_SESSION["access"])){ ?> style="display:none" <?php } ?>>
            <a href="summary-report.php" class="nav-link" id="lnkSummaryRep">
              <i class="nav-icon fa fa-list-alt"></i>
              <p> Summary Report </p>
            </a>
          </li>
          <?php }} ?>
         


          <li class="nav-item" style="display:none" <?php if(isset($_SESSION["access"])) if(!in_array('history',$_SESSION["access"])){ ?>  <?php } ?>>
            <a href="history.php" class="nav-link" id="lnkHistory">
              <i class="nav-icon fa fa-history"></i>
              <p> History </p>
            </a>
          </li>

          <li class="nav-item" <?php if(isset($_SESSION["access"])) if(!in_array('promoView',$_SESSION["access"])){ ?> style="display:none" <?php } ?>>
            <a href="promote.php" class="nav-link" id="lnkPromote">
              <i class="nav-icon fa fa-line-chart"></i>
              <p>Manage  Promotion </p>
            </a>
          </li>
         
          <li class="nav-item">
            <a href="admin-profile.php" id="lnkProfile" class="nav-link">
              <i class="nav-icon fas fa-user"></i>
              <p> Profile </p>
            </a>
          </li>
          <!-- <li class="nav-item" <?php if(isset($_SESSION["access"])) if(!in_array('settings',$_SESSION["access"])){ ?> style="display:none" <?php } ?>>
            <a href="settings.php" id="lnkSettings" class="nav-link">
              <i class="nav-icon fas fa-gear"></i>
              <p> Settings </p>
            </a>
          </li> -->
          <li class="nav-item">
            <a href="logout.php" class="nav-link">
            <i class="nav-icon fa fa-sign-out"></i>
              <p> Logout </p>
            </a>
          </li>
                       
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>