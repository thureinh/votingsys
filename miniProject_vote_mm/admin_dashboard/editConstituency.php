<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Edit Constituency</title>

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

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
        <?php 
        $id=$_GET['editid'];
        try{
        $id=explode('-',$id);
        $id=(int)$id[1];
        $conn=new PDO('mysql:host=localhost;dbname=test','root','');
        $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $stmt=$conn->prepare('SELECT * FROM constituency WHERE conid=:id');
        $stmt->bindParam(':id',$id);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result=$stmt->fetch();
        }catch(PDOException $e){
          echo $e->getMessage();
        }
        $conn=null;
         ?>
         <div class="container mt-3">
<h1>Add Constituency</h1>
<form id="form1" method="POST" action="../transferDB.php">
  <input type='hidden' name='pid' value="5">
  <input type='hidden' name='editid' value="<?php echo $result['conid'] ?>">
  <div class="form-group">
    <label for="con_name">Constituency</label>
    <input type="text" class="form-control" name="con_name" value="<?php echo $result['con_name']; ?>" placeholder="Enter Constituency Name" maxlength="60" >
    <div class="invalid-feedback"></div><div class="valid-feedback"></div>
  </div>
  <div class="form-group">
    <label for="con_township">Township</label>
    <input type="text" class="form-control" name="township" value="<?php echo $result['township'] ?>" placeholder="Township" maxlength="60" >
    <div class="invalid-feedback"></div><div class="valid-feedback"></div>
  </div>
  <button type="submit" name="btnSubmit" value="Add" class="btn btn-primary">Edit</button>
  <button type="button" onclick="location.replace('manageConstituency.php');" class="btn btn-primary">Cancel</button>
</form>
  <div class="alert alert-success" id="responseMsg" role="alert">
  </div>
</div>

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
      console.log('Document Loaded!');
      $('#responseMsg').hide();
      $('#form1').on('focusout','input[type=text]',function(event){
        var value1=$(this).val();
        if(!value1.trim()){
          $(event.target).addClass('is-invalid');
          $('#form1 .invalid-feedback').text('This field cannot be left empty!');
        }
        else{
            var pattern=/^[A-Za-z0-9,\.\s\u1000-\u109F-]+$/g;
            if(pattern.test(value1)){
              $(event.target).removeClass('is-invalid');
              $(event.target).addClass('is-valid');
              $('#form1 .valid-feedback').text('Input Correct');
            }
            else
            {
              $(event.target).addClass('is-invalid');
              $('#form1 .invalid-feedback').text('Illegal Characters Are Not Allowed');
            }
        }
      });
      $('#form1').on('submit',function(e){
        if(!$('#form1 input[type=text]').val().trim()){
          $('#form1 input[type=text]').addClass('is-invalid');
          $('#form1 .invalid-feedback').text('Please Fill All Forms');
          e.preventDefault();
        }
      });
    });
  </script>
</body>

</html>
