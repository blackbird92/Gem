<?php
require_once 'db_define.php';

// ストーン計算
function stone($user, $type){
	echo $user . "   " . $type . "\n";

	$db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try{
    	// stone取り出し
    	$sql = 'select userName, stone from users';
    	$stmt = $db->query($sql);

    	while($row = $stmt->fetchObject()){
    		if($row->userName == $user){
    			$stone = $row->stone;
    			echo $stone . "\n";
    		}
    	}

    	// ストーン計算
    	switch($type){
    		// ポストした時とMeTooした時
    		case 0:
    		case 1:
    			$stone = $stone + 5;
    			break;
    		// 完了した時
    		case 2:
    			$stone = $stone + rand(1, 10);
    			echo $stone . "\n";
    			break;
    		// 削除したとき
    		case 3:
    			$stone = $stone - rand(1, 5);
    			break;
    	}

    	// で、保存
    	$stmt = $db->prepare("update users set stone = :stone where userName = :name");
    	$stmt->execute([
    			':stone' => $stone,
    			':name' => $user
    		]);

    	// ディスコネクト
    	$db = NULL;

    }catch(PDOException $e){
    	echo $e->getMessage();
    	exit;
    }
}


// Gemを編集して返す
function returnGem($follow_list,$type){
    try{
    	$db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
    	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        switch($type){
             // 完了済み以外
            case 0: 
            foreach($follow_list as $value){
                $sql = 'select * from ' . $value . ' order by createdAt desc';
                $stmt = $db->query($sql);

                while($row = $stmt->fetchObject()){
                    if($row->isComp == 0){
                        $users[] = array(
                            'createdAt'=>$row->createdAt,
                            'gem'=>$row->Gem,
                            'user'=>$value,
                            'stone'=>$row->stone
                        );
                    }
                }

                // ここは何をしているんだろう笑
                // 無くても良い気がする
                foreach($users as $key=>$value){
                    $createdAt[$key] = array(
                        'createdAt'=>$value['createdAt'],
                        'gem'=>$value['gem'],
                        'user'=>$value['user'],
                        'stone'=>$value['stone']
                        );
                }
            }

                // // ここソートっぽい
                // array_multisort($createdAt, SORT_DESC, $users);
                    
                // return $createdAt;

                break;

            //完了済み
            case 1:
            foreach($follow_list as $value){
                $sql = 'select * from ' . $value . ' order by createdAt desc';
                $stmt = $db->query($sql);

                while($row = $stmt->fetchObject()){
                    if($row->isComp == 1){
                        $users[] = array(
                            'gem'=>$row->Gem,
                            'compDate'=>$row->compDate,
                            'user'=>$value
                        );
                    }
                }
                // ここ、内容がよく理解できてないから調べて理解
                foreach($users as $key=>$value){
                    $createdAt[$key] = array(
                        'compDate'=>$value['compDate'],
                        'gem'=>$value['gem'],
                        'user'=>$value['user']
                        
                    );
                }
            }

                break;
        }

        $db = null;

        array_multisort($createdAt, SORT_DESC, $users);
        return $createdAt;
    }catch(PDOException $e){
    	echo $e ->getMessage();
    	exit;
    }
}


// Gem（完了済みも）の数を保存する
