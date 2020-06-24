<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Register Constituency Page</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.1.0/css/flag-icon.min.css" rel="stylesheet">
  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">
<?php if(!isset($_COOKIE['admin'])) header("Location: ../loginanim.php") ?>
  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
<?php include 'include_files/sidebar.php'; ?>
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
<div class="container mt-3">
<h1>Register Constituency</h1>
<form id="form1" method="POST" action="../transferDB.php">
  <div class="form-group">
    <label for="con_name">Constituency</label>
    <input type="text" class="form-control" name="con_name" placeholder="Enter Constituency Name" maxlength="60" >
    <div class="invalid-feedback"></div><div class="valid-feedback"></div>
  </div>
  <div class="form-group">
    <label for="con_township">Township</label>
    <input type="text" class="form-control" name="township" placeholder="Township" maxlength="60" >
    <div class="invalid-feedback"></div><div class="valid-feedback"></div>
  </div>
  <button type="submit" name="btnSubmit" value="Add" class="btn btn-primary">Add</button>
</form>
  <div class="alert alert-success" id="responseMsg" role="alert">
  </div>
<!-- Google Maps -->
<!-- <div class="d-flex justify-content-center my-3">
<div style="height: 300px;width: 100%" id="g-maps"> 
</div>
</div> -->
 <!-- End Of Maps -->
</div>

        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
<?php include 'include_files/footer.php' ?>
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
    var faultyArray=[false,false];
    function checker(){
            var btn=$('button[name=btnSubmit]');
      var available=true;
      $.each(faultyArray,function(index,value){
        if(value===false){
          available=false;
          return false;
        }
      })
      if(available){
        btn.attr('disabled',false);
      }
    }
    $(document).ready(function(){
      console.log('Document Loaded!');
      $('#responseMsg').hide();
      var btn=$('button[name=btnSubmit]');
      $('#form1').on('focusout','input[type=text]',function(event){
        var value1=$(this).val();
        if(!value1.trim()){
          $(event.target).removeClass('is-valid');
          $(event.target).addClass('is-invalid');
          $('#form1 .invalid-feedback').text('This Field Cannot Be Left Empty');
          if($(event.target).attr('name')=='con_name'){
          faultyArray[0]=false;            
          }
          else{
          faultyArray[1]=false;             
          }
          btn.attr('disabled',true);
        }
        else{
            var pattern=/^[A-Za-z0-9,\.\u1000-\u109F\s-]+$/g;
            if(pattern.test(value1)){
              $(event.target).removeClass('is-invalid');
              $(event.target).addClass('is-valid');
              $('#form1 .valid-feedback').text('Input Correct');
          if($(event.target).attr('name')=='con_name'){
          faultyArray[0]=true;            
          }
          else{
          faultyArray[1]=true;             
          }
              checker();
            }
            else
            {
              $(event.target).removeClass('is-valid');
              $(event.target).addClass('is-invalid');
              $('#form1 .invalid-feedback').text('Illegal Characters Are Not Allowed');
          if($(event.target).attr('name')=='con_name'){
          faultyArray[0]=false;            
          }
          else{
          faultyArray[1]=false;             
          }
              btn.attr('disabled',true);
            }
        }
      });
      $('#form1 :submit').on('click',function(e){
        e.preventDefault();
        console.log('Clicked');
        var formitems=document.getElementById('form1').elements;
        var forms=[];
        var obj={};
        for (var i = formitems.length - 1; i >= 0; i--) {
          if(!formitems[i].value)
          {
              $('#form1 input[type=text]').removeClass('is-valid');
              $('#form1 input[type=text]').addClass('is-invalid');
              $('#form1 .invalid-feedback').text('This Field Cannot Be Left Empty');
              return;
          }
          obj[formitems[i].name]=formitems[i].value;
          forms.push(obj);
        }
          var jsonData=JSON.stringify(forms);
          $('#form1 input[type=text]').removeClass('is-invalid');
          $('#form1 input[type=text]').addClass('is-valid');
          $('#form1 .valid-feedback').text('Input Correct');
          $.ajax({
            url: "../transferDB.php",
            type: "POST",
            dataType : 'html',
            contentType : 'application/x-www-form-urlencoded',
            success :function(response){
              $('#responseMsg').text(response);
              $('#responseMsg').show();
              setTimeout(function(){ $('#responseMsg').hide(); },5000);
              $('#form1').find('input').val('');
              $('#form1 input').removeClass('is-valid');
            },
            data: {"j":jsonData},
          });
      });

    });
  </script>

</body>

</html>
