<?php
require_once 'function.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$type = $_POST['type'];

	$follow_list = array('Daiki','Rizu','Takumi');
	$createdAt = returnGem($follow_list,$type);

	// jsonで出力
	header('Content-Type: application/json');
	echo json_encode( $createdAt );	
}


