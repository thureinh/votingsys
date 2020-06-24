<?php include 'include/header.php'; ?>
<style>
     
.card.hovercard {
    position: relative;
    padding-top: 0;
    overflow: hidden;
    text-align: center;
    background-color: rgba(214, 224, 226, 0.2);
    width: 250px;
    height: 300px;
}

.card.hovercard .cardheader {
    background-size: cover;
    height: 135px;
    width: 100%;
    background-position: center;
}

h2 {
    text-align: center;
    color: black;
}

.card.hovercard .avatar {
    position: relative;
    top: -50px;
    margin-bottom: -50px;
}

.card.hovercard .avatar img {
    width: 100px;
    height: 100px;
    max-width: 100px;
    max-height: 100px;
    -webkit-border-radius: 50%;
    -moz-border-radius: 50%;
    border-radius: 50%;
    border: 5px solid rgba(255,255,255,0.5);
}

.card.hovercard .info {
    padding: 4px 8px 10px;
}

.card.hovercard .info .title {
    margin-bottom: 4px;
    font-size: 24px;
    line-height: 1;
    color: black;
    vertical-align: middle;
}

.card.hovercard .info .desc {
    overflow: hidden;
    font-size: 12px;
    line-height: 20px;
    color: #737373;
   /* text-overflow: ellipsis;*/
}

.card.hovercard .bottom {
   /* padding: 0 20px;*/
    margin-bottom: 17px;
    margin-left: 7px;
    margin-right: 7px;
}
.new1 {
         border: 3px solid #4e73df;
         width: 5%;

    }
.container.row.col {
        width: 250px;
        height: 300px;
    }


/*.btn{ width:35px; height:32px; line-height:18px; 
       }*/
</style>
<title>Vote</title>
</head>
<body onload="showDB()">
  <?php require 'include/navbar.php'; ?> 
  <?php
  if(!isset($_COOKIE['user']) && !isset($_COOKIE['admin'])){
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
  }
  else{
  $isMulti = false;
  try{
  $conn=new PDO('mysql:host=localhost;dbname=test','root','');
  $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

  if(isset($_COOKIE['user']) && !empty($_COOKIE['user'])){

    $raw_voterID=$_COOKIE['user'];
    $raw_voterID=preg_split('/\-\|\-/', $raw_voterID);
    $voterID=$raw_voterID[1];
    $stmt=$conn->prepare('SELECT elections.* FROM elections JOIN voters ON elections.eid=voters.eid WHERE voter_id=:voterID');
    $stmt->bindParam(':voterID',$voterID);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $elections=$stmt->fetch();
    $stmt=$conn->prepare('SELECT constituency.* FROM constituency JOIN voters ON constituency.conid=voters.conid WHERE voter_id=:voterID');
    $stmt->bindParam(':voterID',$voterID);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $constituency=$stmt->fetch(); 

  }
  else if(isset($_COOKIE['admin']) && !empty($_COOKIE['admin'])){

    $stmt = $conn->prepare('SELECT eid, conid FROM candidateandelection ORDER BY candidateandelection.ceid DESC LIMIT 1');
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $ids = $stmt->fetch();
    $eid = $ids['eid']; $conid = $ids['conid'];
    $stmt = $conn->prepare('SELECT elections.* FROM elections INNER JOIN candidateandelection ON elections.eid = candidateandelection.eid ORDER BY elections.eid');
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $elections = $stmt->fetchAll();
    $stmt = $conn->prepare('SELECT constituency.* FROM constituency INNER JOIN candidateandelection ON constituency.conid = candidateandelection.conid WHERE candidateandelection.eid = :eid ORDER BY constituency.conid');
    $stmt->bindParam( ':eid', $eid);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $constituency = $stmt->fetchAll();
    $isMulti = true;

  }

  }
  catch(PDOException $e){
    echo $e->getMessage();
  }
  $conn=null;
?>
    <h2> Voting System For Corporations </h2>
    <hr class="new1" align="center" style="margin-bottom: 0px;border-bottom-width: 0px;">
    <br>
    <div class="container" style="padding-left: 0px;padding-right: 0px;">
          <h5 style="text-align: center;">Select Election You Want To Vote</h5>
  <div class="form-group alert alert-primary">
    <div class="row">
    <div class="col-7">
<select class="form-control" id='election-select1' onchange="esOne_Change(this)" >
<?php
  if(!$isMulti) {
?>
  <option value="<?php echo $elections['eid']; ?>"> <?php echo $elections['ename']; ?> , <?php if(!empty($elections['eyear_end'])) { ?>[ From <?php echo $elections['eyear_begin']; ?> ~ To <?php echo $elections['eyear_end']; ?> ]<?php }else { ?>[ <?php echo $elections['eyear_begin']; ?> ]<?php } ?> , <?php $datetime=DateTime::createFromFormat('Y-m-d',$elections['edate']);
  $eday=$datetime->format('jS-F-Y ( l )'); echo $eday;
   ?></option>
<?php
  } else {
        $e_temp = 0;
        foreach ($elections as $value) {
          if($e_temp === (int)$value['eid']){
              continue;
          }
        $e_temp = (int) $value['eid'];
?>
  <option value="<?php echo $value['eid']; ?>" <?php if($eid == $value['eid']) echo 'selected'; ?>> <?php echo $value['ename']; ?> , <?php if(!empty($value['eyear_end'])) { ?>[ From <?php echo $value['eyear_begin']; ?> ~ To <?php echo $value['eyear_end']; ?> ]<?php }else { ?>[ <?php echo $value['eyear_begin']; ?> ]<?php } ?> , <?php $datetime=DateTime::createFromFormat('Y-m-d',$value['edate']);
  $eday=$datetime->format('jS-F-Y ( l )'); echo $eday;
   ?></option> 
<?php
        }
?>

<?php    
  }
?>
</select>
</div>

 <div class="col-5">
  <select class="form-control" id='election-select2' onchange="esTwo_Change(this)" >
<?php 
    if(!$isMulti){
?>
  <option value="<?php echo $constituency['conid']; ?>"><?php echo $constituency['township']; ?>
  </option>
<?php
    } else {
          $c_temp = 0;
          foreach ($constituency as $value) {
            if($c_temp === (int)$value['conid']){
              continue;
            }
          $c_temp = (int)$value['conid'];
?>
  <option value="<?php echo $value['conid']; ?>" <?php if($value['conid'] == $conid) echo 'selected'; ?>><?php echo $value['township']; ?>
  </option>
<?php
          }
?>
<?php      
    }
?>
  </select>
 </div>
</div></div></div>
<!-----------------------------container ------------------>
    <div class="container" id="tablebdy">

    </div>
<?php } ?>
<!-- Vote Confirm Modal -->
<div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalScrollableTitle">You Are About To Vote This Candidate...</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="d-flex m-0 p-0 justify-content-center">
        <div class="card" style="width: 20rem;">
            <img id="viewCandidate" src="#" style="height: 300px;" class="card-img-top" alt="">
            <div class="card-body">
            <p class="card-text" style="text-align: center;">
              
            </p>
            </div>
        </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="confirmVote" data-dismiss="modal" onclick="vote(this)" class="btn btn-primary">Confirm Vote</button>
      </div>
    </div>
  </div>
</div>
  <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="bootstrap/js/jquery-3.4.1.js"></script>
    <script src="bootstrap/js/popper.min.js"></script>
    <script src="bootstrap/js/bootstrap.js"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.8.2/js/all.js" integrity="sha384-DJ25uNYET2XCl5ZF++U8eNxPWqcKohUUBUpKGlNLMchM7q4Wjg2CUpjHLaL8yYPH" crossorigin="anonymous"></script>

<!-- Sweet Alert 2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<!-- Optional: include a polyfill for ES6 Promises for IE11 and Android browser -->
<script src="https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.min.js"></script>

<script>
$(document).ready(function(){
  console.log('Document Loaded');
  $('#exampleModalScrollable').on('show.bs.modal',function(event){
    console.log('Modal Show up');
    var precID=$(event.relatedTarget).val();
    var imageSrc=$(event.relatedTarget).parents('div.bottom').siblings('div.avatar').children('img').attr('src');
    var name=$(event.relatedTarget).parents('div.bottom').siblings('div.info').find('label').text();
    $(this).find('#viewCandidate').attr('src',imageSrc);
    $(this).find('p.card-text').html('<strong> Are you sure you want to vote </strong><br><strong style="text-align : center">'+name+' ?</strong><br><p class="font-weight-bolder text-danger">This can\'t be undone.</p>');
    $(this).find('#confirmVote').val(precID);
  });
})
 //--------------- SELECT BOX EVENTS --------------------------
function esOne_Change(input){
  var electionID = $(input).val();
  $.ajax({
    'url': 'transferDB.php?pid=2&e='+electionID,
    'type': 'GET',
    'contentType': 'application/x-www-form-urlencoded;charset=utf-8',
    'dataType': 'text',
    'success': function (result){
        var objs = JSON.parse(result);
        $('#election-select2').empty().append('<option value="">Choose Constituency</option>');
        var temp_id = 0;
        $.each( objs, function ( key, value){
            if (temp_id === parseInt(value['conid'])) return;
            temp_id = parseInt(value['conid']);
            let new_option = '<option value="'+value['conid']+'">'+value['township']+'</option>';
            $('#election-select2').append(new_option);
        })
    }
  })
}
function esTwo_Change(input){
   var electionID = $('#election-select1').val();
   var constituencyID = $(input).val();
   if(constituencyID !== ''){
      $('#election-select2').find('option[value=""]').remove();
   }
   $.ajax({
    'url': 'DBProcessor.php?pid=5&e='+electionID+'&c='+constituencyID,
    'type': 'GET',
    'contentType': 'application/x-www-form-urlencoded;charset=utf-8',
    'dataType': 'html',
    'success': function (result){
      $('#tablebdy').html(result);
    } 
   })
}

function showDB(){
n =  new Date();
y = n.getFullYear();
m = n.getMonth() + 1;
d = n.getDay();
if(m<10){
  m="0"+m;
}
if(d<10){
  d="0"+d;
}
var current_Time=y+'-'+m+'-'+d;
var initialElection=document.getElementById('election-select1').value;
var initialConstituency=document.getElementById('election-select2').value;
showRelatedCandidates(initialElection,initialConstituency);
}
function showRelatedCandidates(input1,input2){
  var asyn;
  if(window.XMLHttpRequest){
    asyn=new XMLHttpRequest();
  }
  else{
    asyn=new ActiveXObject('Microsoft.XMLHTTP');
  }
  asyn.onreadystatechange=function(){
    if(asyn.readyState==4 && asyn.status==200){
        document.getElementById('tablebdy').innerHTML=this.responseText
    }
  }
  var raw_voterID = getCookie('user');
  if (raw_voterID != "" && raw_voterID != null) {
    var voterID=raw_voterID.split(/\-\|\-/);
    voterID=voterID[1];
  }

  asyn.open('GET','DBProcessor.php?pid=5&e='+input1+'&c='+input2+(voterID ? '&v='+voterID : '' ));
  asyn.send();
}
function removeChildren(node){
  var removeItem=node.lastElementChild;
  while(removeItem){
    node.removeChild(removeItem);
    removeItem=node.lastElementChild;
  }
}
function vote(obj){
      console.log("Clicked Vote");
      var present_election=document.getElementById('election-select1').value;
      var selected_constituency=document.getElementById('election-select2').value;
      // localStorage.setItem("virtualCurrentTime","Virtual Time");
      var asyn;
      if(window.XMLHttpRequest){
        asyn=new XMLHttpRequest();
      }else{asyn=new ActiveXObject('Microsoft.XMLHTTP');}
      var completeArr=[];
      var selected="precid="+obj.value;
      completeArr.push('eid='+present_election);
      completeArr.push('conid='+selected_constituency);
      completeArr.push(selected);
      JSON.stringify(completeArr);
  asyn.onreadystatechange=function(){
    if(asyn.readyState==4 && asyn.status==200){
    Swal.fire(
        this.responseText,
        'Congratulations',
        'success'
    )
var initialElection=document.getElementById('election-select1').value;
var initialConstituency=document.getElementById('election-select2').value;
showRelatedCandidates(initialElection,initialConstituency);  
    }
  }
  asyn.open("POST","process.php");
  asyn.setRequestHeader( "Content-Type", "application/json" );
  asyn.send(completeArr);
}
function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}
</script>       
</body>
</html>