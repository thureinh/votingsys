<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Edit Candidate</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.1.0/css/flag-icon.min.css" rel="stylesheet">
  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
<style>
  .login-page {
  width: 360px;
  padding: 8% 0 0;
  margin: auto;
}
.form {
  position: relative;
  z-index: 1;
  background: #ffffff;
  max-width: 360px;
  margin: 0 auto 100px;
  padding: 45px;
  text-align: center;
  box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
}
.form input {
  font-family: "Roboto", sans-serif;
  outline: 0;
  background: #f2f2f2;
  width: 100%;
  border: 0;
  margin: 0 0 15px;
  padding: 20px;
  box-sizing: content-box;
  font-size: 14px;
}

.form button {
  font-family: "Roboto", sans-serif;
  text-transform: uppercase;
  outline: 0;
  background: #4CAF50;
  width: 100%;
  border: 0;
  padding: 15px;
  color: #FFFFFF;
  font-size: 14px;
  -webkit-transition: all 0.3 ease;
  transition: all 0.3 ease;
  cursor: pointer;
  box-sizing: content-box;
}
.form button:hover,.form button:active,.form button:focus {
  background: #43A047;
}
.form .message {
  margin: 15px 0 0;
  color: #b3b3b3;
  font-size: 12px;
}
.form select{
  box-sizing: content-box; 
}
.form .message a {
  color: #4CAF50;
  text-decoration: none;
}
.form .register-form {
  display: none;
}
.container {
  position: relative;
  z-index: 1;
  max-width: 300px;
  margin: 0 auto;
}
.container:before, .container:after {
  content: "";
  display: block;
  clear: both;
}
.form{
  box-sizing: content-box;
}

</style>
</head>

<body id="page-top" onload="showOrigin()">

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
 

          <!-- Page Heading -->
  <?php if(isset($_GET['editid']) && !empty($_GET['editid'])){ ?>
  <div class="edit page">
<div class="form" style="top: 050px;width: 600px;padding-right: 80px;">
      <form class="edit-form" action="../dbprocessor.php" method="POST" enctype="multipart/form-data">
        <h1> Edit Details </h1>
        <input type="hidden" id="editid" name="editmal" value="<?php echo $_GET['editid']; ?>">
        <input type="hidden" id="curtime_id" name="curtime" value="">
  <input type="hidden" name="originimg" id="originimg" value="">
       <div class="alert alert-danger err" role="alert" style="display: none;"></div>
        <input type="text" id="name" name="name" placeholder="name" onfocusout="validateInput(this)"/>
         <div class="alert alert-danger err" role="alert" style="display: none;"></div>
        <input type="text" id="nrc" name="nrc" placeholder="nrc number" onfocusout="validateInput(this)"/>
         <div class="alert alert-danger err" role="alert" style="display: none;"></div>
    <input type="text" id="nationality" name="nationality" placeholder="Race" onfocusout="validateInput(this)"/>
    <br>
     <div class="alert alert-danger err" role="alert" style="display: none;"></div>
    <select class="browser-default custom-select" id="religious" name="religious" style="height: 45px;
        margin-bottom: 18px;" onfocusout="validateInput(this)">
    <option value="Buddhism">Buddhism</option>
    <option value="Christianity">Christianity</option>
    <option value="Islam">Islam</option>
    <option value="Hinduism">Hinduism</option>
    <option value="Others">Other Religions</option>
    </select>
     <div class="alert alert-danger err" role="alert" style="display: none;"></div>
        <input type="text" id="email" name="email" placeholder="email" onfocusout="validateInput(this)"/>
         <div class="alert alert-danger err" role="alert" style="display: none;"></div>
        <input type="text" id="address" name="address" placeholder="address" onfocusout="validateInput(this)"/>
         <div class="alert alert-danger err" role="alert" style="display: none;"></div>
        <input type="text" id="phone" name="phone" placeholder="Mobile phone number" onfocusout="validateInput(this)"/>
        <?php
        try{
    $conn=new PDO("mysql:hostname=localhost;dbname=test;","root","");
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $stmt=$conn->prepare("SELECT parid,party_name_S FROM parties ORDER BY party_name_S");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $queries=$stmt->fetchAll();
      }catch(PDOException $e){
        echo "Connection Failed ===> ".$e;
      }
      $conn=null;
         ?>
      <div class="alert alert-danger err" role="alert" style="display: none;"></div>
       <select class="browser-default custom-select" id="party_id" name="party" style="height: 45px;
        margin-bottom: 18px;" onfocusout="validateInput(this)">
    <option value="0"><b>None</b></option>
        <?php
          foreach ($queries as $value) {
        ?>
    <option value="<?php echo $value['parid']; ?>"><?php echo $value['party_name_S']; ?></option>
         <?php    
          }
         ?>
       </select>
        <label for="img"><b>Your Profile : </b></label>
        <img src="" alt="profile" id="img" class="img-thumbnail" style="width: 300px;height: 300px;margin-left: 50px;">
      <input type="file" class="mt-2" name="editimg" id="edfile" onchange="stateChange(this)">
        <br>

        <button type="submit" id="btnSubmit" style="padding-right: 25px; margin-bottom: 10px">Edit</button>
         
        <button type="button" style="padding-right: 25px; margin-bottom: 10px" onclick="location.replace('manageCandidate.php');">Cancel</button>
      </form>
    </div>
  </div>
<?php } else { ?>
<div class="jumbotron">
  <h1 class="display-4">ACCESS DENIED!</h1>
  <p class="lead">You Need to Choose Which One To Edit</p>
  <hr class="my-4">
  <a class="btn btn-primary btn-lg" href="manageCandidate.php" role="button">Go Home</a>
</div>
<?php } ?>


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
     function stateChange(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                  console.log(e.target.result);
                    $('#img')
                        .attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
  document.getElementById('originimg').value="";
            }
        }
      function showOrigin(){
        var ajaz;
        var idforedit=document.getElementById('editid').value;
        var name=document.getElementById('name');
        var nrc=document.getElementById('nrc');
        var nationality=document.getElementById('nationality');
        var religious=document.getElementById('religious');
        var email=document.getElementById('email');
        var address=document.getElementById('address');
        var phone=document.getElementById('phone');
        var party=document.getElementById('party_id');        
        var image=document.getElementById('img');
        var curtime=document.getElementById('curtime_id');
        curtime.value=localStorage.getItem("Time");
        if(window.XMLHttpRequest){
          ajaz=new XMLHttpRequest();
        }else{
          ajaz=new ActiveXObject('Microsoft.XMLHTTP');
        }
        ajaz.onreadystatechange=function(){
          if(ajaz.readyState==4 && ajaz.status==200){
            console.log(resp);
            var resp=JSON.parse(this.responseText);
            name.value=resp[0]['name'];
            nrc.value=resp[0]['nrc'];
            nationality.value=resp[0]['nationality'];
            religious.value=resp[0]['religious'];
            email.value=resp[0]['email'];
            address.value=resp[0]['address'];
            phone.value=resp[0]['phone_no'];
            if(!resp[0]['parid'])
            {
              party.value=0;
            }
            else
            {
              party.value=resp[0]['parid'];
            }
            image.src="../"+resp[0]['image'];
        document.getElementById('originimg').value=resp[0]['image'];
          }
        }
        ajaz.open("GET","../dbprocessor.php?pid=4&editid="+idforedit,true);
        ajaz.send();
      }
      var btndis=[true,true,true,true,true,true];
function validateInput(obj){
        var should;
        var check=obj.value;
        var name=obj.name;
        console.log("The name is "+check+"\n");
        console.log("The value is "+check+"\n")
        if(name=="name"){     
  var pat= /^[A-Za-z\s\u1000-\u109F]+$/g;
    btndis[0]=checker(0,pat,check);   
  }
  if(name=="nrc"){
  var pat=/^[1\u1000-\u109F]?[0-4\u1000-\u109F]\/[A-Za-z\u1000-\u109F]+\([A-Za-z\u1000-\u109F]+\)[\d\u1000-\u109F]+$/g;
  btndis[1]=checker(1,pat,check);
  }
  if(name=="nationality"){
  var pat=/^[A-Za-z\u1000-\u109F]+$/;
  btndis[5]=checker(2,pat,check);
  }
  if(name=="religious"){
    if(check==""){
      respEmpty(3);
    }else{respSuccess(3);}
  }
  if(name=="email"){
    var pat= /(?!.*\.{2})^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
    btndis[2]=checker(4,pat,check);
  }
  if(name=="address"){
    var pat=/^[A-Za-z0-9\./,\(\)\s\u1000-\u109F]+(,|\u104a)[A-Za-z0-9.\s\u1000-\u109F]+(?:(,|\u104a)[A-Za-z\s\u1000-\u109F]+)?(,|\u104a)[A-Za-z\s\u1000-\u109F]+$/g;
    btndis[3]=checker(5,pat,check);
  }
  if(name=="phone"){
    var pat=/^[\d\s\u1000-\u109F-]{9,30}$/g;
    btndis[4]=checker(6,pat,check);
  }
  if(name=="party"){
    if(check==""){
      respEmpty(7);
    }else{respSuccess(7);}
  }
  console.log(btndis);
    for(var k=0;k<btndis.length;k++){
    should=btndis[k];
    if(!should){
        break;
    }
  }
  var button=document.getElementById('btnSubmit');
  console.log("Should is "+should);
  if(should){
    button.disabled=false;
    console.log(button);
  }
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
      var err=document.getElementsByClassName('err');
      var button=document.getElementById('btnSubmit');
      button.disabled=true;
      err[index].style.display="block";
      err[index].innerHTML="<i class='fas fa-exclamation-triangle'></i> Invalid Format.Please check again.";
      err[index].style.marginBottom="0px";
    }
    function respSuccess(index){
      var err=document.getElementsByClassName('err');
      var button=document.getElementById('btnSubmit');
      err[index].style.display="none";
    }
    function respEmpty(index){
      var err=document.getElementsByClassName('err');
      var button=document.getElementById('btnSubmit');
      err[index].style.display="block";
      err[index].innerHTML="<i class='fas fa-exclamation-triangle'></i> This field can not be left empty.";
      err[index].style.marginBottom="0px";
    }
  </script>
</body>

</html>
