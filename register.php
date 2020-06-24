<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Register</title>
  </head>
  <body>
  <div class="container bg-light">
    <h1 class="my-4" style="text-align: center;vertical-align: middle;">Register</h1>
    <form id="form1" enctype="multipart/form-data">
    <input type="hidden" name="pid" value="1">
  <?php
    try{
    $dbconnect=new pdo('mysql:host=localhost;dbname=test','root','');
    $dbconnect->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $stmt=$dbconnect->prepare('SELECT elections.* FROM elections INNER JOIN candidateandelection ON elections.eid=candidateandelection.eid ORDER BY eid');
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $elections=$stmt->fetchAll();
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
    $dbconnect=null; 
  ?>
  <div class="form-group">
      <label for="election-select">Select One Election</label>
<div class="alert alert-danger err-msgr" style="display: none;margin-bottom:0px" role="alert"></div>
      <select class="form-control form-control-lg" name="election" id="election-select">
      <option value="" selected>Choose An Election</option>
  <?php
  $temp=0;
  foreach ($elections as $value) {
    if($value['eid']===$temp){
      continue;
    }
    $temp=$value['eid'];
    $date=DateTime::createFromFormat('Y-m-d',$value['edate']);
    $eday=$date->format('jS M Y');
  ?>
<option value="<?php echo $value['eid']; ?>">&nbsp;Name : <?php echo $value['ename']; ?> , <?php if(!empty($value['eyear_end'])) { ?>[ From <?php echo $value['eyear_begin']; ?> ~ To <?php echo  $value['eyear_end']; ?> ]<?php }else { ?>[ <?php echo $value['eyear_begin']; ?> ]<?php } ?> , Election Day : <?php echo $eday ?>
</option>
  <?php  
    }
  ?>
      </select>
  </div>
  <label>Select One Of these Constituencies </label>
<div class="alert alert-danger err-msgr" style="display: none;margin-bottom:0px" role="alert"></div>
  <div class="form-group border radio-consti" style="height: 150px;overflow-y: auto;">
<!--     
    <div class="custom-control custom-radio my-2 mx-3">
    <input type="radio" class="custom-control-input" id="customControlValidation1" name="radio-con">
    <label class="custom-control-label" for="customControlValidation1">Toggle this custom radio</label>
    </div> 
-->
  </div>
  <div class="form-group">
    <label for="exampleInputName1">Name</label>
<div class="alert alert-danger err-msgr" style="display: none;margin-bottom:0px" role="alert"></div>
    <input type="text" name="name" class="form-control" id="exampleInputName1" placeholder="Enter Name">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">NRC</label>
<div class="alert alert-danger err-msgr" style="display: none;margin-bottom:0px" role="alert"></div>
    <input type="text" name="nrc" class="form-control" id="exampleInputEmail1" placeholder="Enter NRC Number">
  </div>
  <div class="form-group row">
      <div class="col">
      <div class="custom-file">
      <input type="file" name="front-image" class="custom-file-input" id="customFile1">
      <label class="custom-file-label" for="customFile">Upload Front Image Of NRC</label>
      </div>
      <div class="d-flex justify-content-center">
          <img id="front-img" class="rounded m-2" src="" style="width: 500px;height: 300px;display: none;">
      </div>
      </div>
      <div class="col">
      <div class="custom-file">
      <input type="file" name="back-image" class="custom-file-input" id="customFile2">
      <label class="custom-file-label" for="customFile">Upload Back Image Of NRC</label>
      </div>
      <div class="d-flex justify-content-center">
          <img id="back-img" class="rounded m-2" src="" style="width: 500px;height: 300px;display: none;">
      </div>
      </div>
  </div>
  <div class="row my-2">
    <div class="col">
        <button type="submit" class="btn btn-primary btn-lg btn-block">Submit</button>
    </div>
    <div class="col">
        <button type="button" class="btn btn-primary btn-lg btn-block" onclick="location.replace('index.php');">Cancel</button>
    </div>
  </div>
</form>
<div class="alert alert-success" id="success-msgr" role="alert" style="display: none;"></div>
</div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="js/jquery-3.4.1.js"></script>
    <script>
        $(document).ready(function(){
          console.log('JQuery Loaded');
          $('#election-select').on('change',function(e){
                var eid=$(e.target).val();
                console.log(eid);
                $.ajax({
                  'url' : 'outerProcessor.php?pid=1&eid='+eid,
                  'type' : 'GET',
                  'contentType' : 'application/json;charset=utf-8',
                  'dataType' : 'text',
                  'success' : function(result){
                    $('#form1 .radio-consti').empty();
                    var constituencies=JSON.parse(result);
                    var temp_id=0;
                    $.each(constituencies,function(index,value){
                    if(temp_id==value['conid']){
                      return;
                    }
                    temp_id=value['conid'];
                    let newRadio='<div class="custom-control custom-radio my-2 mx-3"><input type="radio" class="custom-control-input" id="customControlValidation'+index+'" name="radio-con" value="'+value['conid']+'" ><label class="custom-control-label" for="customControlValidation'+index+'"> Name : '+value['con_name']+' <strong>,</strong> Township : '+value['township']+' </label></div>';
                    $('#form1 .radio-consti').append(newRadio);
                    })
                  }
                })
          })
          $('#customFile1').on('change',function(e){
              if(this.files && this.files[0]){
                 var reader=new FileReader();
                 reader.onload=function(eve){
                    $('#front-img').attr('src',eve.target.result).slideDown('slow');
                 }
                 reader.readAsDataURL(this.files[0]);
              }
          })
          $('#customFile2').on('change',function(e){
            if(this.files && this.files[0]){
              var reader =new FileReader();
              reader.onload=function(eve){
                $('#back-img').attr('src',eve.target.result).slideDown('slow');
              }
              reader.readAsDataURL(this.files[0]);
            }
          })
          $("#form1 input[type='text']").on( 'focusout', function(event){
              var current_el = $(event.target);
              console.log(current_el.siblings());
              if(!current_el.val().trim()){
                current_el.siblings('div.err-msgr').text('This Field Cannot Be Left Empty').show();
              }
              else
              {
                current_el.siblings('div.err-msgr').text('This Field Cannot Be Left Empty').hide();
              }
          });
          $('#form1').on('submit',function(eve){
              console.log('Form Submitted');
              eve.preventDefault();
              var form_elements=document.getElementById('form1').elements;
              var fmd=new FormData();
              var prevent=false;
              $.each(form_elements,function(key,value){
              if($(value).is(':button,input[type=file],input[type=radio]')){
                return;
              }
              fmd.append(value.name,value.value);             
              if(!$(value).val().trim()){
                  $(value).closest('div').find('.err-msgr').text('This Field Cannot Be Left Empty').show();
                  prevent=true;
              }
              })
              fmd.append( 'radio-con', $('input[type=radio]:checked').val());
              if(prevent) return;
              var file1=document.getElementById('customFile1');
              var file2=document.getElementById('customFile2');
              fmd.append(file1.name,file1.files[0]);
              fmd.append(file2.name,file2.files[0]);
              var xhr;
              if(window.XMLHttpRequest){
                  xhr=new XMLHttpRequest();
              }
              else
              {
                xhr=new ActiveXObject('Microsoft.XMLHTTP');
              }
              xhr.onreadystatechange=function(){
                if(xhr.readyState==4 && xhr.status==200){
                  $('#success-msgr').text(this.responseText).show();
                  setTimeout(function(){$('#success-msgr').hide()},3000);
                  $('#form1 input[type=file],#form1 input[type=text]').val('');
                  $('#front-img,#back-img').hide();
                }
              }
              xhr.open('POST','outerProcessor.php');
              xhr.send(fmd);
          })
        })
    </script>
  </body>
</html>