<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Edit Party</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.1.0/css/flag-icon.min.css" rel="stylesheet">
  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">
  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
<?php include 'include_files/sidebar.php' ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <!-- End of Topbar -->
<?php 
  $pid=$_GET['editid'];
  $pid=explode("_", $pid);
  $pid=$pid[1];
  try{
    $conn=new PDO('mysql:host=localhost;dbname=test','root','');
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $stmt=$conn->prepare('SELECT * FROM parties WHERE parid=:pid');
    $stmt->bindParam(':pid',$pid);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $results=$stmt->fetch();
  }catch(PDOException $e){
    echo $e->getMessage();
  }
  $conn=null;
?>
        <!-- Begin Page Content -->
        <div class="container-fluid mt-3">
<h1>Edit Candidate</h1>
<form id="form1" enctype="multipart/form-data" method="POST" action='../transferdb.php' >
  <input type="hidden" name='pid' value="3">
  <input type='hidden' name='eparID' value="<?php echo $results['parid']; ?>">
  <input type='hidden' name='originSrc' value="<?php echo $results['logo']; ?>">
  <div class="form-group">
    <label for="partynameL">Party Name</label>
    <input type="text" class="form-control" id="partynameL" name="partynameL" placeholder="Full Party Name" maxlength="50" value="<?php echo $results['party_name_L']; ?>" >
<div class="invalid-feedback"></div><div class="valid-feedback"></div>
  </div>
  <div class="form-group">
    <label for="partynameS">Acronym</label>
   <input type="text" class="form-control" id="partynameS" name="partynameS" value="<?php echo $results['party_name_S']; ?>" placeholder="Party Name in Short">
<div class="invalid-feedback"></div><div class="valid-feedback"></div>
  </div>
  <div class="form-group">
    <label for="desc">Textarea</label>
    <textarea class="form-control" id="desc" name="desc" placeholder="Party Quote" ><?php echo $results['description']; ?></textarea>
  </div>
<div class="custom-file mb-2">
  <input type="file" class="custom-file-input" id="customFile" name="logofile">
  <label class="custom-file-label" for="customFile">Choose file</label>
</div>
<div class="text-center">
  <img src="../<?php echo $results['logo']; ?>" style='width: 200px;height: 200px' class="rounded" alt="">
</div>
<div class="row">
    <div class="col">
  <button type="submit" id="btnSubmit" class="btn btn-lg btn-primary btn-block mt-2">Submit</button>      
    </div>
    <div class="col">
        <button type="button" class="btn btn-lg btn-primary btn-block mt-2" onclick="location.replace('manageParties.php');">Cancel</button>
    </div>
</div>

</form>          

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>


  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>
<script>
   
    $(document).ready(function(){
      faultyArray=[true,true];
    $('#form1 input[type=text]').on('focusout',function(e){
      val=$(e.target).val();
      var index;
      if($(e.target).attr('name').trim()=='partynameL'){
        index=0;
      }
      else{ index=1; }
      if(!$.trim(val)){
        $(e.target).removeClass('is-valid');
        $(e.target).addClass('is-invalid');
        $('#form1 .invalid-feedback').text('This Field Cannot Be Left Empty!');
        $('#btnSubmit').attr('disabled',true);
        faultyArray[index]=false;
      }
      else
      {
        pattern=/^[\w\s()\u1000-\u109F]+$/g;
        if(!pattern.test(val)){
          $(e.target).removeClass('is-valid');
          $(e.target).addClass('is-invalid');
          $('#form1 .invalid-feedback').text('Invalid Format.Please Check Again');
          $('#btnSubmit').attr('disabled',true);
          faultyArray[index]=false;
        }
        else{
          $(e.target).removeClass('is-invalid');
          $(e.target).addClass('is-valid');
          $('#form1 .valid-feedback').text('Correct Input');
          faultyArray[index]=true;
          checkFaulty();
        }
      }
    });
    $('#form1').on('submit',function(e){
      var formitems=$('#form1 input[type=text]');
      $.each(formitems,function(key,value){
        if(!$.trim($(value).val())){
          $(value).removeClass('is-valid');
          $(value).addClass('is-invalid');
          $('#form1 .invalid-feedback').text('This Field Cannot Be Left Empty!');
          e.preventDefault();
        }
      });
    });
    $('#customFile').on('change',function(e){
      if(e.target.files && e.target.files[0]){
        reader=new FileReader();
        reader.onload=function(event){
          $('#form1 img.rounded').attr('src',event.target.result);
        };
        reader.readAsDataURL(e.target.files[0]);
      }
    });
    });
    function checkFaulty(){
      isok=true;
      $.each(faultyArray,function(index,value){
        if(!value){
          isok=false;
          return false;
        }
      });
      if(isok){
        $('#btnSubmit').attr('disabled',false);
      }
    }
</script>
</body>

</html>
