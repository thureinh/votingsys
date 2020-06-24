<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Voter Management</title>

  <!-- Custom fonts for this template -->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.1.0/css/flag-icon.min.css" rel="stylesheet">
  <!-- Custom styles for this template -->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

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

 <!-- Nav Component -->
<ul class="nav nav-tabs m-3">
  <li class="nav-item">
    <a class="nav-link active" href="voterManagement.php">Decision Table</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="acceptedManagement.php">View Accepted People List</a>
  </li>
</ul>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-2 mt-3 text-gray-800" style="text-align: center;">Voter Management Table</h1>
<?php
  try {
    $con=new PDO('mysql:host=localhost;dbname=test','root','');
    $con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $stmt=$con->prepare('SELECT pending_users.*,ename,township FROM pending_users INNER JOIN elections ON elections.eid=pending_users.electionID INNER JOIN constituency ON constituency.conid=pending_users.constituencyID');
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $pendingUsers=$stmt->fetchAll();
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
    $con=null;
?>
          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Approve/Reject Voters</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th style="display: none;"></th>
                      <th style="display: none;"></th>
                      <th style="display: none;"></th>
                      <th style="display: none;"></th>
                      <th style="display: none;"></th>
                      <th style="display: none;"></th>
                      <th style="display: none;"></th>
                    </tr>
                    <tr>
                      <th>Voter Name</th>
                      <th>Voter NRC</th>
                      <th>NRC Images</th>
                      <th>Requested Election</th>
                      <th>Requested Constituency</th>
                      <th colspan="2" style="text-align: center;vertical-align: middle;">Decisions</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Voter Name</th>
                      <th>Voter NRC</th>
                      <th>NRC Images</th>
                      <th>Requested Election</th>
                      <th>Requested Constituency</th>
                      <th colspan="2" style="text-align: center;vertical-align: middle;">Decisions</th>
                    </tr>
                  </tfoot>
                  <tbody>
                      <?php
                        foreach($pendingUsers as $value) {
                      ?>
                      <tr id="<?php echo $value['puid']; ?>">
                        <td><?php echo $value['pu_name']; ?></td>
                        <td><?php echo $value['pu_nrc']; ?> </td>
                        <td><button type="button" value="<?php echo $value['nrc_front_img']; ?>-|-<?php echo $value['nrc_back_img']; ?>" class="btn btn-primary btn-block nrc-view-btn">View NRC</button></td>
                        <td><?php echo $value['ename']; ?></td>
                        <td><?php echo $value['township']; ?></td>
                        <td><div class="d-flex justify-content-center"><button type="button" class="btn btn-success" data-toggle="modal" data-target="#acceptModal">Accept</button></div></td>
                        <td><div class="d-flex justify-content-center"><button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectModal">Reject</button></div></td>
                      </tr>
                      <?php
                        }
                      ?>
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
<?php include 'include_files/footer.php'  ?> 
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

 <!-- Accept Modal -->
 <div class="modal fade" id="acceptModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Accept Request?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are You Sure You Want To Accept This Request?<br>
        This can't be undone.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary accept-request">OK</button>
      </div>
    </div>
  </div>
</div>

<!--  Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Reject Request?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are You Sure You Want To Reject This Request?<br>
        This can't be undone.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary reject-request">OK</button>
      </div>
    </div>
  </div>
</div>

  <!-- NRC VIEW MODAL -->
<div class="modal fade bd-example-modal-xl" id="nrc-view-modal" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
 <!--      <img src="..." class="d-block w-100" alt="..."> -->
    </div>
    <div class="carousel-item">
<!--       <img src="..." class="d-block w-100" alt="..."> -->
    </div>
  </div>
  <a class="carousel-control-prev bg-dark" href="#carouselExampleControls" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next bg-dark" href="#carouselExampleControls" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
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
$(document).ready(function(){
    var globalID;
    var dTable=$('#dataTable').DataTable({
      'order' : [[0,'asc'],[1,'asc']],
      'columnDefs':[{
          'targets' : [2],
          'orderable' : false,
          'searchable' : false,
        }]
    });
    $('#dataTable').on('click','tbody .nrc-view-btn',function(){
        var images=$(this).val().split(/\-\|\-/);
        $('#nrc-view-modal div.carousel-item').eq(0).html('<div class="d-flex justify-content-center"><img src="../../'+images[0]+'"style="width:600px;height:600px" class="d-block"></div>');
        $('#nrc-view-modal div.carousel-item').eq(1).html('<div class="d-flex justify-content-center"><img src="../../'+images[1]+'"style="width:600px;height:600px" class="d-block"></div>');
        $('#nrc-view-modal').modal('show');
    });
    $('#acceptModal,#rejectModal').on('show.bs.modal',function(event){
        globalID=$(event.relatedTarget).closest('tr').attr('id');
    });
    $('#acceptModal').on('click','.modal-footer .accept-request',function(){
      $.ajax({
          'url' : '../transferDB.php?pid=7&aid='+globalID,
          'type' : 'GET',
          'contentType' : 'application/x-www-form-urlencoded;charset=UTF-8',
          'dataType' : 'text',
          'success' : function(result){
            var originalValue = parseInt($('#sidebar-pending-counts-decrease').text());
            $('#sidebar-pending-counts-decrease').text(originalValue - 1);
            reconstruct_DataTable(result);
            $('#acceptModal').modal('hide');
          }
      })
    })
    $('#rejectModal').on('click','.modal-footer .reject-request',function(){
      $.ajax({
          'url' : '../transferDB.php?pid=7&rid='+globalID,
          'type' : 'GET',
          'contentType' : 'application/x-www-form-urlencoded;charset=UTF-8',
          'dataType' : 'text',
          'success' : function(result){
              var originalValue = parseInt($('#sidebar-pending-counts-decrease').text());
              $('#sidebar-pending-counts-decrease').text(originalValue - 1);
              reconstruct_DataTable(result);
              $('#rejectModal').modal('hide');
          }
      });
    })
    function reconstruct_DataTable(inputObj){
        var tableRows=JSON.parse(inputObj);
        dTable.destroy();
        $('#dataTable tbody').empty();
        $.each(tableRows,function(key,value){
            var newRow='<tr id='+value['puid']+'><td>'+value['pu_name']+'</td><td>'+value['pu_nrc']+'</td><td><button type="button" value="'+value['nrc_front_img']+'-|-'+value['nrc_back_img']+'" class="btn btn-primary btn-block nrc-view-btn">View NRC</button></td><td>'+value['ename']+'</td><td>'+value['township']+'</td><td><div class="d-flex justify-content-center"><button type="button" class="btn btn-success" data-toggle="modal" data-target="#acceptModal">Accept</button></div></td><td><div class="d-flex justify-content-center"><button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectModal">Reject</button></div></td></tr>';

            $('#dataTable tbody').append(newRow);
        });
        dTable=$('#dataTable').DataTable({
          'order': [[0,'asc'],[1,'asc']],
          'columnDefs' : [{
            'targets' : [2],
            'orderable' : false,
            'searchable' : false,
          }]    
        });        
    }
})
</script>
</body>

</html>