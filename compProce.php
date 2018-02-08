<?php
require_once 'ApnsPHP/Autoload.php';
require_once 'db_define.php';
require_once 'return_deviceToken.php';
require_once 'push.php';
require_once 'function.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$compGem = $_POST['gem'];

	// echo 'PHP gem' . $compGem;
	$got_gems = split("else=", $compGem);
	var_dump($got_gems);

	try{
		$db = new PDO(PDO_DSN,DB_USERNAME,DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		if($got_gems[0]){
			$stmt = $db->prepare("replace " . $got_gems[1] . "(Gem,isComp,compDate) values(?,?,?)");
			$stmt->execute([
					// ':comp' => '1',
					// ':comp_gem' => $got_gems[0]
					$got_gems[0],
					'1',
					$got_gems[3]
				]);
		}


			// echo 'row updated: ' . $stmt->rowCount(); 
		$deviceToken = return_deviceToken($got_gems[2]);
		$db = null;

		// stone計算
		stone($got_gems[1], 2);

		// 通知を飛ばす
		$push_mode = 1;
		$push_dist = $got_gems[1];
		sendPush($deviceToken,$push_mode,$push_dist);

	}catch(PDOException $e){
        echo $e -> getMessage();
        exit;

    }
}
