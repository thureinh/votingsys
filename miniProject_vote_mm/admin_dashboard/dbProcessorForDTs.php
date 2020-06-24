<?php
require 'vendor/ssp.class.php';
if(isset($_GET['q']) && $_GET['q']==1){
$table='parties';
$primaryKEY='parid';
$sql_details=array(
'user'=>'root',
'pass'=>'',
'db'=>'test',
'host'=>'localhost'
);
$columns=array(
	array('db'=>'parid','dt'=>'DT_RowId','formatter'=> function($i,$row){
		return 'party_'.$i;
	}),
	array('db'=>'party_name_L','dt'=>'partynameL'),
	array('db'=>'party_name_S','dt'=>'partynameS'),
	array('db'=>'logo','dt'=>'logo'),
	array('db'=>'description','dt'=>'desc'),
);
echo json_encode(SSP::simple($_GET,$sql_details,$table,$primaryKEY,$columns));
}
if(isset($_GET['q']) && $_GET['q']==2){
	$sql_details=array(
		'user' => 'root',
		'pass' => '',
		'db' => 'test',
		'host' => 'localhost'
	);
	$table='elections';
	$primaryKey='eid';
	$columns=array(
		array('db' => 'eid' , 'dt' => 'DT_RowId' , 'formatter' => function($index,$row){
			return 'eid-'.$index;
		}),
		array('db' => 'ename' , 'dt' => 'ename' ),
		array('db' => 'edate' , 'dt' => 'edate','formatter' => function($d,$row){
			return date('jS M Y',strtotime($d));
		}),
		array('db' => 'eyear_begin' , 'dt' => 'eyear_begin'),
		array('db' => 'eyear_end' , 'dt' => 'eyear_end'),
	);
	echo json_encode(SSP::simple($_GET,$sql_details,$table,$primaryKey,$columns));
}
if(isset($_GET['q']) && $_GET['q']==3){
	$sql_details=array(
		'user' => 'root',
		'pass'=> '',
		'db' => 'test',
		'host' => 'localhost'
	);
	$table='constituency';
	$primaryKey='conid';
	$columns=array(
		array('db' => 'conid','dt' => 'DT_RowId','formatter' => function($id,$row){
			return 'con-'.$id;
		}),
		array('db' => 'con_name','dt' => 'conname'),
		array('db' => 'township','dt' => 'township')
	);
	echo json_encode(SSP::simple($_GET,$sql_details,$table,$primaryKey,$columns));
}
?>