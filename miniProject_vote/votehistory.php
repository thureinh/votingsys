<?php session_start(); ?>
<?php include 'include/header.php'; ?>
<title>History Page</title>
</head>
<body onload="storeIn()">
<?php require 'include/navbar.php'; ?>
<div class="container" style="padding-left: 0px;padding-right: 0px;">

<?php
	try{
		$selectedQueries;
			$con=new PDO('mysql:host=localhost;dbname=test','root','');
			$con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	if(isset($_SESSION) && !empty($_SESSION)){

						$eid=$_SESSION['eid'];
						$conid=$_SESSION['conid'];
						$con=new PDO('mysql:host=localhost;dbname=test','root','');
						$con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
						$stmt=$con->prepare('SELECT candidateandelection.eid,candidateandelection.conid,ename,edate,township FROM elections JOIN candidateandelection ON elections.eid=candidateandelection.eid JOIN constituency ON constituency.conid=candidateandelection.conid WHERE candidateandelection.eid=:eid AND candidateandelection.conid=:conid');
						$stmt->bindParam(':eid',$eid);
						$stmt->bindParam(':conid',$conid);
						$stmt->execute();
						$stmt->setFetchMode(PDO::FETCH_ASSOC);
						$selectedQueries=$stmt->fetch();
	
	}
	else
	{
				$stmt=$con->prepare('SELECT candidateandelection.eid,candidateandelection.conid,ename,edate,township FROM elections INNER JOIN candidateandelection ON elections.eid=candidateandelection.eid INNER JOIN counts ON counts.ceid=candidateandelection.ceid INNER JOIN constituency ON candidateandelection.conid=constituency.conid ORDER BY counts.vid DESC LIMIT 1');	
				$stmt->execute();
				$stmt->setFetchMode(PDO::FETCH_ASSOC);
				$selectedQueries=$stmt->fetch();
	}		
		$stmt2=$con->prepare('SELECT elections.* FROM elections JOIN candidateandelection ON elections.eid=candidateandelection.eid JOIN counts ON candidateandelection.ceid=counts.ceid ORDER BY candidateandelection.ceid DESC');
	    $stmt2->execute();
	    $stmt2->setFetchMode(PDO::FETCH_ASSOC);
	    $elections=$stmt2->fetchAll();
	}catch(PDOException $e){
		echo $e->getMessage();
	}
	$con=null;
    function is_Same($arr,$input){
    	$temp_var=false;
    	if(empty($arr)){
      		return false;
    	}
    	else
    	{
    	foreach ($arr as $value) {
      	if((int)$value===(int)$input){
          	$temp_var=true;
          	break;
      	}
    	}
    	return $temp_var;   
   		}
  	}
?>
<div class="d-flex justify-content-center bg-primary">
 	<h2 class="text-justify-center text-white">Election Histories</h2>
</div>
<nav class="navbar navbar-expand-lg navbar-light border border-dark">
<div class="col-7">
	<select class="form-control" id='election-select' onchange="clickFirstSelect()">
<?php
	$temp_store=[];
	foreach ($elections as $value) {
		if(is_Same($temp_store,$value['eid'])){
			continue;
		}
    	else{
     		array_push($temp_store, $value['eid']);
?>
  <option value="<?php echo $value['eid']; ?>" <?php if($value['eid']==$selectedQueries['eid']) echo 'selected'; ?> > <?php echo $value['ename']; ?> , <?php if(!empty($value['eyear_end'])){ ?>[ From <?php echo $value['eyear_begin']; ?> ~ To <?php echo $value['eyear_end']; ?> ]<?php }else { ?>[ <?php echo $value['eyear_begin']; ?> ]<?php } ?> , <?php $datetime=DateTime::createFromFormat('Y-m-d',$value['edate']);$eday=$datetime->format('jS-F-Y ( l )'); echo $eday; ?></option>
<?php
  } }
?>
	</select>
</div>
<div class="col-5">
	<select class="form-control" id='constituency-select' onchange="clickSecondSelect()">
  		<option value='<?php echo $selectedQueries['conid']; ?>' selected><?php echo $selectedQueries['township'] ?></option>
	</select>
</div>
</nav>
	 <table class="table table-dark">
	 	<thead class="thead-dark">
	 		<tr>
	 			<th scope="col">Profile</th>
	 			<th scope="col">Name</th>
	 			<th scope="col">Party</th>
	 			<th scope="col">Vote Counts</th>
	 		<th scope="col" style="width: 50%; text-align: center;">Percentage</th>
	 		</tr>
	 	</thead>
	<tbody id="storedtb">

	</tbody>
	</table>
</div>

<script type="text/javascript">
	function initialSetup(){
		var electionID=document.getElementById('election-select').value;
		var selectedCon=document.getElementById('constituency-select').value;
		var xhr;
		if(window.XMLHttpRequest){
			xhr=new XMLHttpRequest();
		}else{xhr=new ActiveXObject("Microsoft.XMLHTTP");}
		xhr.onreadystatechange=function(){
			if(xhr.readyState==4 && xhr.status==200){
				var conObj=JSON.parse(this.responseText);
				var conObj_values=Object.values(conObj);
				var temp_Keep;
				for(const value of conObj_values){
					if(selectedCon==value['conid'] || temp_Keep==value['conid']){
						continue;
					}
					temp_Keep=value['conid'];
					newOption='<option value="'+value['conid']+'">'+value['township']+'</option>';
					document.getElementById('constituency-select').innerHTML+=newOption;
				}
			}	
		}
		xhr.open("GET","transferDB.php?pid=11&e="+electionID,true);
		xhr.send();
	}
	function clickFirstSelect(){
		var eid=document.getElementById('election-select').value;
		var itemToRemove=document.getElementById('constituency-select');
		removeChildren(itemToRemove);
		itemToRemove.innerHTML='<option value="">Click Here To Choose Constituency</option>';
		var xhr;
		if(window.XMLHttpRequest){
			xhr=new XMLHttpRequest();
		}else{xhr=new ActiveXObject("Microsoft.XMLHTTP");}
		xhr.onreadystatechange=function(){
			if(xhr.readyState==4 && xhr.status==200){
				var conObj=JSON.parse(this.responseText);
				var conObj_values=Object.values(conObj);
				var temp_store;
				for(const value of conObj_values){
					if(value['conid']==temp_store) continue;
					temp_store=value['conid'];
					let newOption='<option value="'+value['conid']+'">'+value['township']+'</option>';
					itemToRemove.innerHTML+=newOption;
				}
			}
		}
		xhr.open("GET","transferDB.php?pid=1&e="+eid,true);
		xhr.send();
	}
	function clickSecondSelect(){
		var eid=document.getElementById('election-select').value;
		var conid=document.getElementById('constituency-select').value;
		if(conid !== ''){
			let optionsToRemove = getOptionsByValue('');
			if(optionsToRemove.length === 1)
			{
				optionsToRemove[0].parentNode.removeChild(optionsToRemove[0]);
			}
		}
		var xhr;
		if(window.XMLHttpRequest){
			xhr=new XMLHttpRequest();
		}else{xhr=new ActiveXObject("Microsoft.XMLHTTP");}
		xhr.onreadystatechange=function(){
			if(xhr.readyState==4 && xhr.status==200){
			document.getElementById('storedtb').innerHTML=this.responseText;				
			}
		}
		xhr.open("GET","transferDB.php?pid=1&e="+eid+"&c="+conid,true);
		xhr.send();		
	}
	function getOptionsByValue(value)
	{
    var allInputs = document.getElementsByTagName("option");
    var results = [];
    for(var x=0;x<allInputs.length;x++)
        if(allInputs[x].value == value)
            results.push(allInputs[x]);
    return results;
	}
	function storeIn(){
		var xhr;
		if(window.XMLHttpRequest){
			xhr=new XMLHttpRequest();
		}else{xhr=new ActiveXObject("Microsoft.XMLHTTP");}
		xhr.onreadystatechange=function(){
			if(xhr.readyState==4 && xhr.status==200){
			document.getElementById('storedtb').innerHTML=this.responseText;
			initialSetup();
			}
		}
		xhr.open("GET","dbprocessor.php?pid=1",true);
		xhr.send();
	}
	function removeChildren(node){
  		var removeItem=node.lastElementChild;
  		while(removeItem){
    	node.removeChild(removeItem);
    	removeItem=node.lastElementChild;
  	}
}
</script>
<?php include 'include/footer.php'; ?>