<?php
	session_start();
	if($_SERVER['REQUEST_METHOD']=='POST')
	{	
		$data = file_get_contents( "php://input" );
		$arr=explode(",", $data);
		$eid=$arr[0];$conid=$arr[1];$precID=$arr[2];

		$eid=explode('=', $eid);		$eid=(int)$eid[1];
		$conid=explode('=', $conid);	$conid=(int)$conid[1];
		$precID=explode('=', $precID);	$precID=(int)$precID[1];

		try {
			$temp_con=new  PDO('mysql:host=localhost;dbname=test;','root','');
			$temp_con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			if(!isset($_SESSION) || empty($_SESSION))
			{
				$stmt=$temp_con->prepare('DELETE FROM counts WHERE ceid IN (SELECT ceid FROM candidateandelection WHERE conid=:conid AND eid=:eid)');
				$stmt->bindParam(':conid',$conid);
				$stmt->bindParam(':eid',$eid);
				$stmt->execute();
			}
			$stmt=$temp_con->prepare('SELECT vote_count,ceid FROM counts WHERE ceid IN (SELECT ceid FROM candidateandelection WHERE conid=:conid AND eid=:eid AND precID=:precID)');
			$stmt->bindParam(':conid',$conid);
			$stmt->bindParam(':eid',$eid);
			$stmt->bindParam(':precID',$precID);
			$stmt->execute();
			$isExists=$stmt->rowCount();
			if($isExists>0){
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$queries=$stmt->fetch();
			$stmt=$temp_con->prepare('UPDATE counts SET vote_count=:vote_count WHERE ceid=:ceid');
			$addedVote=(INT)$queries['vote_count']+1;
			$stmt->bindParam(':vote_count',$addedVote);
			$stmt->bindParam(':ceid',$queries['ceid']);
			$stmt->execute();
			}
			else
			{
			$stmt=$temp_con->prepare('INSERT INTO counts(ceid) SELECT ceid FROM candidateandelection WHERE conid=:conid AND eid=:eid AND precID=:precID');
			$stmt->bindParam(':conid',$conid);
			$stmt->bindParam(':eid',$eid);
			$stmt->bindParam(':precID',$precID);
			$stmt->execute();
			}
			$raw_voterID=$_COOKIE['user'];
			$raw_voterID=preg_split('/\-\|\-/', $raw_voterID);
			$voterID=$raw_voterID[1];
			$stmt=$temp_con->prepare('UPDATE voters SET is_voted=1 WHERE voter_id=:voterID');
			$stmt->bindParam(':voterID',$voterID);
			$stmt->execute();
			echo "Successfully Voted!";
			$_SESSION['eid']=$eid;
			$_SESSION['conid']=$conid;
		}catch (PDOException $e) {
			echo $e->getMessage();
		}
		$temp_con=null;
		// try {
		// 	if(!isset($_SESSION[$eid][$conid][$precID]))
		// 		{
		// 			$_SESSION[$eid][$conid]['active']=1;
		// 			$_SESSION[$eid][$conid][$precID]=0;
		// 			throw new Exception('Undefined Index');
		// 		}	
		// } catch (Exception $e) {
		// 	echo $e->getMessage().' ,But Calm Down! It\'s Ok. Nothing Wrong';			
		// }
		// finally
		// {
		// 	foreach ($_SESSION as $key => $value) {
		// 		foreach ($value as $key2 => $value2) {
		// 			$_SESSION[$key][$key2]['active']=0;
		// 		}
		// 	}
		// 	$_SESSION[$eid][$conid]['active']=1;
		// 	$_SESSION[$eid][$conid][$precID]+=1;
		// }
	}
?>