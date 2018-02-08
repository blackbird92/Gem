<?php
require_once 'ApnsPHP/Autoload.php';
require_once 'db_define.php';



if($_SERVER["REQUEST_METHOD"] == "POST"){
    // echo 'hello';
    $name = $_POST['user'];
    // echo $name;
    try{

    //connect
    $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //'users' テーブルのデータを取得する
    $sql = 'select * from ' . $name . " order by compDate";
    $stmt = $db->query($sql);
    
    //取得したデータを配列に格納
    while ($row = $stmt->fetchObject())
    {
        if($row->isComp == 1){
        	$users[] = array(
        		'gem' => $row->Gem,
                'compDate' => $row->compDate
        		);

        }
    }
    
    //JSON形式で出力する
    header('Content-Type: application/json');
    echo json_encode( $users );
    exit;

    //disconnect
    $db = null;


    }catch(PDOException $e){
        echo $e -> getMessage();
        exit;

    }
    
}else{
    // echo "Test";
}