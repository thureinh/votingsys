<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Register Candidate</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.1.0/css/flag-icon.min.css" rel="stylesheet">
  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  <style>

.input-container {
  display: -ms-flexbox; /* IE10 */
  display: flex;
  width: 100%;
  margin-bottom: 15px;
}

.icon {
  padding: 10px;
  background: dodgerblue;
  color: white;
  min-width: 50px;
  text-align: center;
}

.input-field {
  width: 100%;
  padding: 10px;
  outline: none;
}

.input-field:focus {
  border: 2px solid dodgerblue;
}

/* Set a style for the submit button */
.btn {
  background-color: dodgerblue;
  color: white;
  padding: 20px 20px;
  border: none;
  cursor: pointer;
  width: 100%;
  opacity: 0.9;
}

.btn:hover {
  opacity: 1;
}

</style>
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
  <?php if(isset($_COOKIE['admin']) && !empty($_COOKIE['admin'])){ ?> 
    <div class="container">
      <h1 style="margin-left: 400px">Register Candidate</h1>
      <div class="row">
      <div class="col-3"> </div>
      <div class="col-6" >
          <form id="form1">
    <input type="hidden" name="postp" value="insert">
    <div class="alert alert-danger err" role="alert" style="display: none;"></div>
    <div class="input-container">
    <i class="fa fa-user icon" style="font-size:25px;"></i>
    <input class="input-field empty" type="text" placeholder="Candidate's Full Name" name="cname" onfocusout="validateInput(this)"  maxlength="50">
    </div>

    <div class="alert alert-danger err" role="alert" style="display: none;"></div>
    <div class="input-container">
    <i class="fa fa-address-card icon" style="font-size:25px;"></i>
    <input class="input-field empty" type="text" placeholder="NRC Card" name="nrc" onfocusout="validateInput(this)">
    </div>

    <div class="alert alert-danger err" role="alert" style="display: none;"></div>
    <div class="input-container">
    <i class="fa fa-flag icon" style="font-size:25px;"></i>
    <input class="input-field empty" type="text" placeholder="Race" name="nationality" onfocusout="validateInput(this)"  maxlength="50">
    </div>

    <div class="alert alert-danger err" role="alert" style="display: none;"></div>
    <div class="input-container">
    <i class="fa fa-praying-hands icon" style="font-size:25px;"></i>
    <select class="browser-default custom-select" id="reli" name="religious" style="height: 45px;" onfocusout="validateInput(this)">
    <option selected value="">Select Religion</option>
    <option value="Buddhism">Buddhism</option>
    <option value="Christianity">Christianity</option>
    <option value="Islam">Islam</option>
    <option value="Hinduism">Hinduism</option>
    <option value="Others">Other Religions</option>
    </select>
    </div>

    <div class="alert alert-danger err" role="alert" style="display: none;"></div>
    <div class="input-container">
    <i class="far fa-envelope icon" style="font-size:25px;"></i>
    <input class="input-field empty" type="text" placeholder="Email" name="email" onfocusout="validateInput(this)">
    </div>

    <div class="alert alert-danger err" role="alert" style="display: none;"></div>
    <div class="input-container">
    <i class="fa fa-map-marked icon" style="font-size:25px;"></i>
    <input class="input-field empty" type="text" placeholder="Address(Street+District+City)" name="address" onfocusout="validateInput(this)" maxlength="50">
    </div>

    <div class="alert alert-danger err" role="alert" style="display: none;"></div>
    <div class="input-container">
    <i class="fa fa-mobile-alt icon" style="font-size:25px;"></i>
    <input class="input-field empty" type="text" placeholder="Mobile Phone Number" name="phone" onfocusout="validateInput(this)" maxlength="50">
    </div>
    <?php 
    try{
    $connection=new PDO("mysql:hostname=localhost;dbname=test","root","");
    $connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $stmt=$connection->prepare("SELECT parid,party_name_S,party_name_L,logo FROM parties ORDER BY party_name_S");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $queries=$stmt->fetchAll();
    }catch(PDOException $e){
      echo "Connection Failed ===> ".$e->getMessage();
    }
    $connection=null;
     ?>
    <div class="alert alert-danger err" role="alert" style="display: none;"></div>
    <div class="input-container">
    <i class="fa fa-handshake icon" style="font-size:25px;"></i>
    <select class="browser-default custom-select" id="party_id" name="party" style="height: 45px;" onfocusout="validateInput(this)">
    <option selected value="">Select Party</option>
    <option class='text-dark font-weight-bolder' value='0'>Non-Partisan</option>
    <?php
        foreach ($queries as $value) {
    ?>
    <option value="<?php echo $value['parid']; ?>"><?php echo $value['party_name_L']; ?></option>
    <?php
        }
    ?>
    </select></form>
    </div>

    <div class="input-container">
    <i class="fa fa-image icon" style="font-size:25px;height: 38px;"></i>
    <div class="custom-file">
  <input type="file" id="profile" name="image" class="custom-file-input empty" onchange="stateChange(this)" required=""> <label class="custom-file-label" for="validatedCustomFile">Upload your image</label>
  </div>
</div>   
<br>
      </div>
      <div class="col-3"></div>       
    </div>
    <!-- /next row button -->
    <div class="row">
      <div class="col"></div>
     <div class="col" style="padding-left: 100px;">
<label for="hiddenimg"><b style="margin-left: 50‒;padding-left: 50px;">Your Profile : </b></label><br>  <img src="" alt="" id="hiddenimg" style="width: 200px;height: 200px;"></div>
        <div class="col"></div>
     </div><br>
    <div class="row">
      <div class="col"></div>
     <div class="col" style="height: 75px;">
        <button name="submit" onclick="submitted()" id="btnSubmit" class="btn btn-success btn-lg btn-block" style="width: 300px;height: 50px;padding-bottom: 45px;margin-left: 50‒;margin-left: 5px;">Insert</button>
        <br><br>  
      </div>
      <div class="col"></div>
    </div>
      <div class="alert alert-warning" id="fempty" role="alert" style="display: none;text-align: center;"></div>
      <div class="alert alert-success" id="hid" role="alert" style="visibility: hidden; text-align: center;"></div>
  </div>
  <?php }else{ ?>
<div class="jumbotron jumbotron-fluid">
  <div class="container">
    <h1 class="display-4">ACCESS DENIED!!!</h1>
    <p class="lead">Pleae Log in as Admin to access this page.</p>
  </div>
</div>
<?php } ?>  
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
    function stateChange(input){
      if(input.files && input.files[0]){
        var reader=new FileReader();
        reader.onload=function(event){
    document.getElementById('hiddenimg').src=event.target.result;
        }
        reader.readAsDataURL(input.files[0]);
      }
    }
    function submitted(){
      var form=document.getElementById('form1');
      var formd=new FormData();
      var file=document.getElementById('profile').files[0];
      var isEmpty=false;
      var ajaz;
      for(var i=0;i<form.length;i++)
      {
        if(form.elements[i].value==="")
          isEmpty=true;
      }
      if(isEmpty) {
        var emp=document.getElementById('fempty');
        emp.style.display="block";
        emp.innerHTML="All Forms are mandatory.Can not left empty";
        return;
    }
      for(var i=0;i<form.length;i++)
      {
        formd.append(form.elements.item(i).name,form.elements.item(i).value);
      }
      formd.append("image",file);
      if(window.XMLHttpRequest){
        ajaz=new XMLHttpRequest();
      }else {ajaz=new ActiveXObject('Microsoft.XMLHTTP');}
      ajaz.onreadystatechange=function(){
      if(ajaz.readyState==4 && ajaz.status==200){
    document.getElementById('hid').innerHTML=this.responseText;
    document.getElementById('hid').style.visibility="visible";
    var empties=document.getElementsByClassName('empty');
    for (var i = 0; i < empties.length; i++) {
        empties[i].value = "";
    }
    document.getElementById('hiddenimg').src="";
    document.getElementById('reli').value="";
    document.getElementById('party_id').value="";
    window.setTimeout(function(){document.getElementById('hid').style.visibility="hidden";},3000);
      }}
      ajaz.open("POST","../DBProcessor.php",true);
      ajaz.send(formd);
    }
        var btndis=[false,false,false,false,false,false];
    function validateInput(obj){
        var should;
        var check=obj.value;
        var name=obj.name;
        var button=document.getElementById('btnSubmit');
        console.log("The input value is "+check+"\n");
        if(name=="cname"){     
  var pat= /^[^\s][A-Za-z\s\.\u1000-\u109F]+$/g;
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
    var pat=/^[\d\u1000-\u109F\+-]+$/g;
    btndis[4]=checker(6,pat,check);
  }
  if(name=="party"){
    if(check==""){
      respEmpty(7);
    }else{respSuccess(7);}
  }
  for(var k=0;k<btndis.length;k++){
    should=btndis[k];
    if(!should){
        break;
    }
  }
  if(should){
    button.disabled=false;
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
      console.log(button);
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
      button.disabled=true;
      console.log(button);
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
