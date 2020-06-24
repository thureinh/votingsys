<?php session_start(); ?>
<?php include 'include/header.php'; ?>
<title>Display Page</title>
<link rel="stylesheet" href="filesForCountdown/style.css">
</head>
<body onload="storeIn()">
<?php require 'include/navbar.php'; ?>

<?php
	if( !isset($_COOKIE['user']) && !isset($_COOKIE['admin']) ){
?>
  <div class="container">
  <div class="jumbotron">
  <h1 class="display-4">Login Required !</h1>
  <p class="lead">Please Login First to View This Page</p>
  <hr class="my-4">
  <a class="btn btn-primary btn-lg" href="../index.php" role="button">Go Back To Login</a>
  </div>
  </div>
<?php
	}else{
?>
    <!------------------ HEADER ---------------------->
    <div class="container" style="padding-left: 0px;padding-right: 0px;">

<?php
	try{
		$selectedQueries;
					$con = new PDO('mysql:host=localhost;dbname=test','root','');
					$con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
          $stmt = $con->prepare('SELECT ename,edate,township FROM elections JOIN candidateandelection ON elections.eid=candidateandelection.eid JOIN constituency ON constituency.conid=candidateandelection.conid WHERE candidateandelection.eid=:eid AND candidateandelection.conid=:conid');

          if (isset($_COOKIE['user']) && !empty($_COOKIE['user']))
          {
            $raw_voterID = $_COOKIE['user'];
            $raw_voterID = preg_split('/\-\|\-/', $raw_voterID);
            $voterID = $raw_voterID[1];
            $stmt2 = $con->prepare('SELECT eid,conid FROM voters WHERE voter_id = :voter_id');
            $stmt2->bindParam( ':voter_id', $voterID);
            $stmt2->execute();
            $stmt2->setFetchMode(PDO::FETCH_ASSOC);
            $ids = $stmt2->fetch();
            $eid = $ids['eid'];$conid = $ids['conid'];
            $stmt->bindParam( ':eid', $eid);
            $stmt->bindParam( ':conid', $conid);
          }
          else if (isset($_COOKIE['admin']) && !empty($_COOKIE['admin']))
          {
            if(isset($_SESSION) && !empty($_SESSION))
            {
              $eid = $_SESSION['eid'];
              $conid = $_SESSION['conid'];
              $stmt->bindParam( ':eid', $eid);
              $stmt->bindParam( ':conid', $conid);
            }
            else
            {
              $stmt = $con->prepare('SELECT ename,edate,township FROM elections INNER JOIN candidateandelection ON elections.eid=candidateandelection.eid INNER JOIN constituency ON constituency.conid=candidateandelection.conid INNER JOIN counts ON counts.ceid = candidateandelection.ceid ORDER BY counts.vid DESC LIMIT 1');       
            }
          }   
          $stmt->execute();
          $stmt->setFetchMode(PDO::FETCH_ASSOC);
          $selectedQueries = $stmt->fetch();

	}catch(PDOException $e){
		echo $e->getMessage();
	}
	$con=null;
?>
<nav class="navbar navbar-dark bg-primary">
 	<p class="text-justify text-white" style="font-size: 18px"><b>INFORMATIONS <i class="fa fa-long-arrow-alt-right text-dark font-weight-bolder"></i></b>	Election Name : <?php echo $selectedQueries['ename']; ?> <b class="text-dark font-weight-bolder">|</b> Constituency : <?php echo $selectedQueries['township'] ?> <b class="text-dark font-weight-bolder">|</b> Today is : <?php $date=DateTime::createFromFormat('Y-m-d',$selectedQueries['edate']);$showDate=$date->format('d-F-Y');echo $showDate; ?>
 	</p>
</nav>
</div>

  <!---------------  COUNT DOWN ---------------------->
  <div class="container border border-dark bg-secondary" id="countdown-display">
  <div class="countDown-container bg-secondary">
  <div class="balloon white">
    <div class="star-red"></div>
  <div class="face">
    <div class="eye"></div>
    <div class="mouth happy"></div>
  </div>
  <div class="triangle"></div>
  <div class="string"></div>
</div>

<div class="balloon red">
  <div class="star"></div>
  <div class="face">
    <div class="eye"></div>
    <div class="mouth happy"></div>
  </div>
  <div class="triangle"></div>
  <div class="string"></div>
</div>

<div class="balloon blue">
  <div class="star"></div>
  <div class="face">
    <div class="eye"></div>
    <div class="mouth happy"></div>
  </div>
  <div class="triangle"></div>
  <div class="string"></div>
</div>
  <div id="timer"></div>
  <h1 class="countDown-header">Winner has not been chosen yet.</h1>
</div>
</div>
<!-------------- END OF COUNT DOWN --------------->

<!------------------------  Winner ------------------->
		
<div class="container border border-dark bg-secondary mb-0" id="winner-display" style="display: none">

<h1 class="display-4 text-center bg-dark text-white">Winner</h1>
<div class="card-header">
<div class="d-flex justify-content-center">
	<img src="" class="card-img-top rounded" style="width: 300px;height: 300px" alt="">
</div>
</div>
<div class="card text-center">
  <div class="card-body">
    <h3 class="card-title">Winner Information</h3>
    <p class="card-text"></p>
  </div>
</div>

</div>

<!--------------------------- END OF WINNER ----------->

<div class="container" style="padding-left: 0px;padding-right: 0px;">
	 <table class="table table-dark">
	 	<thead class="thead-dark">
	 		<tr>
	 			<th scope="col">Profile</th>
	 			<th scope="col">Name</th>
	 			<th scope="col">Party</th>
	 			<th scope="col">Vote Counts</th>
	 		<th scope="col" style="width: 50%;text-align: center;">Percentage</th>
	 		</tr>
	 	</thead>
	<tbody id="storedtb">

	</tbody>
	</table>
</div>
<?php } ?>
    <script src="bootstrap/js/jquery-3.4.1.js"></script>
    <script src="bootstrap/js/popper.min.js"></script>
    <script src="bootstrap/js/bootstrap.js"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.8.2/js/all.js" integrity="sha384-DJ25uNYET2XCl5ZF++U8eNxPWqcKohUUBUpKGlNLMchM7q4Wjg2CUpjHLaL8yYPH" crossorigin="anonymous"></script>

<script type="text/javascript">

// -----------countDown-----------
function countDown(){
const year = new Date().getFullYear();
const month = new Date().getMonth();
const date = new Date().getDate();
const deadline = new Date(year,month,date,18);
var currentT = new Date();
// countdown
if((deadline-currentT) > 0){

let timer = setInterval(function() {
  const today=new Date();

  // get the difference
  const diff = deadline - today;

  if(diff<0){
    clearInterval(timer);
    displayWinner();
  }
  // math
  let days = Math.floor(diff / (1000 * 60 * 60 * 24));
  let hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  let minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
  let seconds = Math.floor((diff % (1000 * 60)) / 1000);

  // display
  document.getElementById("timer").innerHTML =
    "<div class=\"days\"> \
  <div class=\"numbers\">" + days + "</div>days</div> \
<div class=\"hours\"> \
  <div class=\"numbers\">" + hours + "</div>hours</div> \
<div class=\"minutes\"> \
  <div class=\"numbers\">" + minutes + "</div>minutes</div> \
<div class=\"seconds\"> \
  <div class=\"numbers\">" + seconds + "</div>seconds</div> \
</div>";

}, 1000);

}
else
{
	displayWinner();	
}
}

function storeIn(){
		var xhr;
		if(window.XMLHttpRequest){
			xhr=new XMLHttpRequest();
		}else{xhr=new ActiveXObject("Microsoft.XMLHTTP");}
		xhr.onreadystatechange=function(){
			if(xhr.readyState==4 && xhr.status==200){
			document.getElementById('storedtb').innerHTML=this.responseText;
			configPopover();
			countDown();
			}
		}
		xhr.open("GET","dbprocessor.php?pid=1",true);
		xhr.send();
	}
function configPopover(){
	$('[data-toggle=popover]').popover({
		  container: 'body',
		  html: true,
  		trigger: 'hover',
  		selector: '.selector-text',
  		template: '<div class="popover" role="tooltip">'+
  				  '<div class="arrow"></div>'+
  				  '<div class="card" style="width: 18rem;">'+
  				  '<div class="popover-header"></div>'+
  				  '<div class="popover-body">'+	
  				  '</div></div></div>',
  		title: function () { return '<img class="card-img-top" src="' + $(this).parents('td').data('logo') +'" alt="Card image cap" />'; },
  		content: function() { return '<div class="card-body"><p class="card-text" style="text-align: center">' +
  			$(this).parents('td').data('partyname') + '</p></div>'; },
	});
}
function displayWinner(){
	document.getElementById('countdown-display').style.display="none";
	var tableRows=document.getElementById('storedtb').rows;
	var winnerCount=0;
	var winnerName,winnerParty,winnerProfile;
	for (var i = tableRows.length - 1; i >= 0; i--) {
			let temp=tableRows[i].cells[3].getElementsByTagName('input')[0].value;
			if(winnerCount<temp){
				winnerCount=temp;
				winnerName=tableRows[i].cells[1].innerText;
				winnerParty=tableRows[i].cells[2].dataset.partyname;
				winnerProfile=tableRows[i].cells[0].children[0].src;
			}
	}
	var winnerBox=document.getElementById('winner-display');
	winnerBox.getElementsByTagName('div')[0].getElementsByTagName('img')[0].src=winnerProfile;
	winnerBox.getElementsByClassName('card-text')[0].innerHTML='<strong>Name : </strong>'+winnerName+' <b>|</b> <strong>Party : </strong>'+winnerParty+' <b>|</b> <strong>Total Vote : </strong>'+winnerCount;
	winnerBox.style.display="block";

}
</script>
  </body> 
</html>