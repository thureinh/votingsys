<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Manage Elections Page</title>

  <!-- Custom fonts for this template -->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.1.0/css/flag-icon.min.css" rel="stylesheet">
  <!-- Custom styles for this page -->
  <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">
<?php if(!isset($_COOKIE['admin'])) header("Location: ../loginanim.php") ?>
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
        <div class="container-fluid mt-3">

          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">Elections Table</h1>

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Manage Elections</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable3" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th style="display: none"></th>
                      <th style="display: none"></th>
                      <th style="display: none"></th>
                      <th style="display: none"></th>
                      <th style="display: none"></th>
                      <th style="display: none"></th>
                    </tr>
                    <tr>
                      <th >Election Name</th>
                      <th>Date</th>
                      <th>From (Begin Year)</th>
                      <th>To (End Year)</th>
                      <th colspan="2" style="text-align: center;vertical-align: middle;">Action</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Election Name</th>
                      <th>Date</th>
                      <th>From(Begin year)</th>
                      <th>To(End year)</th>
                      <th colspan="2" style="text-align: center;vertical-align: middle;">Action</th>
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
     <!-- Delete Modal -->
      <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
      <div class="modal-dialog">
    <div class="modal-content">
          <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title custom_align" id="Heading">Delete this entry</h4>
      </div>
          <div class="modal-body">
       
       <div class="alert alert-danger">Are you sure you want to delete this Record? <br><i class="fa fa-exclamation-circle"></i>&nbsp;This will delete all records related to this.</div>
       
      </div>
        <div class="modal-footer ">
        <a href="#" class="btn btn-success btnDelete" data-dismiss="modal"><i class="fas fa-check-circle"></i>Yes</a>
        <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fas fa-window-close"></i>No</button>
      </div>
        </div>
  </div> 
    </div>
      <!-- Delete Message Modal -->
<div class="modal fade" id="dMsgShow" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <b></b>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
  <script>
    $(document).ready(function(){
      var datable=new $('#dataTable3').DataTable({
        'processing' : true,
        'serverSide' : true,
        'ajax' : {
                  'url' : 'dbProcessorForDTs.php?q=2',
                  'type' : 'GET',
                  'contentType' : 'application/x-www-form-urlencoded',
                 },
        'columns' : [
                 { 'data' : 'ename' },
                 { 'data' : 'edate' },
                 { 'data' : 'eyear_begin'},
                 { 'data' : 'eyear_end'},
                 { 'data' : 'DT_RowId'},
                 { 'data' : 'DT_RowId'}
                 ],
        'order' : [[1,'asc']],
        'columnDefs':[
        {
          'targets' : -1,
          'render' : function(data,type,row,meta){
            return '<div class="d-flex justify-content-center"><button type="button" data-title="Delete" data-toggle="modal" id="btnDelete" data-target="#deleteModal" value='+data+' class="btn btn-danger">Delete</button></div>';
          }
        },
        {
          'targets' : -2,
          'render' : function(data,type,row,meta){
            return '<div class="d-flex justify-content-center"><button type="button" value='+data+' class="btn btn-warning btnEdit">Edit</button</div>';
          }
        }
        ]
      });
      var idToDelete;
      $('#deleteModal').on('show.bs.modal',function(e){
        idToDelete=$(e.relatedTarget).val();
      });
      $('#deleteModal').on('click','.btnDelete',function(event){
        $.ajax({
          'url' : '../transferdb.php?pid=4&q='+idToDelete,
          'type':'GET',
          'contentType':'application/x-www-form-urlencoded',
          'dataType':'html',
          'success':function(result){
            datable.ajax.reload(null,false);
            $('#dMsgShow .modal-body b').text(result);
            $('#dMsgShow').modal('show');
            setTimeout(function(){$('#dMsgShow').modal('hide')},3000);
          }
        });
      });
      $('#dataTable3').on('click','.btnEdit',function(e){
        temp_id=$(e.target).val();
        $(location).attr('href','editElection.php?editid='+temp_id);
      });
    });
  </script>
</body>

</html>
