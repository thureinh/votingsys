<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Register Election</title>

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
        <div class="container-fluid mt-2">

<h1>Election Registration</h1>
<form id="form1">
  <div class="form-group">
    <label for="input1">Election Name</label>
    <div class="alert alert-danger error-msg" role="alert" style="display: none;margin-bottom: 0px;">
    </div>
    <input type="text" name="ename" onfocusout="validateName(this)" class="form-control" id="input1" placeholder="Election Name" maxlength="100">
    <div class="invalid-feedback" style="display: none;">
        Please Provide Election Name.
    </div>
  </div>
  <div class="form-group">
    <label for="input2">Election Day</label>
    <div class="alert alert-danger error-msg" role="alert" style="display: none;margin-bottom: 0px;">
    </div>
    <input type="date" class="form-control" name="eday" onfocusout="validateDate(this)" id="input2" placeholder="Election Day">
    <div class="invalid-feedback" style="display: none;">
        Please Provide Election Day.
    </div>
  </div>
  <div class="form-group">
    <label for="input3">Time Interval (Year)</label>
    <div class="alert alert-danger error-msg" role="alert" style="display: none;margin-bottom: 0px;">
    </div>
    <input type="number" onfocusout="intervalOut(this)" name="einterval" class="form-control" id="input3" value="" placeholder="Time Interval" min="0">
  </div>
    <div class="row">
    <div class="col">
        <div class="form-group">
          <label for="input4">Begin Year</label>
          <div class="alert alert-danger error-msg" role="alert" style="display: none;margin-bottom: 0px;">
          </div>
          <input type="text" class="form-control" name="eBegin" id="input4" placeholder="Begin Year" list="begin_years" onkeyup="liftKey(this)" onfocusout="showEndYears(this)" maxlength="4">
          <div class="invalid-feedback" style="display: none;">
          Please  Provide Begin Year.
        </div>
          <datalist id="begin_years" >
        
      </datalist>
          </div>
    </div>
    <div class="col">
    <div class="col" style="padding-right: 0px;">
        <div class="form-group">
          <label for="input5">End Year (Left this field empty for one year election)*</label>
          <div class="alert alert-danger error-msg" role="alert" style="display: none;margin-bottom: 0px;">
          </div>
          <input type="text" class="form-control" id="input5" name="eEnd" list="end_years" placeholder="End Year" oninput="typeIn(this)" onkeyup="wasCleaned(this)" maxlength="4" disabled >
          <datalist id="end_years" >

          </datalist>
          </div>
    </div>     
    </div>
  </div>
  <div class="form-group">
    <label for="textarea">Remark</label>
    <div class="alert alert-danger error-msg" role="alert" style="display: none;margin-bottom: 0px;">
    </div>
    <textarea class="form-control" name="eRemark" id="textarea" rows="3" placeholder="Remark"></textarea>
    <input type="hidden" name="pid" value="2">
  </div>
</form>
  <button type="button" class="btn btn-info btn-lg btn-block" id="btnSubmit" onclick="insertElection()">Register Election</button>
  <div class="alert alert-success" id="responseMsg" role="alert" style="display: none">
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
  var faultyArray=[true,false,true,false,false];
  var btnSubmit=document.getElementById('btnSubmit');
  window.onload=function(){
    var yr_begin=document.getElementById('begin_years');
    var present=new Date().getFullYear();
    var default_begin=document.getElementById('input4');
    var past=present-119;
    var future=present+81;
    for (var i = past; i <= future; i++) {
      var option=document.createElement("option");
      option.value=i;
      option.appendChild(document.createTextNode(''+i));
      yr_begin.appendChild(option);
    }
    console.log("Present => "+present+" Past => "+past+" Future => "+future);
  }
  function wasCleaned(input) {
    console.log('keyup');
    console.log(input.value.length);
    if(input.value.length===0){
    var timeInterval=document.getElementById('input3');
    var errBox=document.getElementsByClassName('error-msg')[4];
        errBox.style.display="none";
        faultyArray[0]=true;
        availabilityControl();
        timeInterval.value=0;      
    }
  }
  var begin_year_for_typeIn=0;
  function typeIn(input){
    var input_value=input.value;
    var length=input_value.length;
    var present=new Date().getFullYear();
    var future=present+81;
    var timeInterval=document.getElementById('input3');
    var errBox=document.getElementsByClassName('error-msg')[4]; 

    console.log(length);
    if(isNaN(input.value)){
      errBox.innerHTML="<i class='fas fa-exclamation-circle'></i>&nbsp;Type Only Number Character";
      errBox.style.display="block";
      faultyArray[0]=false;
      btnSubmit.disabled=true;
      return;
    }
    else if(length>0 && length<4){
      errBox.innerHTML="<i class='fas fa-exclamation-circle'></i>&nbsp;Avaliable Year Range is ["+begin_year_for_typeIn+"~2100] ";
        errBox.style.display="block";
        faultyArray[0]=false;
        btnSubmit.disabled=true;
    }
    else if(length===4){
      if(parseInt(input_value) < begin_year_for_typeIn || parseInt(input_value) > future){
        errBox.innerHTML="<i class='fas fa-exclamation-circle'></i>&nbsp;Avaliable Year Range is ["+begin_year_for_typeIn+"~2100] ";
        errBox.style.display="block";
        faultyArray[0]=false;
        btnSubmit.disabled=true;
        return;
    }
    else
    {
        errBox.style.display="none";
        faultyArray[0]=true;
        availabilityControl();
        timeInterval.value=parseInt(input_value)-begin_year_for_typeIn;
    }
  }
    else{
      errBox.style.display="none";
      faultyArray[0]=true;
      availabilityControl();
    }

  }
    letItGo=true;
  function liftKey(input){
    var input_value=input.value;
    var len=input_value.length;
    var errBox=document.getElementsByClassName('error-msg')[3];
    if(isNaN(input.value)){
    errBox.innerHTML="<i class='fas fa-exclamation-circle'></i>&nbsp;Type Only Number Character";
      errBox.style.display="block";
      letItGo=false;
      btnSubmit.disabled=true;
      return;
    }
    else if(len===4){
      if(!letItGo) return;
      errBox.style.display="none";
      showEndYears(input);
    }
    else{
      letItGo=true;
      errBox.style.display="none";
    }
      
  }
  function availabilityControl(){

    var available=true;
    console.log("Faulty Array => ");
    console.log(faultyArray);
    for (var i = faultyArray.length - 1; i >= 0; i--) {
      if(faultyArray[i]==false)
      {
        available=false;
        break;
      }
    }
    if(available){
      btnSubmit.disabled=false;
    }
  }
  function showEndYears(input){
    var errBox=document.getElementsByClassName('error-msg')[3];
    var end_year_inputbox=document.getElementById('input5');
    var yr_end=document.getElementById('end_years');
    var time_interval=document.getElementById('input3');
    var invalidFeedbacks=document.getElementsByClassName('invalid-feedback');
    var begin_year=parseInt(input.value)+1;
    begin_year_for_typeIn=parseInt(input.value);
    if(!letItGo){return};
    if(!input.value){
      errBox.innerHTML="<i class='fas fa-exclamation-circle'></i>&nbsp;This Field Cannot Be Left Empty ";
      errBox.style.display="block";
      faultyArray[1]=false;
      btnSubmit.disabled=true;
      return;
    } 
    else if(input.value<1900 || input.value >2100){
      errBox.innerHTML="<i class='fas fa-exclamation-circle'></i>&nbsp;Avaliable Year Range is [1900~2100] ";
      errBox.style.display="block";
      faultyArray[1]=false;
      btnSubmit.disabled=true;
      return;
    }
    if(!time_interval.value){
    errBox.style.display="none";
    faultyArray[1]=true;
    invalidFeedbacks[2].style.display="none";
    document.getElementById('input4').classList.remove("is-invalid");
    availabilityControl();
    end_year_inputbox.disabled=false;
    var present=new Date().getFullYear();
    var future=present+81;
    console.log("Begin => "+begin_year+"future => "+future);
    for(var i = begin_year; i<=future;i++)
    {
      errBox.style.display="none";
      var newOption=document.createElement("option");
      newOption.value=i;
      newOption.appendChild(document.createTextNode(''+i));
      yr_end.appendChild(newOption);
    }
    }
    else
    {
      end_year_inputbox.disabled=false;
      var user_wanted=parseInt(time_interval.value)+begin_year;
      end_year_inputbox.value=user_wanted-1;
      faultyArray[1]=true;
      availabilityControl();
    }   
  }
  function insertElection() {
    var formitems=document.getElementById('form1').elements;
    var item=[];
    item[0]=document.getElementById('input1');
    item[1]=document.getElementById('input2');
    item[2]=document.getElementById('input4');
    var invalidFeedbacks=document.getElementsByClassName('invalid-feedback');
    var prevent=false;var count=0;
    var fdata=new FormData();
    for(var i=0;i<formitems.length;i++){
      console.log("formitems_"+i+" => "+formitems[i].value+" ");
      if(i==0 || i==1 || i==3)
      {
        if(!formitems[i].value.trim())
        {
          item[count].classList.add("is-invalid");
          invalidFeedbacks[count].style.display="block";  
          prevent=true;       
        }
        count++;
      }
    }
    if(prevent) return;
    for (var i = formitems.length - 1; i >= 0; i--) {
      fdata.append(formitems[i].name,formitems[i].value);
    }
    var xhr;
    if(window.XMLHttpRequest){
      xhr=new XMLHttpRequest();
    }
    else{
      xhr=new ActiveXObject('Micosoft.XMLHTTP');
    }
    xhr.onreadystatechange=function(){
      if(xhr.readyState==4 && xhr.status==200)
      {
        document.getElementById('responseMsg').innerText=this.responseText;
        document.getElementById('responseMsg').style.display="block";
        window.setTimeout(function(){document.getElementById('responseMsg').style.display="none";},5000);
        for (var i = formitems.length - 1; i >= 0; i--) 
        {
          formitems[i].value="";
        }
      }
    }
    xhr.open("POST","../transferdb.php",true);
    xhr.send(fdata);
  }
  function intervalOut(obj){
    var timeInterval=obj.value;
    var errBox=document.getElementsByClassName('error-msg')[2];
    if(isNaN(timeInterval)){
      errBox.innerHTML="<i class='fas fa-exclamation-circle'></i>&nbsp;Accept Only Number";
      errBox.style.display="block";
      faultyArray[2]=false;
      btnSubmit.disabled=true;
    }
    else if(timeInterval<0){
      errBox.innerHTML="<i class='fas fa-exclamation-circle'></i>&nbsp;Negative Values Are Not Allowed";
      errBox.style.display="block";
      faultyArray[2]=false;
      btnSubmit.disabled=true;
    }
    else{
      errBox.style.display="none";
      faultyArray[2]=true;
      availabilityControl();
    } 
  }
  function validateDate(obj){
    var dateval=obj.value;
    var errBox=document.getElementsByClassName('error-msg')[1];
    var invalidFeedbacks=document.getElementsByClassName('invalid-feedback');
    if(!dateval){
      errBox.innerHTML="<i class='fas fa-exclamation-circle'></i>&nbsp;This Field Cannot Be Left Unselected";
      errBox.style.display="block";
      faultyArray[3]=false;
      btnSubmit.disabled=true;
    }
    else
    {
      errBox.style.display="none";
      faultyArray[3]=true;
      invalidFeedbacks[1].style.display="none";
      document.getElementById('input2').classList.remove('is-invalid');
      availabilityControl();
    }
  }
  function validateName(obj){
    var nameval=obj.value;
    var errBox=document.getElementsByClassName('error-msg')[0];
    var pattern=/^[^\*\\\/=$^~%#]+$/g;
    var invalidFeedbacks=document.getElementsByClassName('invalid-feedback');
    if(!nameval){
      errBox.innerHTML="<i class='fas fa-exclamation-circle'></i>&nbsp;This Field Cannot Be Left Empty";
      errBox.style.display="block";
      faultyArray[4]=false;
      btnSubmit.disabled=true;
    }
    else if(!pattern.test(nameval)){
      errBox.innerHTML="<i class='fas fa-exclamation-circle'></i>&nbsp;Invalid Party Name Format";
      errBox.style.display="block";
      faultyArray[4]=false;
      btnSubmit.disabled=true;
    }
    else{
      errBox.style.display="none";
      faultyArray[4]=true;
      document.getElementById('input1').classList.remove('is-invalid');
      invalidFeedbacks[0].style.display="none";
      availabilityControl();
    }
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
