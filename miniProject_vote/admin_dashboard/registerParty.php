<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Register Party</title>

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
      <div id="content" class="mt-3">

        <!-- Topbar -->

        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

   <h1>Register Party</h1><br>
<div class="row">
  <div class="col">
  <form id="partyForm" enctype="multipart/form-data">
      <input type="hidden" name="pid" value="1">
  <div class="form-group">  
      <label for="input1">Full Party Name</label>
    <div class="alert alert-danger error-msg" role="alert" style="display: none"></div>
    <input type="text" name="partynameL" onfocusout="noFoc(this)" class="form-control" id="input1" placeholder="Complete Party Name" maxlength="300">
  </div>
  <div class="form-group">
    <label for="input2">Short Party Name</label>
    <div class="alert alert-danger error-msg" role="alert" style="display: none"></div>
    <input type="text" name="partynameS" onfocusout="noFoc(this)" class="form-control" id="input2" placeholder="Acronym" maxlength="50">
  </div>
  <div class="form-group">
    <label for="textarea1">Description</label>
    <div class="alert alert-danger error-msg" role="alert" style="display: none"></div>
    <textarea class="form-control" name="desc" onfocusout="noFoc(this)" id="textarea" rows="3" placeholder="Party Quote"></textarea>
  </div>
  </form>
  <div class="custom-file">
    <label for="file">Select Party Logo</label>
    <input type="file" onchange="showThumbnail(this)" class="form-control" name="logo" id="file">
  </div>
  <div id="showImage" style="display: none;">
      <img src="#" id='hiddenImg' alt="logo" style="height: 200px;width: 200px;margin-left: 456px;">
  </div>
  </div>
  <br>
      </div>
<button type="button" onclick="insertParty()" id="btnSubmit" class="btn btn-primary btn-lg btn-block" style="margin-top: 15px;">Register</button>
    <div class="alert alert-success font-weight-bolder" id="showmsg" role="alert" style="display: none">
    </div>
  </div>

        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
<?php include 'include_files/footer.php'; ?>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

<script type="text/javascript">
    var faultArray=[false,false];
    function noFoc(obj){
        var name=obj.name;
        var value=obj.value;
    console.log("name => ["+name+"]----value => ["+value+"]");
    if(name=="partynameL")
    {
        var pattern=/^[\w\s\'()\u1000-\u109F]+$/g;
        faultArray[0]=checker(0,pattern,value);
    }
    if(name=="partynameS")
    {
        var pattern=/^[\w\s\.()\u1000-\u109F]+$/g;
        faultArray[1]=checker(1,pattern,value);
    }
//------------- button disable-enable -------------------
    var should;
    var button=document.getElementById('btnSubmit');
    for(var k=0;k<faultArray.length;k++){
    should=faultArray[k];
    if(!should){
        break;
    }
    }
    if(should){
    button.disabled=false;
    }
    }
    function showThumbnail(input){
        if(input.files && input.files[0]){
            var reader=new FileReader();
            reader.onload=function(event){
                document.getElementById('showImage').style.display="block";
                document.getElementById('hiddenImg').src=event.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    function insertParty() {
    var fd=new FormData();
    var nameL=document.getElementById('input1');
    var nameS=document.getElementById('input2');
    var desc=document.getElementById('textarea');
    var file=document.getElementById('file');
    var formitems=document.getElementById('partyForm').elements;
    for(var i=0;i<formitems.length;i++){
        if(i==3) 
        {
            fd.append(formitems[i].name,formitems[i].value);continue;
        }
        if(formitems[i].value==""){
            document.getElementById('showmsg').innerText="All inputs are mandatory..Can not be left empty.";
            document.getElementById('showmsg').style.display="block";
            setTimeout(function(){document.getElementById('showmsg').style.display="none";},5000);
            return;
        }
        fd.append(formitems[i].name,formitems[i].value);
    }
    var img = document.getElementById('file').files[0];
    fd.append("logo",img);
    var xhr;
    if(window.XMLHttpRequest){
        xhr=new XMLHttpRequest();
    }else{
        xhr=new ActiveXObject('Microsoft.XMLHTTP');
    }
    xhr.onreadystatechange=function(){
        if(xhr.readyState==4 && xhr.status==200){
            document.getElementById('showmsg').innerText=this.responseText;
            document.getElementById('showmsg').style.display="block";
            setTimeout(function(){document.getElementById('showmsg').style.display="none";},5000);
            nameL.value="";nameS.value="";desc.value="";file.value="";
            document.getElementById('showImage').style.display="none";
        }
    }
    xhr.open('POST','../transferdb.php',true);
    xhr.send(fd);
    }
      function checker(index,pat,check) {
         if(check!==""){
          var result=pat.test(check);
          console.log("Validation Result => "+result+"\n");
          if(!result){
            respErr(index);
            return false;
          }
          else{
            respSuccess(index);
            return true;
          }
        }else{
          respEmpty(index);
          return false;
        }
  }
    function respErr(index){
      var err=document.getElementsByClassName('error-msg');
      var button=document.getElementById('btnSubmit');
      button.disabled=true;
      err[index].style.display="block";
      err[index].innerHTML="<i class='fas fa-exclamation-triangle'></i> Invalid Format.Please check again.";
      err[index].style.marginBottom="0px";
    }
    function respSuccess(index){
      var err=document.getElementsByClassName('error-msg');
      err[index].style.display="none";
    }
    function respEmpty(index){
      var err=document.getElementsByClassName('error-msg');
      var button=document.getElementById('btnSubmit');
      button.disabled=true;
      err[index].style.display="block";
      err[index].innerHTML="<i class='fas fa-exclamation-triangle'></i> This field can not be left empty.";
      err[index].style.marginBottom="0px";
    }
</script>
  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
