<!DOCTYPE html>
<html>
<head>
	<title>Profile</title>
	<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.1.0/css/flag-icon.min.css" rel="stylesheet">
</head>
<style type="text/css">
	body{
		background-repeat: no-repeat;
		background-position: center;
		background-size: cover;
	}
	#Header a:hover{
		color: lightblue;
	}
	#bg-img{
		background: url('./img/back.jpg');
	}
</style>
<body>
	<?php require 'include/navbar.php'; ?>
	<?php 
try{

$dbconnect=new PDO("mysql:host=localhost;dbname=test","root","");
$dbconnect->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){$e->getMessage();}
$cid=$_GET['cid'];
$stmt=$dbconnect->prepare("SELECT candidates.*,parties.party_name_L,logo FROM candidates JOIN party_records ON candidates.cid=party_records.cid LEFT OUTER JOIN parties ON parties.parid=party_records.parid WHERE candidates.cid=:cid AND party_records.status=1");
$stmt->bindParam(":cid",$cid);
$stmt->execute();
$stmt->setFetchMode(PDO::FETCH_ASSOC);
$result=$stmt->fetch();

	 ?>

	<div id="Header" >

			<div class="container">
				<div class="d-flex justify-content-center">
					<h1 style="text-align: center;">Candidate information</h1>
				</div>
			</div>

	</div>
	<div id="bg-img"></div>
	<div id="#about" style="width: 1110px">
		<div class="container" align="middle" style="width: 440px">
			<div class="col" style="width: 840px">
				<div class="row" style="width: 610px">
					<div id="AboutData">
						<div class="card" style="background-color: #b6ddff5c">
							<div class="card-title my-5">
								<div class="media">
									<img src="<?php echo $result['image']; ?>" class="image-fluid rounded-top mx-5 d-none d-lg-block" width="230" height="300">					
									<div class="media-body">
										<!-- <h3 class="display-4">I'm John Doe</h3>
										<p>Programmer & Blogger</p> -->
										<div class="container">
											<table class="table table-responsive ml-5" style="width: 308px">
												<tr>
													<td class="text-muted" style="width: 230px"><p style="font-weight: bold;">Name : </p><?php echo $result['name']; ?> </td>
												</tr>
												<tr>
													<td class="text-muted" style="width: 230px"><p style="font-weight:bold;">
													Party : </p>
													<?php echo $result['party_name_L'] ? $result['party_name_L'] : 'None'; ?>
												    </td>
												</tr>
												<tr>
													<td class="text-muted" style="width: 230px"><p style="font-weight: bold;">NRC : </p><?php echo $result['nrc']; ?></td>

												</tr>
												<tr>
													<td class="text-muted" style="width: 230px"><p style="font-weight: bold;">Race : </p><?php echo $result['nationality']; ?></td>

												</tr>
												<tr>
													<td class="text-muted" style="width: 230px"><p style="font-weight: bold;">Religion : </p><?php echo $result['religious'];?></td>

												</tr>
												<tr>
													<td class="text-muted" style="width: 230px"><p style="font-weight: bold;">Email : </p><?php echo $result['email']; ?></td>

												</tr>
												<tr>
													<td class="text-muted" style="width: 230px"><p style="font-weight: bold;">Address : </p><?php echo $result['address'];?></td>

												</tr>
												<tr>
													<td class="text-muted" style="width: 230px"><p style="font-weight: bold;">Phone Number : </p><?php echo $result['phone_no'] ?></td>

												</tr>
											</table>
											<?php $dbconnect=null; ?>
										</div>
										<a href='index.php' class="btn btn-primary btn-lg btn-block">Go Back</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	 <script src="bootstrap/js/jquery-3.4.1.js"></script>
    <script src="bootstrap/js/popper.min.js"></script>
    <script src="bootstrap/js/bootstrap.js"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.8.2/js/all.js" integrity="sha384-DJ25uNYET2XCl5ZF++U8eNxPWqcKohUUBUpKGlNLMchM7q4Wjg2CUpjHLaL8yYPH" crossorigin="anonymous"></script>
</body>
</html>