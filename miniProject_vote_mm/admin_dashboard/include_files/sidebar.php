    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
      <?php 
        try {
          $conn=new PDO('mysql:host=localhost;dbname=test','root','');
          $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
          $stmt=$conn->prepare('SELECT puid FROM pending_users');
          $stmt->execute();
          $pendingCounts=$stmt->rowCount();
        } catch (PDOException $e) {
          echo $e->getMessage();
        }
          $conn=null;
      ?>
      <?php 
      function chckActive( $stri, $strii =  null){
      $str=$_SERVER['PHP_SELF']; 
      $result=preg_split("/\//", $str); 
      $result=$result[count($result)-1];
      if($stri==$result){
        return "active";
      }
      if($strii !== null){
        if($strii == $result){
          return "active";
        }
      }
      } 
      ?>
      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-icon">
          <i class="fas fa-user-shield"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Admin Page</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item <?php echo chckActive('index.php');?>">
        <a class="nav-link pb-2" href="index.php">
          <i class="fas fa-sitemap"></i>
          <span>‌နေရာချထားခြင်း</span></a>
      </li>
      <li class="nav-item <?php echo chckActive( 'voterManagement.php', 'acceptedManagement.php');?>">
          <a class="nav-link" href="voterManagement.php">
            <i class="fas fa-bell"></i>&nbsp;
            <span>ခွင့်တောင်းထားမှုများ</span>&nbsp;<span class="badge badge-light" id="sidebar-pending-counts-decrease"><?php echo $pendingCounts; ?></span>
          </a>
      </li>
      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Interface
      </div>

      <!-- Nav Item - Utilities Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-fw fa-folder-plus"></i>
          <span>စာရင်းသွင်းရန်</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">စာရင်းသွင်းခြင်းကဏ္ဍ</h6>
            <a class="collapse-item" href="registerCandidate.php"><i class="fas fa-user-plus"></i>&nbsp;ကိုယ်စားလှယ်</a>
            <a class="collapse-item" href="registerParty.php"><i class="fas fa-users"></i>&nbsp;ပါတီ</a>
            <a class="collapse-item" href="registerElection.php"><i class="fas fa-vote-yea"></i>&nbsp;ရွေးကောက်ပွဲ</a>
            <a class="collapse-item" href="registerConstituency.php"><i class="fas fa-map-marked-alt"></i>&nbsp;မဲဆန္ဒနယ်</a>
          </div>
        </div>
      </li>

      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-cog"></i>
          <span>စီမံခန့်ခွဲရန်</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">စီမံခန့်ခွဲခြင်းကဏ္ဍ</h6>
            <a class="collapse-item" href="manageCandidate.php"><i class="fas fa-user-cog"></i>&nbsp;ကိုယ်စားလှယ်</a>
            <a class="collapse-item" href="manageParties.php"><i class="fas fa-users-cog"></i>&nbsp;ပါတီ</a>
            <a class="collapse-item" href="manageElections.php"><i class="fas fa-tools"></i>&nbsp;ရွေးကောက်ပွဲ</a>
            <a class="collapse-item" href="manageConstituency.php"><i class="fas fa-toolbox"></i>&nbsp;မဲဆန္ဒနယ်</a>
          </div>
        </div>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Back
      </div>
        <li class="nav-item">
        <a class="nav-link" href="../index.php"><i class="fas fa-arrow-circle-left"></i>
          <span>ပင်မစာမျက်နှာသို့သွားပါ</span>
        </a>
      </li> 

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>