<nav class="navbar navbar-expand-lg navbar navbar-dark" style="background-color: #4e73df;background-image: linear-gradient(180deg,#4e73df 10%,#4468d2 100%); background-size: cover;">
  <!-- <a class="navbar-brand" href="#" >Welcome</a> -->
  <img src="uploads/vote-restricted.png" class="navbar-brand rounded" style="width: 35px;height: 45px" alt="">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
    <?php function chckActive($stri){
            $str=$_SERVER['PHP_SELF']; 
      $result=preg_split("/\//", $str); 
      $result=$result[count($result)-1];
      if($stri==$result){
        return "active";
      }
  } ?>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav">
      <li class="nav-item <?php echo chckActive('index.php');?>">
        <a class="nav-link" href="index.php">Home (Vote Page) <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item <?php echo chckActive('display.php');?>">
        <a class="nav-link" href="display.php">Display Results</a>
      </li>
      <li class="nav-item <?php echo chckActive('overallReport.php');?>">
        <a class="nav-link" href="overallReport.php">Overall Report</a>
      </li>
      <?php 
      if(isset($_COOKIE['user']) && !empty($_COOKIE['user'])){
        $currentVoter=$_COOKIE['user'];
        preg_match('/^(?:\w+\s)?(\w+)/', $currentVoter,$matches);
        $currentVoter=$matches[1];
      ?>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-user"></i>&nbsp;Welcome <?php echo $currentVoter; ?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="destroy_cookies.php"><i class="fas fa-sign-out-alt"></i>&nbsp;Logout</a>
        </div>
      </li>
      <?php
      } 
      ?>
      <?php if(isset($_COOKIE["admin"]) && !empty($_COOKIE["admin"])){
      $currentuser=$_COOKIE["admin"];
        ?>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user-shield"></i>&nbsp;Welcome <?php $username=preg_split("/@/", $currentuser);echo $username[0]; ?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="admin_dashboard/index.php"><i class="fas fa-tachometer-alt"></i>&nbsp;Admin Dashboard</a>
          <a class="dropdown-item" href="votehistory.php"><i class="fas fa-history"></i>&nbsp;Vote History</a>
          <a class="dropdown-item" href="destroy_cookies.php"><i class="fas fa-sign-out-alt"></i>&nbsp;Logout</a>
        </div>
      </li>
    <?php }  ?>
    <?php if(!isset($_COOKIE['admin']) && !isset($_COOKIE['user'])) { ?>
      <li class="nav-item <?php echo chckActive('loginanim.php'); ?>">
        <a class="nav-link" href="loginanim.php">Admin Login</a>
      </li>
    <?php } ?>
    </ul>
    <ul class="navbar-nav ml-auto mr-5">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle text-white" href="#" id="dropdown09" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="flag-icon flag-icon-us"></span>&nbsp;English</a>
        <div class="dropdown-menu" aria-labelledby="dropdown09">
        <a class="dropdown-item" href="../miniProject_vote_mm/<?php echo basename($_SERVER['PHP_SELF']); ?>"><span class="flag-icon flag-icon-mm"></span>&nbsp;Myanmar</a>
        </div>
      </li>
    </ul>
  </div>
</nav>