<?php
require 'vendor/ssp.class.php';

try{
    $database=new PDO('mysql:host=localhost;dbname=test','root','');
    $database->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
if($_SERVER['REQUEST_METHOD']=='POST'){
    $queries=$database->prepare('SELECT candidates.cid,name,nationality,nrc,religious,email,address,phone_no,
    image,party_name_S FROM candidates INNER JOIN party_records ON candidates.cid = party_records.cid LEFT OUTER JOIN parties ON party_records.parid=parties.parid WHERE party_records.status=1');
    $queries->execute();
    $queries->setFetchMode(PDO::FETCH_ASSOC);
    $results=$queries->fetchAll();
    echo json_encode($results);
}
if($_SERVER['REQUEST_METHOD']=='GET'){
    $delID=$_GET['d'];
    $queries=$database->prepare('DELETE FROM candidates WHERE cid = :cid');
    $queries->bindParam(':cid',$delID);
    $queries->execute();
    $queries=$database->prepare('SELECT candidates.cid,name,nationality,nrc,religious,email,address,phone_no,
    image,party_name_S FROM candidates INNER JOIN party_records ON candidates.cid = party_records.cid LEFT OUTER JOIN parties ON party_records.parid=parties.parid WHERE party_records.status=1');
    $queries->execute();
    $queries->setFetchMode(PDO::FETCH_ASSOC);
    $results=$queries->fetchAll();
    echo json_encode($results);
}
}catch(PDOException $e){
    echo $e->getMessage();
}
$database=null;

?>
