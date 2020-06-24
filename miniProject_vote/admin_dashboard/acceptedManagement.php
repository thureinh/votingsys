<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Accepted Management</title>

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
    <a class="nav-link" href="voterManagement.php">Decision Table</a>
  </li>
  <li class="nav-item">
    <a class="nav-link active" href="acceptedManagement.php">View Accepted People List</a>
  </li>
</ul>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800" style="text-align: center;">Accepted People List Table</h1>
          <?php
              try {
                  $temp_conn=new PDO('mysql:host=localhost;dbname=test','root','');
                  $temp_conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                  $stmt=$temp_conn->prepare('SELECT u_name,u_nrc,nrc_front_img,nrc_back_img,ename,township,code FROM voters INNER JOIN users ON voters.uid=users.uid INNER JOIN constituency ON constituency.conid=voters.conid INNER JOIN elections ON elections.eid=voters.eid');
                  $stmt->execute();
                  $stmt->setFetchMode(PDO::FETCH_ASSOC);
                  $accepteds=$stmt->fetchAll();
              } catch (PDOException $e) {
                  echo $e->getMessage();
              }
              $temp_conn=null;
          ?>
          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Accepted People</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Voter Name</th>
                      <th>View NRC Image</th>
                      <th>NRC Number</th>
                      <th>Permitted Election</th>
                      <th>Permitted Constituency</th>
                      <th>Code</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Voter Name</th>
                      <th>View NRC Image</th>
                      <th>NRC Number</th>
                      <th>Permitted Election</th>
                      <th>Permitted Constituency</th>
                      <th>Code</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    <?php
                      foreach ($accepteds as $value) {
                    ?>
                      <tr>
                        <td><?php echo $value['u_name'] ?></td>
                        <td><button type="button" value="<?php echo $value['nrc_front_img']; ?>-|-<?php echo $value['nrc_back_img']; ?>" class="btn btn-primary btn-block nrc-view-btn">View NRC</button></td>
                        <td><?php echo $value['u_nrc']; ?></td>
                        <td><?php echo $value['ename'] ?></td>
                        <td><?php echo $value['township'] ?></td>
                        <td><b data-toggle="tooltip" title="View QR"><a href="#"><?php echo $value['code']?></a></b></td>
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
<!-- QR View Modal -->
<div class="modal fade bd-example-modal-lg" id="qrmodal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLongTitle">QR Code</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container">
          <div id="qrcode" class="d-flex justify-content-center">
            
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
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
  <script src="vendor/qrcode.js"></script>
<script>
    $(document).ready(function(){
        console.log('Document Loaded');
      var qrcode = new QRCode(document.getElementById("qrcode"), {
          width : 400,
          height : 400,
          colorDark : "#8E44AD",
          colorLight : "#F7F9F9",
          correctLevel : QRCode.CorrectLevel.H
      });
        var dataTable=$('#dataTable').DataTable({
          'order' : [[0,'asc'],[3,'asc'],[4,'asc']],
          'columnDefs' : [{
              'targets' : [1,-1],
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
    $('#dataTable').on('click','tbody a',function(event){
      event.preventDefault();
      var qrmodal = $('qrmodal');
      var value = $(event.target).text();
      qrcode.makeCode(value);
      $('#qrmodal').modal('show');
    })
    })
</script>

</body>

</html>
