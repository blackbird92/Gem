<?php
require_once 'ApnsPHP/Autoload.php';
require_once 'return_deviceToken.php';
require_once 'db_define.php';
require_once 'push.php';
require_once 'function.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $gem = $_POST['gem'];
    // iPhoneに値を返す。
    // echo 'PHP get ' . $gem . "\n";
    $got_gems = split("else=", $gem);

    // var_dump($got_gems);
    try{

    //connect
    $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $saveGem = mb_convert_encoding($got_gems[0], "UTF-8", "auto");
    $saveStone = rand(50, 100);

    $stmt = $db->prepare("insert into " .  $got_gems[1] . "(gem,stone,createdAt) values(?,?,?)");
    //  配列にしないといけないらしい。
        $stmt->execute([
            $saveGem,
            $saveStone,
            $got_gems[3]
            ]);

    // stone計算
    stone($got_gems[1], 0);

    // 行数計算
    global $counter;
    $stmt = $db->query("select gem from " . $got_gems[1] . " where isComp = false");
    $gemsCount = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    // foreach($gemsCount as $count){
    //     $counter = count($gemsCount);
    //     echo $counter . " ";
    //         // echo $counter;
    // }
    // echo $counter . " ";
    $counter = $stmt->rowCount();
    echo $counter;

    global $comp_counter;
    $stmt = $db->query("select gem from " . $got_gems[1] . " where isComp = true");
    $gemsCount = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();

    $comp_counter = $stmt->rowCount();
    echo $comp_counter;


    // 多分できてるはず
// 疲れた休憩しようかな
// で、保存
        $stmt = $db->prepare("update users set gemCount = :count where userName = :name");
        $stmt->execute([
                ':count' => $counter,
                ':name' => $got_gems[1]
            ]);

        // 完了済みの数を保存
        // ここ治したいな
        $stmt2 = $db->prepare("update users set compGemCount = :count where userName = :name");
        $stmt2->execute([
                ':count' => $comp_counter,
                ':name' => $got_gems[1]
            ]);


    $deviceToken = return_deviceToken($got_gems[2]);
    echo($deviceToken . $got_gems[2]);
    $push_mode = 0;
    $push_from = $got_gems[1];
    sendPush($deviceToken,$push_mode,$push_from);
    
    // echo "added!";

    //disconnect
    $db = null;

    }catch(PDOException $e){
        echo $e -> getMessage();
        exit;

    }
    
}else{
    // echo "Faild";
}
?>