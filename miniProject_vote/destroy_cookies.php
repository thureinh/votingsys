<?php 
if(isset($_COOKIE["admin"])) {
    setcookie("admin","",time()-3600,"/");
	header("Location:loginanim.php"); 
} 
if(isset($_COOKIE["user"])){
	setcookie("user","",time()-3600,"/");
	header("Location:../");
}
?>