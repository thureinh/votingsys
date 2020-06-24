<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Admin Dashboard</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <!-- Custom styles for this page -->
  <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.1.0/css/flag-icon.min.css" rel="stylesheet">
  <style type="text/css">
    .checkbox-area { border:2px solid #ccc;height: 200px; overflow-y: scroll;}
    td.details-control {
    background: url('../uploads/plus-restricted.png') no-repeat center center;
    cursor: pointer;
    background-size: 20px;
    }
    tr.details td.details-control {
    background: url('../uploads/minus-restricted.png') no-repeat center center;
    background-size: 20px;
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
<?php
    try{     
      $conn=new PDO('mysql:host=localhost;dbname=test','root','');
      $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
      $stmt=$conn->prepare('SELECT parid,logo,party_name_L FROM parties');
      $stmt->execute();
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      $parties=$stmt->fetchAll();
      $stmt=$conn->prepare('SELECT cid,name,image FROM candidates');
      $stmt->execute();
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      $candidates=$stmt->fetchAll();
      $stmt=$conn->prepare('SELECT eid,ename,edate,eyear_begin,eyear_end FROM elections');
      $stmt->execute();
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      $elections=$stmt->fetchAll();
      $stmt=$conn->prepare('SELECT conid,con_name,township FROM constituency');
      $stmt->execute();
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      $constituency=$stmt->fetchAll();
    }catch(PDOException $e){
      $e->getMessage();
    }
    $conn=null;
 ?>
        <!-- Begin Page Content -->
        <div class="container-fluid my-4">

<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Assign An Election</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Show Complete Election Table</a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

<h1>Assign an Election</h1>

<form id='form1' class="mt-4">
    <div class="form-group row my-4">
    <div class="col-2">
      <label for="electionSelect" class="font-weight-bolder" style="margin-top: 11px;">Select Election  : </label>
    </div>
      <div class="col-10">
        <select class="form-control form-control-lg" name='election' id='electionSelect' required >
        <option value="" selected >Select One Of The Following Elections</option>
  <?php
  foreach ($elections as $value) {
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
  </div>
  <div class="form-group row my-4">
    <div class="col">
    <label class="font-weight-bolder">Select Constituency : </label>
<div class="alert alert-danger" style="margin-bottom: 0px;display: none;" role="alert" ></div>
<div class="checkbox-area">
<div class="form-check">
  <?php
  $count=0;
  foreach ($constituency as $value) {
  ++$count;
  ?>
    <div class="custom-control custom-radio mb-3 <?php if($count==1) echo 'mt-2'; ?>">
    <input type="radio" name="constituency" value="<?php echo $value['conid']; ?>" class="custom-control-input" id="customControlValidation<?php echo $count; ?>" required >
    <label class="custom-control-label" for="customControlValidation<?php echo $count; ?>">
       <b>Name</b> : <?php echo $value['con_name']; ?> , <b>Township</b> : <?php echo $value['township']; ?>
    </label>
  </div>
  <?php
  }
  ?>
</div>
  </div>
  </div>
  </div>
  <div class="row" id='err-msg-row' style="margin-bottom: 0px;">
    <div class="col" style="margin-bottom: 0px;">
      <div class="alert alert-danger" style="margin-bottom: 0px;display: none;" role="alert">
</div>
    </div>
    <div class="col" style="margin-bottom: 0px">
      <div class="alert alert-danger" style="margin-bottom: 0px;display: none;" role="alert">
</div>
    </div>
  </div>
  <div class="form-group row mt-0 mb-4">
        <div class="col-6">
    <label for="dpd-priBtn1" style="text-align: center;" class="col-form-label"><b>Select Party : </b></label>
<div class="btn-group dropup" id="btn-group1">
  <button type="button" style="width: 400px;height: 60px" class="btn btn-primary border dropdown-toggle" id="dpd-priBtn1" data-toggle="dropdown" value="" data-display="static" aria-haspopup="true" aria-expanded="false" disabled >
    Click Here To See Parties
  </button>
  <div class="dropdown-menu dropdown-menu-lg-right" style="max-height: 300px;overflow-y: auto;">
      <?php 
      if(empty($parties)){
      ?>
<button class="dropdown-item border font-weight-bolder" type="button" style="width: 400px;font-size:20px;" disabled ><img class="rounded" src="../uploads/not-found-restricted.png" style="width: 50px;height: 50px;">&nbsp;No Record Found!</button>
  <?php }else{ ?>
<button class="dropdown-item border" type="button" value="0" style="width: 400px"><img class="rounded" src='../uploads/nonpartisan-restricted.png' style="width: 50px;height: 50px">&nbsp;Non-Partisan</button>
  <?php
  foreach ($parties as $value) {
    ?>
    <button class="dropdown-item border" type="button" value="<?php echo $value['parid']; ?>" style="width: 400px"><img class="rounded" src="../<?php echo $value['logo']; ?>" style="width: 50px;height: 50px">&nbsp;<?php echo $value['party_name_L']; ?></button>
    <?php } }?>
  </div>
</div>
<img src="../uploads/cross-restricted.png" id='hidden-cross1' style="width: 25px;height: 25px;display: none;" class="rounded ml-2" data-toggle="tooltip" data-placement="bottom" title="Clear Selection" alt="wait">
    </div>
  <div class="col-6">
    <label for="select-Candidate" class="col-form-label"><b>Select Candidate : </b></label>
<div class="btn-group dropup" id="btn-group2">
  <button type="button" style="width: 400px;height: 60px" class="btn btn-primary border dropdown-toggle" id='dpd-priBtn2' value="" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false" disabled >
    Click Here To See Candidates
  </button>
  <div class="dropdown-menu dropdown-menu-lg-right" style="max-height: 300px;overflow-y: auto">
    <?php 
    if(empty($candidates)){
      ?>
      <button class="dropdown-item border font-weight-bolder" type="button" style="width: 400px;font-size:20px;" disabled ><img class="rounded" src="../uploads/not-found-restricted.png" style="width: 50px;height: 50px;">&nbsp;No Record Found!</button>
    <?php
    }else{
    foreach ($candidates as $value) {
      ?>
    <button class="dropdown-item border" value="<?php echo $value['cid']; ?>" type='button' style="width: 400px;" ><img style="width: 50px;height: 50px" class="rounded" src='../<?php echo $value['image']; ?>'>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $value['name']; ?></button>
   <?php
    } }
    ?>
  </div>
</div>
<img src="../uploads/cross-restricted.png" id='hidden-cross2' style="width: 25px;height: 25px;display: none;" class="rounded ml-2" data-toggle="tooltip" data-placement="bottom" title="Clear Selection" alt="wait">
    </div>
  </div>
  <div class="form-group row my-4">
    <div class="col">
    <div class="d-flex justify-content-center">
    <button type="button" id='createBtn' class="btn btn-primary btn-lg">Assign Election</button>
  </div>
  </div>
  </div>
</form>
 <div class="alert alert-success" id='success-msg' style="margin-bottom: 0px;display: none;" role="alert">
 </div>

  </div>
  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Complete Election Table</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
               <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th></th>
                      <th></th>
                      <th>Election Name</th>
                      <th>Constituency</th>
                      <th style="text-align: center;vertical-align: middle;">Profile</th>
                      <th>Candidate Name</th>
                      <th>Candidate Party</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th></th>
                      <th></th>
                      <th>Election Name</th>
                      <th>Constituency</th>
                      <th style="text-align: center;vertical-align: middle;">Profile</th>
                      <th>Candidate Name</th>
                      <th>Candidate Party</th>
                      <th>Actions</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    
                  </tbody>
                </table>
              </div>
            </div>
          </div>

  </div>

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

<!-- Delete Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
      <div class="modal-dialog">
    <div class="modal-content">
          <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title custom_align" id="Heading">Delete this entry</h4>
      </div>
          <div class="modal-body">
       
       <div class="alert alert-danger">Are you sure you want to delete this record? <br><i class="fa fa-exclamation-circle"></i>&nbsp;This will delete all records related to this.</div>
       
      </div>
        <div class="modal-footer ">
        <a href="#" class="btn btn-success btnDelete" data-dismiss="modal"><i class="fas fa-check-circle"></i>Yes</a>
        <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fas fa-window-close"></i>No</button>
      </div>
        </div>
  </div> 
    </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

<script>
  var faultyArray=[false,false];
  var mainz_Election,mainz_Constituency,mainz_Object,completedObj;
  $(document).ready(function(){
    var dt=$('#dataTable').DataTable({
                    'columnDefs' : [
                    {
                      'targets':[0],
                      'visible': false,
                      'searchable':false
                    },
                    {
                      'orderable':false,
                      'targets': [1],
                      'class' : 'details-control',
                      'data' : null,
                      'defaultContent' : '',
                    },
                    {
                      'targets' : [4],
                      'searchable' : false,
                      'orderable' : false,
                      'width' : '10%',
                    },
                    {
                      'targets' : [-1],
                      'searchable' : false,
                      'orderable' : false,
                    }
                    ]
                  });
    $('#profile-tab').on('click',function(e){
        console.log('Profile Tab');  
        $.ajax({
        'url' : '../transferDB.php?pid=12',
        'type' : 'GET',
        'contentType' : 'application/json;charset=UTF-8',
        'dataType' : 'text',
        'success' : function (result){
          completedObj=JSON.parse(result);
          let btnDelete='<button type="button" class="btn btn-lg btn-danger" data-toggle="modal" data-target="#deleteModal">Delete</button>';
          if($.trim(completedObj)){
              dt.destroy();         
              $('#dataTable tbody').empty();
              $.each(completedObj,function(key,value){
                    let newRow='<tr id='+value['ceid']+'><td></td><td></td><td>'+value['ename']+'</td><td>'+value['township']+'</td><td>'+'<div class="d-flex justify-content-center"><img src="../'+value['image']+'" class="rounded" style="width:60px;height:60px"></div></td><td>'+value['name']+'</td><td>'+(value['party_name_S'] ? value['party_name_S'] : '<b>None</b>')+'</td><td>'+btnDelete+'</td></tr>';
                    $('#dataTable tbody').append(newRow);
              })
              console.log('SET orderable');
                  dt=$('#dataTable').DataTable({
                    'columnDefs' : [
                    {
                      'targets':[0],
                      'visible':false,
                      'searchable':false,
                    },
                    {
                      'orderable':false,
                      'targets': [1],
                      'class' : 'details-control',
                      'data' : null,
                      'defaultContent' : '',
                    },
                    {
                      'targets' : [4],
                      'searchable' : false,
                      'orderable' : false,
                      'width' : '10%',
                    },
                    {
                      'targets':[-1],
                      'searchable':false,
                      'orderable':false,
                    }
                    ]
                  });
          }
          else{
            dt.clear().draw();
          }
        }
      })

    })
    $('#dataTable tbody').on( 'click', 'tr td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = dt.row( tr );
        if ( row.child.isShown() ) {
            tr.removeClass( 'details' );
            row.child.hide();
        }
        else {
            tr.addClass( 'details' );
            row.child( format(tr.attr('id'),completedObj) ).show();
        }
      })
    $('#deleteModal').on('show.bs.modal',function(e){
      GB_delID=$(e.relatedTarget).closest('tr').attr('id');
      console.log(GB_delID);
    })
    $('#deleteModal').on('click','a.btnDelete',function(e){
       var delID=GB_delID;
       $.ajax({
        'url' : '../dbprocessor.php?pid=6&delid='+delID,
        'type' : 'GET',
        'contentType' : 'application/json;charset=UTF-8',
        'dataType' : 'text',
        'success' : function(){
        $.ajax({
        'url' : '../transferDB.php?pid=12',
        'type' : 'GET',
        'contentType' : 'application/json;charset=UTF-8',
        'dataType' : 'text',
        'success' : function (result){
          completedObj=JSON.parse(result);
          var btnEdit='<button type="button" class="btn btn-warning btnEdit">Edit</button>';
          var btnDelete='<button type="button" class="btn btn-lg btn-danger" data-toggle="modal" data-target="#deleteModal">Delete</button>';
          if($.trim(completedObj)){
              dt.destroy();      
              $('#dataTable tbody').empty();   
              $.each(completedObj,function(key,value){
                  let newRow='<tr id='+value['ceid']+'><td></td><td></td><td>'+value['ename']+'</td><td>'+value['township']+'</td><td>'+'<div class="d-flex justify-content-center"><img src="../'+value['image']+'" class="rounded" style="width:60px;height:60px"></div></td><td>'+value['name']+'</td><td>'+(value['party_name_S'] ? value['party_name_S'] : '<b>None</b>')+'</td><td>'+btnDelete+'</td></tr>';
                    $('#dataTable tbody').append(newRow);
              })
                  dt=$('#dataTable').DataTable({
                    'columnDefs' : [
                    {
                      'targets' : [0],
                      'visible' : false,
                      'searchable':false,
                    },
                    {
                      'targets' : [4],
                      'searchable' : false,
                      'orderable' : false,
                      'width' : '10%',
                    },
                    {
                      'targets': [1],
                      'class' : 'details-control',
                      'data' : null,
                      'searchable':false,
                      'orderable':false,
                      'defaultContent' : '',
                    },
                    {
                      'targets':[-1],
                      'searchable':false,
                      'orderable':false,
                    }
                    ]
                  });
          }
          else{
            dt.clear().draw();
          }
        }
      })

        }
       })
    })
    $('#btn-group1 .dropdown-menu').on('click','.dropdown-item',function(e){
      console.log('Party Button');
     $('#err-msg-row').find('div.alert').eq(0).text('This Field Cannot Be Left Unselected').hide();
     var id=$(e.target).val();
     $('#dpd-priBtn1').removeClass('btn-primary').addClass('border border-dark').html($(e.target).html()).val(id);
     $('#hidden-cross1').show();
            console.log(mainz_Object);
            var canObj=JSON.parse(mainz_Object);
            console.log(canObj);
            if(!$.trim(canObj)){
              var newBtn='<button class="dropdown-item border font-weight-bolder" type="button" style="width: 400px;font-size:20px;" disabled ><img class="rounded" src="../uploads/not-found-restricted.png" style="width: 50px;height: 50px;">&nbsp;No Record Found!</button>';
              $('#btn-group2 .dropdown-menu').html(newBtn);
            }
            else
              { 
                  $('#btn-group2 .dropdown-menu').empty();
                  if(id==0)
                  {
                    $.each(canObj,function(key,value){
                      if(value['parid']==null){
                      let newBtn='<button class="dropdown-item border" type="button" value="'+value['cid']+'" style="width:400px;"><img class="rounded" src="../'+value['image']+'" style="width:50px;height:50px;">&nbsp;&nbsp;&nbsp;&nbsp;'+value['name']+'</button>';
                      $('#btn-group2 .dropdown-menu').append(newBtn);
                      }
                    })
                  }
                  else
                  {
                  $.each(canObj,function(key,value){
                    if(value['parid']==id){
                    let newBtn='<button class="dropdown-item border" type="button" value="'+value['cid']+'" style="width:400px;"><img class="rounded" src="../'+value['image']+'" style="width:50px;height:50px;">&nbsp;&nbsp;&nbsp;&nbsp;'+value['name']+'</button>';
                  $('#btn-group2 .dropdown-menu').append(newBtn);
                   }
                  })
                }
              }

    })
    $('#home-tab').on('click',function(e){
        faultyChecker();
    })
    $('#hidden-cross1').on('click',function(e){
        crossAction();
    })
    $('#hidden-cross2').on('click',function(e){
        crossAction();
    })
    $('#form1').find('input[type=radio]').on('change',function(e){
      $('.form-group').eq(1).find('div.alert').hide();
    })
    $('#electionSelect').on('change',function(e){
      var optionValue=$('#electionSelect option:selected').val();
      if(!$.trim(optionValue)){
        faultyArray[0]=false;
      }
      else
      { 
        mainz_Election=optionValue;
        faultyArray[0]=true;
        faultyChecker();
      }
    })
    $('input[type=radio]').on('click',function(event){
      var radioValue=$('input[name=constituency]:checked').val();
      if(!$.trim(radioValue)){
        faultyArray[1]=false;
      }
      else{
        faultyArray[1]=true;
        mainz_Constituency=radioValue;
        faultyChecker();
      }
    })
    $('#createBtn').on('click',function(event){
        event.preventDefault();
        var chosenParty=$('#dpd-priBtn1').val();
        var chosenCandidate=$('#dpd-priBtn2').val();
        var chosenConstituency=$('#form1').find("input[name='constituency']:checked");
        var chosenElection=$('#form1').find('[name=election] option:selected').val();
        var formShutDown=false;
        if(!$.trim(chosenConstituency.val())){
         $('.form-group').eq(1).find('div.alert').text('Select One Of The Following Constituency').show();
         formShutDown=true;
        }
        if(!$.trim(chosenCandidate)){
          $('#err-msg-row').find('div.alert').eq(1).text('This Field Cannot Be Left Unselected').show();
          formShutDown=true;
        }
        if(!$.trim(chosenParty)){
            $('#err-msg-row').find('div.alert').eq(0).text('This Field Cannot Be Left Unselected').show();
            formShutDown=true;
        }
        if(!$.trim(chosenElection)){
          formShutDown=true;
        }
        if(formShutDown){
          $('#form1').addClass('was-validated');
        }
        else {
          var objArr={'chosenParty' : chosenParty,'chosenCandidate' : chosenCandidate,'chosenConstituency' : chosenConstituency.val(),'chosenElection':chosenElection};
          objArr=JSON.stringify(objArr);
          $.ajax({
            'url' : '../transferDB.php',
            'type' : 'POST',
            'contentType' : 'application/x-www-form-urlencoded;charset=utf-8',
            'dataType' : 'text',
            'data' : {'pid' : 6,'json':objArr},
            'success' : function(result){
             $('#success-msg').text(result).show();
             setTimeout(function(){$('#success-msg').hide()},5000);  
            let newToggle1='<button type="button" style="width: 400px;height: 60px" class="btn btn-primary border dropdown-toggle" id="dpd-priBtn1" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false">Click Here To See Parties</button>';
            let newToggle2='<button type="button" style="width: 400px;height: 60px" class="btn btn-primary border dropdown-toggle" id="dpd-priBtn2" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false">Click Here To See Candidates</button>';
            $('#dpd-priBtn1').replaceWith(newToggle1);
            $('#dpd-priBtn2').replaceWith(newToggle2);
            $('#hidden-cross1,#hidden-cross2').hide();
            faultyChecker();
            }
          })
        }
    })
    $('#btn-group2 .dropdown-menu').on('click','.dropdown-item',function(e){
      $('#err-msg-row').find('div.alert').eq(1).hide();
      $('#err-msg-row').find('div.alert').eq(0).hide();
      console.log(e.target);
      console.log($(e.target).html());
      var id=$(e.target).val();
     $('#dpd-priBtn2').removeClass('btn-primary').addClass('border border-dark').html($(e.target).html()).val(id);
     $('#hidden-cross2').show();
          var parObj=JSON.parse(mainz_Object);
          if(!$.trim(parObj)){
             $('#dpd-priBtn1').removeClass('btn-primary').addClass('border border-dark').val('0'); 
             var noneBtn='<img class="rounded" src="../uploads/nonpartisan-restricted.png" style="width:50px;height:50px;">&nbsp;Non-Partisan';
             $('#dpd-priBtn1').html(noneBtn).attr('disabled',true);
             return;        
          }
          $.each(parObj,function(key,value){
             if(value['cid']==id){
             $('#dpd-priBtn1').removeClass('btn-primary').addClass('border border-dark').val(value['parid'] ? value['parid'] : '0');
             var newBtn='<img class="rounded" src="../'+(value['logo'] ? value['logo'] : 'uploads/nonpartisan-restricted.png')+'" style="width: 50px;height: 50px;">&nbsp;'+(value['party_name_L'] ? value['party_name_L'] : 'Non-Partisan');
             $('#dpd-priBtn1').html(newBtn).attr('disabled',true);
           }
          })
    })
  }) 
  function crossAction(){
    console.log('Cross Action');
    $('#hidden-cross1,#hidden-cross2').hide();
    var newToggle1='<button type="button" style="width: 400px;height: 60px" class="btn btn-primary border dropdown-toggle" id="dpd-priBtn1" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false">Click Here To See Parties</button>';
    var newToggle2='<button type="button" style="width: 400px;height: 60px" class="btn btn-primary border dropdown-toggle" id="dpd-priBtn2" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false">Click Here To See Candidates</button>';
    $('#dpd-priBtn1').replaceWith(newToggle1);
    $('#dpd-priBtn2').replaceWith(newToggle2);
    var notFound='<button class="dropdown-item border font-weight-bolder" type="button" style="width: 400px;font-size:20px;" disabled ><img class="rounded" src="../uploads/not-found-restricted.png" style="width: 50px;height: 50px;">&nbsp;No Record Found!</button>';
    var crossObj=JSON.parse(mainz_Object);
    if(!$.trim(crossObj)){
          $('#btn-group1 .dropdown-menu,#btn-group2 .dropdown-menu').html(notFound);
    }
    else{
          $('#btn-group1 .dropdown-menu,#btn-group2 .dropdown-menu').empty();
          var temp_store1=[];var temp_store2=[];
          $.each(crossObj,function(key,value){

            let party_dropdown='<button class="dropdown-item border" type="button" value="'+(value['parid'] ? value['parid'] : '0')+'" style="width: 400px"><img class="rounded" src="../'+(value['logo'] ? value['logo'] : 'uploads/nonpartisan-restricted.png')+'" style="width: 50px;height: 50px">&nbsp;'+(value['party_name_L'] ? value['party_name_L'] : 'Non-Partisan' )+'</button>';  

            let candidate_dropdown='<button class="dropdown-item border" value="'+value['cid']+'" type="button" style="width: 400px;" ><img style="width: 50px;height: 50px" class="rounded" src="../'+value['image']+'">&nbsp;&nbsp;&nbsp;&nbsp;'+value['name']+'</button>';
              if(!is_Same(temp_store1,value['cid']))
              {
                  temp_store1.push(value['cid']);
                  $('#btn-group2 .dropdown-menu').append(candidate_dropdown); 
              }
              if(!is_Same(temp_store2,value['parid']))
              {
                  temp_store2.push(value['parid']);
                  $('#btn-group1 .dropdown-menu').append(party_dropdown); 
              }       
          })
    }
    console.log('Cross Action Complete');
  }   
  function faultyChecker(){
    console.log('Faulty-Checker');
    var checker=true;
    $.each(faultyArray,function(index,value){
      if(value==false){
        checker=false;
        return false;
      }
    })
    if(checker){
      $.ajax({
        'url' : '../transferDB.php?pid=10&e='+mainz_Election+'&c='+mainz_Constituency,
        'type' : 'GET',
        'contentType' : 'application/x-www-form-urlencoded',
        'dataType' : 'text',
        'success' : function(result){
          mainz_Object=result;
          $('#dpd-priBtn1,#dpd-priBtn2').attr('disabled',false);
          var queryResults=JSON.parse(result);
          console.log(queryResults);
          if($.trim(queryResults)){
              $('#btn-group1 .dropdown-menu').empty();
              $('#btn-group2 .dropdown-menu').empty();
              var temp_store1=[];var temp_store2=[];
              $.each(queryResults,function(key,value){
              let newBtn1='<button class="dropdown-item border" value="'+value['cid']+'" type="button" style="width: 400px;" ><img style="width: 50px;height: 50px" class="rounded" src="../'+value['image']+'">&nbsp;&nbsp;&nbsp;&nbsp;'+value['name']+'</button>';
              let newBtn2='<button class="dropdown-item border" type="button" value="'+(value['parid'] ? value['parid'] : '0')+'" style="width: 400px"><img class="rounded" src="../'+(value['logo'] ? value['logo'] : 'uploads/nonpartisan-restricted.png')+'" style="width: 50px;height: 50px">&nbsp;'+(value['party_name_L'] ? value['party_name_L'] : 'Non-Partisan')+'</button>';
              if(!is_Same(temp_store1,value['cid']))
              {
                  temp_store1.push(value['cid']);
                  $('#btn-group2 .dropdown-menu').append(newBtn1);
              }
              if(!is_Same(temp_store2,value['parid']))
              {
                  temp_store2.push(value['parid']);
                  $('#btn-group1 .dropdown-menu').append(newBtn2);
              }
              })
          }
          else{
              var newBtn='<button class="dropdown-item border font-weight-bolder" type="button" style="width: 400px;font-size:20px;" disabled ><img class="rounded" src="../uploads/not-found-restricted.png" style="width: 50px;height: 50px;">&nbsp;No Record Found!</button>';
              $('#btn-group1 .dropdown-menu').empty().append(newBtn);
              $('#btn-group2 .dropdown-menu').empty().append(newBtn);            
          }
        }
      })
    }
  }
  function is_Same(arr,data){
    var same=false;
    if(arr.length==0){
      return false;
    }
    else{
        $.each(arr,function(index,value){
            if(value==data){
                same=true;
                return false;
            }
        })
        return same;
    }
  }
function format (index,data) {
  var extended;
    $.each(data,function(key,value){
      if(value['ceid']==index){
        extended='<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
        '<tr><td>Election Day : </td><td>'+value['edate']+'</td></tr>'+
        '<tr><td>Candidate NRC : </td><td>'+value['nrc']+'</td></tr>'+
        '<tr><td>Full Party Name : </td><td>'+(value['party_name_L'] ? value['party_name_L'] : '<b>None</b>')+'</td></tr>'+
        '</table>';
      }
    })  
    return extended;  
}

</script>
</body>

</html>
