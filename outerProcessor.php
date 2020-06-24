<?php
	try {
		$connector=new PDO('mysql:host=localhost;dbname=test','root','');
		$connector->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

	if($_SERVER['REQUEST_METHOD']==='POST'){
		if(isset($_POST['pid'])){
			if($_POST['pid']==1){
				$front_img_dir=upload_files($_FILES['front-image']);
				$back_img_dir=upload_files($_FILES['back-image']);

				if(empty($front_img_dir) || empty($back_img_dir)){
					echo 'Registeration Failed';
				}
				else
				{
					$stmt=$connector->prepare('INSERT INTO pending_users(pu_nrc,pu_name,nrc_front_img,nrc_back_img,electionID,constituencyID) VALUES(:nrc,:name,:nrcFront,:nrcBack,:eid,:conid)');
					$stmt->bindParam(':nrc',$_POST['nrc']);
					$stmt->bindParam(':name',$_POST['name']);
					$stmt->bindParam(':nrcFront',$front_img_dir);
					$stmt->bindParam(':nrcBack',$back_img_dir);
					$stmt->bindParam(':eid',$_POST['election']);
					$stmt->bindParam(':conid',$_POST['radio-con']);
					$stmt->execute();
					echo 'Registered Successfully!';
				}
			}
		}
	}	

	if($_SERVER['REQUEST_METHOD']==='GET'){
		if(isset($_GET['pid'])){
			if($_GET['pid']==1){
				$stmt=$connector->prepare('SELECT constituency.* FROM candidateandelection INNER JOIN constituency ON candidateandelection.conid=constituency.conid INNER JOIN elections ON candidateandelection.eid=elections.eid WHERE elections.eid=:eid ORDER BY constituency.conid');
				$stmt->bindParam(':eid',$_GET['eid']);
				$stmt->execute();
				$stmt->setFetchMode(PDO::FETCH_ASSOC);
				$constituencies=$stmt->fetchAll();
				echo json_encode($constituencies);
			}
		}
	}

	} catch (PDOException $e) {
		echo $e->getMessage();
	}
	$connector=null;
function upload_files($files){
$returnValue;
$target_dir = "upload_files/";
$target_file = $target_dir . basename($files["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($files["tmp_name"]);
    if($check !== false) {
        // echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Check if file already exists
// if (file_exists($target_file)) {
//     echo "Sorry, file already exists.";
//     $uploadOk = 0;
// }
// Check file size
// if ($_FILES["fileToUpload"]["size"] > 500000) {
//     echo "Sorry, your file is too large.";
//     $uploadOk = 0;
// }
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($files["tmp_name"], $target_file)) {
        // echo "The file ". basename( $files["fileToUpload"]["name"]). " has been uploaded.";	
        $returnValue=$target_file;
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
 return $returnValue;
}
?>