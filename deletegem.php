<?php
require_once 'ApnsPHP/Autoload.php';
require 'db_define.php';
require_once 'function.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $gem = $_POST['gem'];
    // iPhoneに値を返す。
    // echo 'PHP get ' . $gem . "\n";
    $got_gems = split("deleTable=", $gem);

    // var_dump($got_gems);

    try{

    //connect
    $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $db->prepare("delete from " . $got_gems[1] . " where Gem = :gem");
    $stmt->execute([
        ':gem' => $got_gems[0]
    ]);


    // echo "deleted!";

    //disconnect
    $db = null;

    // stone計算
    stone($got_gems[1], 3);

    }catch(PDOException $e){
        echo $e -> getMessage();
        exit;

    }
    
}else{
    echo "Faild";
}


?>