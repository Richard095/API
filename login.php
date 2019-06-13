<?php
include "config.php";
include "utils.php";
$dbConn = connect($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['user'];
    $pass = $_POST['pass'];

    $sql = $dbConn->prepare('SELECT * FROM users WHERE user = :user AND pass = :pass');
    $sql->execute(array(
        ':user' => $user,
        ':pass' => $pass,
    ));
    $result = $sql->Fetch(PDO::FETCH_ASSOC);
    
    if ($result !== false) {
        $user = array();
        $user["user"] = array();
        $item = array(
            'userId' => $result['userId'],
            'user_type' => $result['user_type'],
            'fullname' => $result['fullname'],
            'user' => $result['user'],
            'pass' => $result['pass'],
            'phone' => $result['phone'],
            'email' => $result['email'],
            'addresses' => $result['addresses'],
            'descriptions' => $result['descriptions'],
            'thumb' => $result['thumb'],
            'created_at' => $result['created_at'],
        );
        array_push($user['user'], $item);
        echo json_encode($user);
        //header("HTTP/1.1 200 OK");
        exit();
    } else {
        echo ("false");
    }


    //----------------------------------------------------

}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    
    $userId = $_GET['userId'];
    $sql = $dbConn->prepare("SELECT * FROM users where userId=:userId");
    $sql->execute(array(
        ':userId' => $userId,
    ));
    $result = $sql->Fetch(PDO::FETCH_ASSOC);
        
        if($result !== false){
        $user = array();
        $user["user"] = array();
        $item = array(
            'userId' => $result['userId'],
            'user_type' => $result['user_type'],
            'fullname' => $result['fullname'],
            'user' => $result['user'],
            'pass' => $result['pass'],
            'phone' => $result['phone'],
            'email' => $result['email'],
            'addresses' => $result['addresses'],
            'descriptions' => $result['descriptions'],
            'thumb' => $result['thumb'],
            'created_at' => $result['created_at'],
        );
        array_push($user['user'], $item);
        echo json_encode($user);
        echo ("  USUARIO: ".$id);
        //header("HTTP/1.1 200 OK");
        exit();
    }else{
        echo ("0");
    }
}
//header("HTTP/1.1 400 Bad Request");
