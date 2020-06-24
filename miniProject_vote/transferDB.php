<?php
$host="localhost";
$username="root";
$password="";
include 'include/strong-passwords.php';
try{
	$dbconnect=new PDO("mysql:host=$host;dbname=test",$username,$password);
$dbconnect->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
function is_ajax(){
	return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest';
}
if($_SERVER['REQUEST_METHOD']=='POST'){
	if(isset($_POST) && !empty($_POST)){
		if(isset($_POST['pid']) && $_POST['pid']==1){
			if(isset($_FILES['logo'])){
			$file=$_FILES['logo'];
			$path=uploadFile($file);
			if(!$path){
				echo "Inserted Failed";
			}
			else{
			$partynameL=$_POST['partynameL'];
			$partynameS=$_POST['partynameS'];
			$descri=$_POST['desc'];
			$stmt=$dbconnect->prepare("INSERT INTO parties(party_name_L,party_name_S,logo,description) VALUES (:partynameL,:partynameS,:logo,:descri)");
			$stmt->bindParam(":partynameL",$partynameL);
			$stmt->bindParam(":partynameS",$partynameS);
			$stmt->bindParam(":descri",$descri);
			$stmt->bindParam(":logo",$path);
			$stmt->execute();
			echo "Inserted Successfully";
		} }
		else{
			echo "You must insert a logo in order to represent";
		}
	}
	if(isset($_POST['pid']) && $_POST['pid']==2)
	{
		$eRemark=$_POST['eRemark'];
		$eBegin=$_POST['eBegin'];
		$eDay=$_POST['eday'];
		$eName=$_POST['ename'];
		if(isset($_POST['eEnd']) && !empty(($_POST['eEnd']))){
			$eEnd=$_POST['eEnd'];
		}
		else {
			$eEnd=NULL;
		}
		$stmt=$dbconnect->prepare("INSERT INTO elections (ename,edate,eyear_begin,eyear_end) VALUES (:ename,:edate,:eyear_begin,:eyear_end)");
		$stmt->bindParam(':ename',$eName);
		$stmt->bindParam(':edate',$eDay);
		$stmt->bindParam(':eyear_begin',$eBegin);
		$stmt->bindParam(':eyear_end',$eEnd);
		$stmt->execute();
		echo "---Inserted Successfully---";
	}
	if(isset($_POST['pid']) && $_POST['pid']==3){
		$id=$_POST['eparID'];
		$originSrc=$_POST['originSrc'];
		$partynameL=$_POST['partynameL'];
		$partynameS=$_POST['partynameS'];
		$descri=$_POST['desc'];
		$willPath;
		if($_FILES['logofile']['error']==4){
			$willPath=$originSrc;
		}
		else{
		$stmt=$dbconnect->prepare('SELECT logo FROM parties WHERE parid=:id');
		$stmt->bindParam(':id',$id);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$imagetoDel=$stmt->fetch();
		unlink($imagetoDel['logo']);
			$willPath=uploadFile($_FILES['logofile']);
		}

		$stmt=$dbconnect->prepare('UPDATE parties SET party_name_L=:partynameL,party_name_S=:partynameS,logo=:willPath,description=:descri WHERE parid=:id');
		$stmt->bindParam(':partynameL',$partynameL);
		$stmt->bindParam(':partynameS',$partynameS);
		$stmt->bindParam(':descri',$descri);
		$stmt->bindParam(':willPath',$willPath);
		$stmt->bindParam(':id',$id);
		$stmt->execute();
		header('Location:admin_dashboard/manageParties.php');
	}
	if(isset($_POST['pid']) && $_POST['pid']==4){
		print_r($_POST);
		$id=$_POST['editid'];
		$ename=$_POST['ename'];
		$eday=$_POST['eday'];
		$eBegin=$_POST['eBegin'];
		$eEnd=$_POST['eEnd'];
		$eRemark=$_POST['eRemark'];
		$stmt=$dbconnect->prepare('UPDATE elections SET ename=:ename,edate=:eday,eyear_begin=:eBegin,eyear_end=:eEnd,remark=:eRemark WHERE eid=:id');
		$stmt->bindParam(':id',$id);
		$stmt->bindParam(':ename',$ename);
		$stmt->bindParam(':eday',$eday);
		$stmt->bindParam(':eBegin',$eBegin);
		$stmt->bindParam(':eEnd',$eEnd);
		$stmt->bindParam(':eRemark',$eRemark);
		$stmt->execute();
		header('Location:admin_dashboard/manageElections.php');
	}
	if(isset($_POST['pid']) && $_POST['pid']==5){
		$id=(int)$_POST['editid'];
		$conname=$_POST['con_name'];
		$township=$_POST['township'];
		$stmt=$dbconnect->prepare('UPDATE constituency SET con_name=:conname,township=:township WHERE conid=:id');
		$stmt->bindParam(':id',$id);
		$stmt->bindParam(':conname',$conname);
		$stmt->bindParam(':township',$township);
		$stmt->execute();
		header('Location:admin_dashboard/manageConstituency.php');
	}
	if(isset($_POST['pid']) && $_POST['pid']==6){
		$objArr=json_decode($_POST['json']);
		$constituency=(int)$objArr->chosenConstituency;
		$election=(int)$objArr->chosenElection;
		$candidate=(int)$objArr->chosenCandidate;
		$party=(int)$objArr->chosenParty;
		if($party===0){
		$stmt=$dbconnect->prepare('INSERT INTO candidateandelection(conid,eid,precID) VALUES(:conid,:eid,(SELECT precID FROM party_records WHERE cid=:cid AND parid IS :parid AND status=1))');
		$party=NULL;
		}
		else
		{
		$stmt=$dbconnect->prepare('INSERT INTO candidateandelection(conid,eid,precID) VALUES(:conid,:eid,(SELECT precID FROM party_records WHERE cid=:cid AND parid=:parid AND status=1))');
	    }
		$stmt->bindParam(':conid',$constituency);
		$stmt->bindParam(':eid',$election);
		$stmt->bindParam(':cid',$candidate);
		$stmt->bindParam(':parid',$party);
		$stmt->execute();
		echo 'Uploaded Successfully';
    } 
    if(isset($_POST['pid']) && $_POST['pid']==7){
    	$nrc=$_POST['nrc'];
    	$code=$_POST['code'];
    	echo $nrc;echo $code;
    	$stmt=$dbconnect->prepare('SELECT u_name,voter_id FROM voters INNER JOIN users ON voters.uid=users.uid WHERE u_nrc=:nrc AND code=:code');
    	$stmt->bindParam(':nrc',$nrc);
    	$stmt->bindParam(':code',$code);
    	$stmt->execute();
    	$isValid=$stmt->rowCount();
    	if($isValid>0){
    		echo 'Access Granted';
    		$stmt->setFetchMode(PDO::FETCH_ASSOC);
    		$cookieName=$stmt->fetch();
    		$voterID=$cookieName['voter_id'];
    		$cookieName=$cookieName['u_name'];
    		if(!isset($_COOKIE[$cookieName])) {
   				 setcookie('user', $cookieName.'-|-'.$voterID, time() + 86400, "/");
			}
    		header('Location:index.php');
    	}
    	else
    	{
    		echo 'Access Denied';
    		header('Location:error_msg2.php');
    	}
    }
	if(isset($_POST['j'])){
		header('Content-Type:application/json;charset=UTF-8');
		$obj=json_decode($_POST['j'],false);
		$con_name=$obj[0]->con_name;
		$township=$obj[0]->township;
		$stmt=$dbconnect->prepare("INSERT INTO constituency(con_name,township) VALUES(:con_name,:township)");
		$stmt->bindParam(':con_name',$con_name);
		$stmt->bindParam(':township',$township);
		$stmt->execute();
		echo 'Inserted Successfully!';
	}
	}
}
if(isset($_GET) && !empty($_GET)){
	if($_GET['pid']==1){
		if(isset($_GET['e']) && !isset($_GET['c']))
		{
		$election=$_GET['e'];
		$prepared=$dbconnect->prepare('SELECT constituency.* FROM constituency JOIN candidateandelection ON constituency.conid=candidateandelection.conid JOIN elections ON elections.eid=candidateandelection.eid INNER JOIN counts ON candidateandelection.ceid=counts.ceid WHERE candidateandelection.eid=:eid ORDER BY conid');
		$prepared->bindParam(':eid',$election);
		$prepared->execute();
		$prepared->setFetchMode(PDO::FETCH_ASSOC);
		$queries=$prepared->fetchAll();
		echo json_encode($queries);
		}
		else{
		$election=$_GET['e'];
		$constituency=$_GET['c'];
	$prepared=$dbconnect->prepare("SELECT image,name,party_name_S,vote_count FROM candidates INNER JOIN party_records ON candidates.cid=party_records.cid LEFT JOIN parties ON party_records.parid=parties.parid JOIN candidateandelection ON candidateandelection.precID=party_records.precID JOIN counts ON candidateandelection.ceid=counts.ceid WHERE candidateandelection.eid=:eid AND candidateandelection.conid=:conid ORDER BY counts.vote_count DESC,candidates.name ASC");
	$prepared->bindParam(':eid',$election);
	$prepared->bindParam(':conid',$constituency);
 	$prepared->execute();
 	$prepared->setFetchMode(PDO::FETCH_ASSOC);
 	$results=$prepared->fetchAll();

 	$prepared=$dbconnect->prepare("SELECT image,name,party_name_S FROM candidates JOIN party_records AS pr_1 ON candidates.cid=pr_1.cid LEFT JOIN parties ON pr_1.parid=parties.parid JOIN candidateandelection ON candidateandelection.precID=pr_1.precID WHERE NOT EXISTS (SELECT * FROM party_records AS pr_2 INNER JOIN candidateandelection ON pr_2.precID=candidateandelection.precID JOIN counts ON candidateandelection.ceid=counts.ceid WHERE candidateandelection.eid=:eid AND candidateandelection.conid=:conid AND pr_1.precID=pr_2.precID) AND candidateandelection.eid=:eid AND candidateandelection.conid=:conid ORDER BY candidates.name ASC");
 	$prepared->bindParam(":eid",$election);
 	$prepared->bindParam(":conid",$constituency);
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
 		<td><?php echo $value['party_name_S'] ? $value['party_name_S'] : '<b>None</b>';?> </td>
 <td>Votes : <?php echo isset($value['vote_count']) ? $value['vote_count'] : 0 ; ?>/<?php echo $total; ?></td>
 		<td><div class="progress" style="height: 40px;">
  <div class="progress-bar <?php if($percentage<=25.0) echo $colorArr[3];if($percentage>25.0 && $percentage<=50.0) echo $colorArr[2];if($percentage>50.0 && $percentage<=75.0 ) echo $colorArr[1];if($percentage>75.0 && $percentage<=100) echo $colorArr[0]; ?> progress-bar-striped progress-bar-animated<?php if($percentage == 0) echo ' text-dark'; ?>" role="progressbar" aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percentage; ?>%"><?php echo $percentage; ?>%</div>
</div></td>
</tr>
 		<?php
 	}	}  
	}
	if($_GET['pid']==2){
		if(isset($_GET['e']) && !isset($_GET['c'])){
			$eid = $_GET['e'];
			$stmt = $dbconnect->prepare('SELECT constituency.* FROM constituency INNER JOIN candidateandelection ON candidateandelection.conid = constituency.conid WHERE candidateandelection.eid = :eid ORDER BY constituency.conid');
			$stmt->bindParam(':eid',$eid);
			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$elections = $stmt->fetchAll();
			echo json_encode($elections);
		}
	}
	if($_GET['pid']==3){
		$id=$_GET['q'];
		$id=explode('_', $id);
		$id=(int)$id[1];
		$stmt=$dbconnect->prepare("SELECT logo FROM parties WHERE parid=:id");
		$stmt->bindParam(':id',$id);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$imagetoDel=$stmt->fetch();
		$imagetoDel=$imagetoDel['logo'];
		unlink($imagetoDel);
		$stmt=$dbconnect->prepare("DELETE FROM parties WHERE parid=:id");
		$stmt->bindParam(':id',$id);
		$stmt->execute();
		echo "Deleted Successfully!";
	}
	if($_GET['pid']==4){
		$id=$_GET['q'];
		$id=explode('-',$id);
		$id=(int)$id[1];
		$stmt=$dbconnect->prepare('DELETE FROM elections WHERE eid=:id');
		$stmt->bindParam(':id',$id);
		$stmt->execute();
		echo "Deleted Successfully"; 
	}
	if($_GET['pid']==5){
		$id=$_GET['q'];
		$id=explode('-', $id);
		$id=(int)$id[1];
		$stmt=$dbconnect->prepare('DELETE FROM constituency WHERE conid=:id');
		$stmt->bindParam(':id',$id);
		$stmt->execute();
		echo "Deleted Successfully";
	}
	if($_GET['pid']==6){
		if(isset($_GET['e'])){
			$eid=(int)$_GET['e'];
			$stmt=$dbconnect->prepare('SELECT constituency.* FROM constituency JOIN candidateandelection ON constituency.conid=candidateandelection.conid WHERE candidateandelection.eid=:eid ORDER BY conid DESC');
			$stmt->bindParam(':eid',$eid);
			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$results=$stmt->fetchAll();
			echo json_encode($results);
		}
	}
	if($_GET['pid']==7){
		$id=isset($_GET['rid']) ? $_GET['rid'] : $_GET['aid'];
		if(isset($_GET['aid']) && !empty($_GET['aid'])){
		$generated_code;
			$stmt=$dbconnect->prepare('INSERT INTO users(u_name,u_nrc,nrc_front_img,nrc_back_img) SELECT pu_name,pu_nrc,nrc_front_img,nrc_back_img FROM pending_users WHERE puid=:puid');
			$stmt->bindParam(':puid',$id);
			$stmt->execute();
			$lastInsertId=$dbconnect->lastInsertId();
			$stmt=$dbconnect->prepare('SELECT electionID,constituencyID FROM pending_users WHERE puid=:puid');
			$stmt->bindParam(':puid',$id);
			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$twoIDs=$stmt->fetch();
		$codeExists;		
		do{
		$generated_code=generateStrongPassword(5,false,'luds');
		$generated_code='e-'.$twoIDs['electionID'].'c-'.$twoIDs['constituencyID'].'_'.$generated_code;
		$stmt=$dbconnect->prepare('SELECT * FROM voters WHERE code=:code');
		$stmt->bindParam(':code',$generated_code);
		$stmt->execute();$codeExists=$stmt->rowCount();
		}while ($codeExists>0);
		$stmt=$dbconnect->prepare('INSERT INTO voters(uid,eid,conid,code) VALUES(:uid,:eid,:conid,:code)');
		$stmt->bindParam(':uid',$lastInsertId);
		$stmt->bindParam(':eid',$twoIDs['electionID']);
		$stmt->bindParam(':conid',$twoIDs['constituencyID']);
		$stmt->bindParam(':code',$generated_code);
		$stmt->execute();

		}
		$stmt=$dbconnect->prepare('DELETE FROM pending_users WHERE puid=:puid');
		$stmt->bindParam(':puid',$id);
		$stmt->execute();
		$stmt=$dbconnect->prepare('SELECT pending_users.*,ename,township FROM pending_users INNER JOIN elections ON elections.eid=pending_users.electionID INNER JOIN constituency ON constituency.conid=pending_users.constituencyID');
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$finalResults=$stmt->fetchAll();
		echo json_encode($finalResults);
	}
	if($_GET['pid']==8){

	}
	if($_GET['pid']==9){
		$eid=$_GET['e'];$conid=$_GET['c'];
		$stmt=$dbconnect->prepare('SELECT * FROM candidateandelection');
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		echo json_encode($stmt->fetchAll());
	}
	if($_GET['pid']==10){
		$eid=$_GET['e'];$conid=$_GET['c'];
		$stmt=$dbconnect->prepare('SELECT * FROM candidateandelection');
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$cAnde=$stmt->fetchAll();
		$options=0;$precID;$ceid;
		//options def : all can be same => 0 , candidate cannot be same => 1 , candidate and party cannot be same => 2
		foreach ($cAnde as $value) {
			if($value['eid']==$eid){
				$options=1;
				$precID=$value['precID'];
				$ceid=$value['ceid'];
			}
			if($value['eid']==$eid && $value['conid']==$conid){
				$options=2;
				$precID=$value['precID'];
				$ceid=$value['ceid'];
				break;
			}
		}
		switch ($options) {
			case 0:
					$stmt=$dbconnect->prepare('SELECT candidates.cid,name,image,parties.parid,party_name_L,logo FROM candidates JOIN party_records ON candidates.cid=party_records.cid LEFT OUTER JOIN parties ON party_records.parid=parties.parid WHERE status=1');
					$stmt->bindParam(':precID',$precID);
					$stmt->execute();
					$stmt->setFetchMode(PDO::FETCH_ASSOC);
					$results=$stmt->fetchAll();
					echo json_encode($results);
					break;
			case 1:
					$stmt=$dbconnect->prepare('SELECT candidates.cid,name,image,parties.parid,party_name_L,logo FROM candidates INNER JOIN party_records ON candidates.cid=party_records.cid LEFT OUTER JOIN parties ON party_records.parid=parties.parid WHERE status=1 AND party_records.cid NOT IN (SELECT party_records.cid FROM party_records INNER JOIN candidateandelection ON party_records.precID=candidateandelection.precID WHERE candidateandelection.eid=:eid)');
					$stmt->bindParam(':eid',$eid);
					$stmt->execute();
					$stmt->setFetchMode(PDO::FETCH_ASSOC);
					$results=$stmt->fetchAll();
					echo json_encode($results);
			   	break;
			case 2:
					$stmt=$dbconnect->prepare('SELECT candidates.cid,name,image,parties.parid,party_name_L,logo FROM candidates JOIN party_records ON candidates.cid=party_records.cid LEFT JOIN parties ON party_records.parid=parties.parid WHERE status=1 AND NOT EXISTS (SELECT parid FROM party_records AS pr WHERE pr.precID IN (SELECT precID FROM candidateandelection WHERE eid=:eid AND conid=:conid) AND pr.parid=party_records.parid) AND party_records.cid NOT IN (SELECT party_records.cid FROM party_records INNER JOIN candidateandelection ON party_records.precID=candidateandelection.precID WHERE candidateandelection.eid=:eid)');
					$stmt->bindParam(':precID',$precID);
					$stmt->bindParam(':eid',$eid);
					$stmt->bindParam(':conid',$conid);
					$stmt->execute();
					$stmt->setFetchMode(PDO::FETCH_ASSOC);
					$results=$stmt->fetchAll();
					echo json_encode($results);
				break;
			default:
					echo 'Error! Options Value is not Defined';
				break;
		}
	}
	if($_GET['pid']==11){
		if(isset($_GET['e'])){
			$eid=(int)$_GET['e'];
			$stmt=$dbconnect->prepare('SELECT constituency.* FROM constituency JOIN candidateandelection ON constituency.conid=candidateandelection.conid INNER JOIN counts ON candidateandelection.ceid=counts.ceid WHERE candidateandelection.eid=:eid ORDER BY conid DESC');
			$stmt->bindParam(':eid',$eid);
			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$results=$stmt->fetchAll();
			echo json_encode($results);
		}
	}
	if($_GET['pid']==12){
		$stmt=$dbconnect->prepare('SELECT candidateandelection.ceid,ename,edate,township,image,name,nrc,party_name_S,party_name_L FROM candidateandelection JOIN elections ON candidateandelection.eid=elections.eid JOIN constituency ON candidateandelection.conid=constituency.conid JOIN party_records ON candidateandelection.precID=party_records.precID JOIN candidates ON party_records.cid=candidates.cid LEFT JOIN parties ON party_records.parid=parties.parid ORDER BY ename,edate,township ASC');
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$completed=$stmt->fetchAll();
		foreach ($completed as $key => $value) {
		 		$value=DateTime::createFromFormat('Y-m-d',$value['edate']);
		 		$completed[$key]['edate']=$value->format('jS F Y');
		}
		echo json_encode($completed);
	}
}
// Free Space in pid=8 (GET)
}catch(PDOException $e){
	echo "Connection Failed ".$e->getMessage();
}
$dbconnect=null;
 function uploadFile($input_file)
{
	$target_dir = "../upload_files/";
$target_file = $target_dir . basename($input_file["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $check = getimagesize($input_file["tmp_name"]);
    if($check !== false) {
        // echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// if ($input_file["size"] > 500000) {
//     echo "Sorry, your file is too large.";
//     $uploadOk = 0;
// }
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
} else {
    if (move_uploaded_file($input_file["tmp_name"], $target_file)) {
        // echo "The file ". basename( $input_file["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
if($uploadOk==0){
	return false;
}
else{ return $target_file; }
}
 ?>
