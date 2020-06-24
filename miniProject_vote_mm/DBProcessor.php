<?php session_start(); ?>
<?php
try{
	$dbconnect=new PDO("mysql:host=localhost;dbname=test","root","");
	$dbconnect->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
if($_SERVER['REQUEST_METHOD']=='POST'){
	if(isset($_POST['postp']) && $_POST['postp']=='login'){
	$prepared=$dbconnect->prepare("SELECT * FROM admin");
	$prepared->execute();
	$prepared->setFetchMode(PDO::FETCH_ASSOC);
	$result=$prepared->fetchAll();
	$found=false;
for ($i=0; $i < count($result) ; $i++) { 
	$pass=$result[$i]['password'];
	$name=$result[$i]['username'];
	if($_POST['admname']==$name && password_verify($_POST['admpass'],$pass ))
		{
			$found=true;break;
		}
	else { $found=false; }
}
if($found){
	setcookie( "admin", $_POST['admname'],time() + 86400, "/"); 
	setcookie( "user", "", time() - 3600, "/");
			header("Location:admin_dashboard/index.php");}
else{header("Location:error_msg.php");}
}
if(isset($_POST['editmal']) && !empty($_POST['editmal'])){
if(!isset($_POST['originimg']) || empty($_POST['originimg'])){
	$target_dir='../upload_files/';
	$file=$_FILES['editimg'];
	$target_file=$target_dir.basename($file['name']);
	$ok=1;
	$errmsg="";
	$image="";
$prepared=$dbconnect->prepare("SELECT image FROM candidates WHERE cid=:id");
$prepared->bindParam(":id",$id);
$id=$_POST['editmal'];
$prepared->execute();
$prepared->setFetchMode(PDO::FETCH_ASSOC);
$result=$prepared->fetch();
$org=$result['image'];
$filetype=strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	$chck=getimagesize($file["tmp_name"]);
	if($chck==false){
		$errmsg.="File is not an image";
		$ok=0;
	}
	if($filetype != "jpg" && $filetype != "png" && $filetype !="jpeg" && $filetype != "gif" ) {
    $errmsg.="Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $ok = 0;
    }
    if ($ok == 0) {
    $errmsg.="Sorry, your file was not uploaded.";
    } else {
    if (!move_uploaded_file($file["tmp_name"], $target_file)) {
        $errmsg= "Sorry, there was an error uploading your file.";
    }else{
    	$stmt=$dbconnect->prepare('SELECT image FROM candidates WHERE cid=:cid');
		$stmt->bindParam(':cid',$_POST['editmal']);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$imagetoDel=$stmt->fetch();
		unlink($imagetoDel['image']);
    	$image=$target_file;} } }else{$image=$_POST['originimg'];}
	$prepared=$dbconnect->prepare("UPDATE candidates SET name=:name,nrc=:nrc,nationality=:nationality,religious=:religious,email=:email,address=:address,phone_no=:phone,image=:image WHERE cid=:id");
	$prepared->bindParam(":name",$name);
	$prepared->bindParam(":nrc",$nrc);
	$prepared->bindParam(":nationality",$nationality);
	$prepared->bindParam(":religious",$religious);
	$prepared->bindParam(":email",$email);
	$prepared->bindParam(":address",$address);
	$prepared->bindParam(":phone",$phone);
	$prepared->bindParam(":image",$image);
	$prepared->bindParam(":id",$id);
	$id=$_POST['editmal'];
	$name=$_POST['name'];
	$nrc=$_POST['nrc'];
	$nationality=$_POST['nationality'];
	$religious=$_POST['religious'];
	$email=$_POST['email'];
	$address=$_POST['address'];
	$phone=$_POST['phone'];
	$partyid=$_POST['party'];
	if($partyid==0){
		$partyid=NULL;
	}
	else
	{
	$partyid=$_POST['party'];
	}
	if(empty($image)){
		$image=$org;
	}
	$prepared->execute();
	$curtime=explode("-", $_POST['curtime']);
	$curyear=$curtime[0];
	$curmonth=$curtime[1];
	$sql="SELECT cid,parid,status FROM party_records";
	$prepare=$dbconnect->prepare($sql);
	$prepare->execute();
	$prepare->setFetchMode(PDO::FETCH_ASSOC);
	$queries=$prepare->fetchAll();
	$recordFound=false;	
	foreach ($queries as $value) {
		if($value['cid']==$id && $value['parid']==$partyid && $value['status']==1){
			$recordFound=true;
		}
	}
	if(!$recordFound){
	$previousParID;
	$prepare=$dbconnect->prepare("SELECT precID FROM party_records WHERE cid=:cid AND status=1");
	$prepare->bindParam(':cid',$_POST['editmal']);
	$prepare->execute();
	$rct=$prepare->rowCount();
	if($rct>0){
		$prepare->setFetchMode(PDO::FETCH_ASSOC);
		$results=$prepare->fetch();
		$previousParID=$results['precID'];
	}
	$sql="UPDATE party_records SET status=0 WHERE cid=".$id;
	$dbconnect->exec($sql);
	$prepare=$dbconnect->prepare("INSERT party_records(cid,parid,status) VALUES(:cid,:partyid,1)");
	$prepare->bindParam(':cid',$id);
	$prepare->bindParam(':partyid',$partyid);
	$prepare->execute();
	$lastInsertId=$dbconnect->lastInsertId();
	if(isset($previousParID)){
	if($previousParID!==NULL || !empty($previousParID)){
		$prepare=$dbconnect->prepare('UPDATE candidateandelection SET precID=:precID WHERE precID=:previousprecId');
		$prepare->bindParam(':precID',$lastInsertId);
		$prepare->bindParam(':previousprecId',$previousParID);
		$prepare->execute();		
	}
	}
    }

	foreach ($_SESSION as $key => $value) {
		foreach ($value as $key1 => $value2) {
			$idd=explode("=",$key1);
			if($idd[1]==$id)
			{
				$_SESSION[$key][$key1]=0;
			}
		}
	}
    header('Location:admin_dashboard/manageCandidate.php');
 }
if(isset($_POST['postp']) && $_POST['postp']=='insert'){
	$trgt_dir="../upload_files/";
	$filez=$_FILES['image'];
	$trgt_file=$trgt_dir.basename($filez["name"]);
	$uploadOk = 1;
	$imgtype=strtolower(pathinfo($trgt_file,PATHINFO_EXTENSION));
	$check = getimagesize($filez["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
    if($imgtype != "jpg" && $imgtype != "png" && $imgtype !="jpeg" && $imgtype != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
    }
    if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
    } else {
    if (!move_uploaded_file($filez["tmp_name"], $trgt_file)) {
         echo "Sorry, there was an error uploading your file.";
    } else{
    	$prepared=$dbconnect->prepare("INSERT INTO candidates (name,nrc,nationality,religious,email,address,phone_no,image) VALUES(:name,:nrc,:nationality,:religious,:email,:address,:phoneno,:image)");
	$prepared->bindParam(':name',$name);
	$prepared->bindParam(':nrc',$nrc);
	$prepared->bindParam(':nationality',$nationality);
	$prepared->bindParam(':religious',$religious);
	$prepared->bindParam(':email',$email);
	$prepared->bindParam(':address',$address);
	$prepared->bindParam(':phoneno',$phoneno);
	$prepared->bindParam(':image',$image);
	$name=$_POST['cname'];
	$nrc=$_POST['nrc'];
	$nationality=$_POST['nationality'];
	$religious=$_POST['religious'];
	$email=$_POST['email'];
	$address=$_POST['address'];
	$phoneno=$_POST['phone'];
	$image=$trgt_file;
	$prepared->execute();
	$last_id=$dbconnect->lastInsertId();
	$prepared=$dbconnect->prepare("INSERT INTO party_records(cid,parid,status) VALUES(:cid,:parid,1)");
	$prepared->bindParam(":cid",$last_id);
	$prepared->bindParam(":parid",$partyid);
	if((int)$_POST['party']==0){
		$partyid=NULL;
	}
	else{
		$partyid=$_POST['party'];
	}
	$prepared->execute();
	echo "Successfully Inserted!!!";   	
    }}	
?>
<?php
	}}else{
if($_GET['pid']==1){
 	$active_Arr=[];
 	if(isset($_COOKIE['user']) && !empty($_COOKIE['user'])){

 		$raw_voterID=$_COOKIE['user'];
   	 	$raw_voterID=preg_split('/\-\|\-/', $raw_voterID);
    	$voterID = $raw_voterID[1];
    	$prepared = $dbconnect->prepare('SELECT eid,conid FROM voters WHERE voter_id = :voterID');
    	$prepared->bindParam(':voterID',$voterID);
    	$prepared->execute();
    	$prepared->setFetchMode(PDO::FETCH_ASSOC);
    	$ids = $prepared->fetch();
    	$active_Arr[0] = $ids['eid'];
    	$active_Arr[1] = $ids['conid'];

 	}
	else if(isset($_SESSION) && !empty($_SESSION)){
		$active_Arr[0]=$_SESSION['eid'];
		$active_Arr[1]=$_SESSION['conid'];
	}
	else
	{
	$tmp_prepared=$dbconnect->prepare('SELECT eid,conid FROM candidateandelection INNER JOIN counts ON candidateandelection.ceid=counts.ceid ORDER BY counts.vid DESC LIMIT 1');
	$tmp_prepared->execute();
	$tmp_prepared->setFetchMode(PDO::FETCH_ASSOC);
	$non_SessionResults=$tmp_prepared->fetch();
	$active_Arr[0]=$non_SessionResults['eid'];
	$active_Arr[1]=$non_SessionResults['conid'];
	}
	$prepared=$dbconnect->prepare("SELECT image,name,party_name_S,party_name_L,logo,vote_count FROM candidates INNER JOIN party_records ON candidates.cid=party_records.cid LEFT OUTER JOIN parties ON party_records.parid=parties.parid JOIN candidateandelection ON candidateandelection.precID=party_records.precID JOIN counts ON candidateandelection.ceid=counts.ceid WHERE candidateandelection.eid=:eid AND candidateandelection.conid=:conid ORDER BY counts.vote_count DESC,candidates.name ASC");
	$prepared->bindParam(':eid',$active_Arr[0]);
	$prepared->bindParam(':conid',$active_Arr[1]);
 	$prepared->execute();
 	$prepared->setFetchMode(PDO::FETCH_ASSOC);
 	$results=$prepared->fetchAll();

 	$prepared=$dbconnect->prepare("SELECT image,name,party_name_S,party_name_L,logo FROM candidates JOIN party_records AS pr_1 ON candidates.cid=pr_1.cid LEFT OUTER JOIN parties ON pr_1.parid=parties.parid JOIN candidateandelection ON candidateandelection.precID=pr_1.precID WHERE NOT EXISTS (SELECT * FROM party_records AS pr_2 INNER JOIN candidateandelection ON pr_2.precID=candidateandelection.precID JOIN counts ON candidateandelection.ceid=counts.ceid WHERE candidateandelection.eid=:eid AND candidateandelection.conid=:conid AND pr_1.precID=pr_2.precID) AND candidateandelection.eid=:eid AND candidateandelection.conid=:conid ORDER BY candidates.name ASC");
 	$prepared->bindParam(":eid",$active_Arr[0]);
 	$prepared->bindParam(":conid",$active_Arr[1]);
 	$prepared->execute();
 	$prepared->setFetchMode(PDO::FETCH_ASSOC);
 	$temp_result=$prepared->fetchAll();
 	foreach ($temp_result as $tmp_value) {
 		array_push($results, $tmp_value);
 	}


 	$total=0;
 	for($j=0;$j<count($results);$j++){
 		if(!isset($results[$j]['vote_count'])){
 			continue;
 	    }
 	    $total+=$results[$j]['vote_count'];
 	}
 	$colorArr=array('bg-success','bg-primary','bg-warning','bg-danger');
 	foreach ($results as $key => $value) {
 	if(!$total==0 && isset($value['vote_count'])){
 	$percentage=($value['vote_count']/$total)*100;}
 	else{$percentage=0;}
 	$percentage=round($percentage,2);
 		?>
 		<tr>
 		<td><img src="<?php echo $value['image'] ?>" style="width: 80px;height: 80px;" alt="pp"></td>
 		<td><?php echo $value['name']; ?></td>
 		<td data-toggle="popover" data-partyname="<?php echo $value['party_name_L'] ? $value['party_name_L'] : '<b>Non-Partisan</b>' ; ?>" data-logo="<?php echo $value['logo'] ? $value['logo'] : 'uploads/nonpartisanLogo-restricted.png' ; ?>">
 			<p class="selector-text">
 				<?php echo $value['party_name_S'] ? $value['party_name_S'] : '<b>None</b>';?>
 			</p>
 		</td>
 		<?php $personelVote = isset($value['vote_count']) ? $value['vote_count'] : 0 ; ?>
 		<td><input type="hidden" name="vote-count" value="<?php echo $personelVote; ?>">Votes : <?php echo $personelVote; ?> / <?php echo $total; ?></td>
 		<td><div class="progress" style="height: 40px;">
  <div class="progress-bar <?php if($percentage<=25.0) echo $colorArr[3];if($percentage>25.0 && $percentage<=50.0) echo $colorArr[2];if($percentage>50.0 && $percentage<=75.0 ) echo $colorArr[1];if($percentage>75.0 && $percentage<=100) echo $colorArr[0]; ?> progress-bar-striped progress-bar-animated<?php if($percentage == 0) echo ' text-dark'; ?>" role="progressbar" aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percentage; ?>%"><?php echo $percentage; ?>%</div>
</div></td>
</tr>
 		<?php
 	}
 }	
 elseif($_GET['pid']==2){
 	$eid = $_GET['eid'];
 	$query = "SELECT name, party_name_L, party_name_S, (SUM(IFNULL(vote_count,0)) / ".
 	"(SELECT SUM(IFNULL(vote_count,0)) FROM party_records".
		" INNER JOIN candidateandelection ON party_records.precID = candidateandelection.precID".
		" LEFT OUTER JOIN counts ON candidateandelection.ceid = counts.ceid".
		" WHERE candidateandelection.eid = :eid ) * 100 ) AS percentage".	
	" FROM party_records".
	" INNER JOIN candidates ON candidates.cid = party_records.cid".
	" LEFT OUTER JOIN parties ON parties.parid = party_records.parid".
	" INNER JOIN candidateandelection ON candidateandelection.precID = party_records.precID". 
	" LEFT JOIN counts ON candidateandelection.ceid = counts.ceid".
	" WHERE candidateandelection.eid = :eid GROUP BY IFNULL( party_records.parid, UUID())".
	" ORDER BY percentage DESC,party_name_L ASC";
 	$prepared = $dbconnect->prepare($query);
 	$prepared->bindParam(':eid',$eid);
 	$prepared->execute();
 	$prepared->setFetchMode(PDO::FETCH_ASSOC);
 	$results = $prepared->fetchAll();
 	echo json_encode($results);
 }
 elseif($_GET['pid']==3){
 	$prepared=$dbconnect->prepare("SELECT candidates.*,parties.party_name_S FROM candidates JOIN party_records ON candidates.cid=party_records.cid JOIN parties ON party_records.parid=parties.parid WHERE party_records.status=1");
 	$prepared->execute();
 	$prepared->setFetchMode(PDO::FETCH_ASSOC);
 	$finalresult=$prepared->fetchAll();
 	$finalresult=json_encode($finalresult);
 	echo $finalresult;
 }
 elseif($_GET['pid']==4){
 	$prepared=$dbconnect->prepare("SELECT candidates.*,parties.parid FROM candidates JOIN party_records ON candidates.cid=party_records.cid LEFT OUTER JOIN parties ON party_records.parid=parties.parid WHERE candidates.cid=:id
 		AND party_records.status=1");
 	$prepared->bindParam(':id',$id);
 	$id=$_GET['editid'];
 	$prepared->execute();
 	$prepared->setFetchMode(PDO::FETCH_ASSOC);
 	$outputs=$prepared->fetchAll();
 	echo $jsonobj=json_encode($outputs);
 }
 elseif($_GET['pid']==5){
 	showVote_Candidates($dbconnect,$_GET['e'],$_GET['c'],isset($_GET['v']) ? $_GET['v'] : null);
 }
 elseif($_GET['pid']==6){
 	$delID=$_GET['delid'];
 	$prepared=$dbconnect->prepare('DELETE FROM candidateandelection WHERE ceid=:ceid');
 	$prepared->bindParam(':ceid',$delID);
 	$prepared->execute();
 }
 elseif($_GET['pid']==7){
 	$year=$_GET['y'];$month=$_GET['m'];
 	$prepared=$dbconnect->prepare("SELECT candidates.name,candidates.image,counts.vote_count FROM candidates INNER JOIN party_records ON candidates.cid=party_records.cid INNER JOIN counts ON party_records.precID=counts.precID WHERE period_year=:year AND period_month=:month AND status=1 ORDER BY counts.vote_count DESC,candidates.name ASC");
	$prepared->bindParam(":year",$year);
	$prepared->bindParam(":month",$month);
 	$prepared->execute();
 	$prepared->setFetchMode(PDO::FETCH_ASSOC);
 	$results=$prepared->fetchAll();
 	$prepared=$dbconnect->prepare("SELECT name,image FROM candidates WHERE NOT EXISTS(SELECT cid FROM party_records INNER JOIN counts ON party_records.precID=counts.precID WHERE period_month=:month AND period_year=:year AND status=1 AND candidates.cid=party_records.cid) ORDER BY name ASC");
	$prepared->bindParam(":year",$year);
	$prepared->bindParam(":month",$month);
 	$prepared->execute();
 	$rc=$prepared->rowCount();
 	$prepared->setFetchMode(PDO::FETCH_ASSOC);
 	$results2=$prepared->fetchAll();
 	if($rc>0){
 	foreach ($results2 as $value) {
 		array_push($results,$value);
 	}
    }

 	$total=0;
 	for($j=0;$j<count($results);$j++){
 		if(isset($results[$j]['vote_count'])) {$total+=$results[$j]['vote_count'];}
 	}
 	$colorArr=array('bg-success','bg-primary','bg-warning','bg-danger');
 	foreach ($results as $key => $value) {
 	if(!$total==0){
 	isset($value['vote_count']) ? $percentage=($value['vote_count']/$total)*100 : $percentage=0;}
 	else{$percentage=0;}
 	$percentage=round($percentage,2);
 		?>
 		<tr>
 		<td><img src="<?php echo $value['image']; ?>" style="width: 80px;height: 80px;" alt="pp"></td>
 		<td><?php echo $value['name']; ?></td>
 <td>Votes : <?php echo isset($value['vote_count']) ? $value['vote_count'] : 0 ; ?>/<?php echo $total; ?></td>
 		<td><div class="progress" style="height: 40px;">
  <div class="progress-bar <?php if($percentage<=25.0) echo $colorArr[3];if($percentage>25.0 && $percentage<=50.0) echo $colorArr[2];if($percentage>50.0 && $percentage<=75.0 ) echo $colorArr[1];if($percentage>75.0 && $percentage<=100) echo $colorArr[0]; ?> progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percentage; ?>%"><?php echo $percentage; ?>%</div>
</div></td>
</tr>
 		<?php
 	}
 }
 else echo "Invalid PID";
}
 	}catch(PDOException $e){ echo $e->getMessage(); }
 $dbconnect=null;
function showVote_Candidates( $dbc, $e, $c, $v){
	$disabled;
	if ($v !== null){
	$prepared=$dbc->prepare("SELECT is_voted FROM voters WHERE voter_id=:voterID");
	$prepared->bindParam(':voterID',$v);
	$prepared->execute();
	$prepared->setFetchMode(PDO::FETCH_ASSOC);
	$isVoted=$prepared->fetch();
	$disabled = $isVoted['is_voted'] == 0 ? false : true ;
	}
	else {
		$disabled = true;
	}
	$prepared=$dbc->prepare("SELECT candidates.*,parties.logo,party_records.precID FROM candidates INNER JOIN party_records ON candidates.cid=party_records.cid LEFT JOIN parties ON party_records.parid=parties.parid JOIN candidateandelection ON candidateandelection.precID=party_records.precID WHERE candidateandelection.eid=:eid AND candidateandelection.conid=:conid AND party_records.status=1");
	$prepared->bindParam(':eid',$e);
	$prepared->bindParam(':conid',$c);
 	$prepared->execute();
 	$prepared->setFetchMode(PDO::FETCH_ASSOC);
 	$finalresult=$prepared->fetchAll();
 	$counts=0;$start=0;$end=0;
 	foreach($finalresult as $value) {
 		$counts++;
 		?>
 		<?php $start=$end+1; if($start==1 || $start==$counts) {
 		$end=$start+3; ?>
			<div class="row">
		<?php } ?>
            <div class="col">
                <div class="card hovercard" style="color: #004085;background-color: #cce5ff;border-color: #b8daff;margin-bottom: 20px;" >
 <div class="cardheader" style="background: url('<?php echo $value['logo'] ? $value['logo'] : 'uploads/nonpartisanLogo-restricted.png'; ?>');background-size: cover;height: 135px;width: 100%;background-position: center;"> </div>
                <div class="avatar">
                    <img alt="" src="<?php echo $value['image']; ?>">
                </div>
                <div class="info">
                    <div class="title">
                        <label for="name"><?php echo $value['name']; ?></label>
                    </div>                    
                </div>
                <div class="bottom">
                    <td>
                        <nobr>
                     <button type="button" data-toggle="modal" data-target="#exampleModalScrollable" class="btn btn-<?php echo $disabled ? 'secondary' : 'primary' ; ?> btn-lg" name= "btnVote" value="<?php echo $value['precID']; ?>" style="width: 100px; height: 40px;line-height:18px;" <?php if($disabled) echo 'disabled'; ?>>Vote</button>
					<a href="profile.php?cid=<?php echo $value['cid']; ?>" class="btn btn-success btn-lg" style="width: 100px;height:40px;line-height:23px;">Profile</a>
                        </nobr> 
                    </td> 
                </div>
            </div>
            </div>
		<?php if($counts==$end) { ?>
				</div>
		<?php } } ?>
<?php  } ?> 