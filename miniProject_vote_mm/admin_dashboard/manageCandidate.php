<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Manage Candidate</title>

  <!-- Custom fonts for this template -->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.1.0/css/flag-icon.min.css" rel="stylesheet">
  <!-- Custom styles for this template -->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <style type="text/css">
td.details-control {
    background: url('img/add_icon.png') no-repeat center center;
    background-size: 40px;
    cursor: pointer;
    }
tr.details td.details-control {
    background: url('img/minus_icon.png') no-repeat center center;
    background-size: 34px;
    }
  </style>
</head>

<body id="page-top">

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
          <h1 class="h3 mb-2 text-gray-800 mt-3">Candidates Table</h1>

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Manage Candidates</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable1" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th></th>
                      <th>Profile</th>
                      <th>Name</th>
                      <th>Party</th>                      
                      <th>NRC</th>
                      <th>Nationality</th>
                      <th>Religion</th>
                      <th>Edit</th>
                      <th>Delete</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th></th>
                      <th>Profile</th>
                      <th>Name</th>
                      <th>Party</th>                      
                      <th>NRC</th>
                      <th>Nationality</th>
                      <th>Religion</th>
                      <th>Edit</th>
                      <th>Delete</th>
                    </tr>
                  </tfoot>
                  <tbody>
                  </tbody>
                </table>
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

 <!--  delete modal -->
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
        <a href="#" class="btn btn-success editor_remove" data-dismiss="modal"><i class="fas fa-check-circle"></i>Yes</a>
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


  <!-- Page level custom scripts -->
  <script src="js/demo/datatables-demo.js"></script>
  <script type="text/javascript">
    var dTable,global_data;
    function format (index,data) {
      var data=JSON.parse(data);
      var hiddenRow;
      $.each(data,function(inx,data){
        if(data['cid']==index){
        hiddenRow= '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
        '<tr>'+
            '<td>Email :</td>'+
            '<td>'+data['email']+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td>Address :</td>'+
            '<td>'+data['address']+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td>Phone Number:</td>'+
            '<td>'+data['phone_no']+'</td>'+
        '</tr>'+
        '</table>';  
        return false;
        }
      })
     return hiddenRow; 
   }
   function getTable(){
      console.log('getting table...');
      if(!dTable){
      dTable=$('#dataTable1').DataTable({
        'order' : [[2,'asc']],
        'columnDefs':[{
          'targets': [1,-1,-2],
          'orderable':false,
          'searchable':false,
        },
        {
          'targets':[0],
          'orderable':false,
          'searchable':false,
          'class':'details-control',
          'data':null,
          'defaultContent':'',
        }
        ]
      });
      }
      $.ajax({
      'url' : 'dataforDataTable1.php',
      'type' : 'POST',
      'contentType' : 'application/json;charset=utf-8',
      'dataType' : 'text',
      'success' : function(data){
          console.log(data);
          global_data=data;
          var cpObj=JSON.parse(data);
          if(!$.trim(cpObj)){
            console.log('EMPTY');
            dTable.clear().draw();
          }
          else{
          console.log('NOT EMPTY');
          dTable.destroy();
          $('#dataTable1 tbody').empty();
          $.each(cpObj,function(key,value){
          let newRow='<tr id='+value['cid']+'><td></td><td><img class="rounded" src="../'+value['image']+'" style="width:60px;height:60px"></td><td>'+value['name']+'</td>'+
              '<td>'+(value['party_name_S'] ? value['party_name_S'] : '<b>None</b>')+'</td><td>'+value['nrc']+'</td><td>'+value['nationality']+'</td><td>'+value['religious']+'</td><td><a href="editCandidate.php?editid='+value['cid']+'" class="btn btn-warning btn-xs">Edit</a></td><td><button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" id="btnDelete" data-target="#deleteModal">Delete</button></td></tr>';
              $('#dataTable1 tbody').append(newRow);
          })
          dTable=$('#dataTable1').DataTable({
          'order' : [[2,'asc']],
          'columnDefs':[{
          'targets': [1,-1,-2],
          'orderable':false,
          'searchable':false,
          },
          {
          'targets':[0],
          'orderable':false,
          'searchable':false,
          'class':'details-control',
          'data':null,
          'defaultContent':'',
          },
        {
          'targets':[4],
          'width':'17%',
        }
          ]
          });
         } 
      }
      });
   }
$(document).ready(function() {
    console.log("Document Loaded!");
    var tR;
    getTable();

   $('#deleteModal').on('show.bs.modal', function (event) {
      tR=$(event.relatedTarget).closest('tr').attr('id');
   })
    $('#deleteModal').on('click', 'a.editor_remove', function (e) {
       $.ajax({
        'url':'dataforDataTable1.php?d='+tR,
        'type':'GET',
        'contentType':'application/json;charset=utf-8',
        'success': function(result){
          console.log(result);
          getTable();
        }
       })
    } );

    $('#dataTable1 tbody').on("click","tr td.details-control",function(){
      var tr=$(this).closest('tr');
      var trID=tr.attr('id');
      var row=dTable.row(tr);
      if(row.child.isShown())
      {
        tr.removeClass('details');
        row.child.hide();
      }
      else{
        tr.addClass('details');
        row.child(format(trID,global_data)).show();
      }
    });
   } );
  </script>

</body>

</html>
