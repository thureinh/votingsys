<?php
	session_start();
	session_destroy();
if(isset($_GET) && !empty($_GET)){
	if($_GET['pid']==1){
	session_unset();
	header("Location:votehistory.php");
}
if($_GET['pid']==2){
	$year=$_GET['y'];$month=$_GET['m'];
	$year=preg_replace("/\s+/",'', $year);
	if($month<10){
		$month="0".$month;
	}
	$sess_name=$year."-".$month;
	echo "Session Name [".$sess_name."] ++++++";
	unset($_SESSION[$sess_name]);
	header("Location:transferDB.php?pid=2&y=".$_GET['y']."&m=".$_GET['m']);
}
}
?>