<?php
require_once 'function.php';


if($_SERVER["REQUEST_METHOD"] == "POST"){
	$userName = $_POST['userName'];
	// echo $userName;
	// $test = returnGem(array($userName));
	// // echo "HelloHello!! " . $userName;
	// echo json_encode($test);
	try{
	    //connect
	    $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);

	    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	    $stmt = $db->prepare("select userName,stone,gemCount,compGemCount from users where userName = :name");
	    $stmt->execute([
	    		':name' => $userName
	    	]);
	    $userData = $stmt->fetchAll(PDO::FETCH_ASSOC);
	    // var_dump($userData);


// 一回消してみる
	    // while($row = $stmt->fetchObject()){
	    // 	$data[] = array(
	    // 		'userName'=>$row->userName,
	    // 		'stone'=>$row->stone,
	    // 		'gemCount'=>$row->gemCount,
	    // 		'compGemCount'=>$row->compGemCount
	    // 		);
	    // }

	    	// var_dump($data);
	    	// 
	    	// echo json_encode($data);
	    	// exit;
	    	$json = json_encode($userData);
	    	header('Content-Type: application/json');
	    	echo $json;

	}catch(PDOException $e){
		echo $e->getMessage();
		exit;
	}

}




// try{
// 	    //connect
// 	    $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);

// 	    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 	    $stmt = $db->prepare("select userName,stone,gemCount,compGemCount from users where userName = :name");
// 	    $stmt->execute([
// 	    		':name' => 'Daiki'
// 	    	]);
// 	    // $userData = $stmt->fetchAll(PDO::FETCH_ASSOC);
// 	    // var_dump($userData);
// 	    // これできなかった↑

// 	    while($row = $stmt->fetchObject()){
// 	    	$data[] = array(
// 	    		'userName'=>$row->userName,
// 	    		'stone'=>$row->stone,
// 	    		'gemCount'=>$row->gemCount,
// 	    		'compGemCount'=>$row->compGemCount
// 	    		);

// 	    	echo json_encode($data);
// 	    }
// 	}catch(PDOException $e){
// 		echo $e->getMessage();
// 		exit;
// 	}