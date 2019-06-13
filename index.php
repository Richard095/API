<?php
include "config.php";
include "utils.php";
$dbConn = connect($db);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['userId'])) {
        echo("nada aun");

    } else {
        //SHOW ALL THE USERS

        $users = array();
        $users["users"] = array();
        
        $sql = $dbConn->prepare("SELECT * FROM users");
        $sql->execute();

        
        if ($sql->rowCount()) {
            while ($row = $sql->Fetch(PDO::FETCH_ASSOC)) {
                $item = array(
                    'userId' => $row['userId'],
                    'user_type' => $row['user_type'],
                    'fullname' => $row['fullname'],
                    'phone' => $row['phone'],
                    'email' => $row['email'],
                    'addresses' => $row['addresses'],
                    'descriptions' => $row['descriptions'],
                    'thumb' => $row['thumb'],
                    'created_at' => $row['created_at'],
                );
                array_push($users['users'], $item);
            }
            echo json_encode($users);
            //header("HTTP/1.1 200 OK");
            exit();
        } else {echo json_encode(array('messaje' => 'Nadaque mostrar'));}

    }
}

//Insert a new User
if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    if(isset($_POST['userId'])){
        $user = array();
        $user["user"] = array();
        $sql = $dbConn->prepare("SELECT fullname,phone,email,addresses,descriptions FROM users WHERE  userId= :userId ;");
        $sql->execute(array(
            ':userId' => $_POST['userId'],
        ));

        
        if ($sql->rowCount()) {
            while ($row = $sql->Fetch(PDO::FETCH_ASSOC)) {
                $item = array(
                    'fullname' => $row['fullname'],
                    'phone' => $row['phone'],
                    'email' => $row['email'],
                    'addresses' => $row['addresses'],
                    'descriptions' => $row['descriptions'],
                );
                array_push($user['user'], $item);
            }
            echo json_encode($user);
            //header("HTTP/1.1 200 OK");
            exit();
        } else {echo json_encode(array('messaje' => 'Nadaque mostrar'));}
 
    }else{

    


    $input = $_POST;
    $sql = "INSERT INTO users
            (user_type, fullname, user, pass, phone, email, addresses, descriptions, thumb)
            VALUES
            (:user_type, :fullname, :user, :pass, :phone, :email, :addresses, :descriptions, :thumb)";

    // $sql = $dbConn->prepare("SELECT user FROM users where userId=:userId");
    // $sql->bindValue(':userId', $_GET['userId']);

    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);
    $statement->execute();
    $userId = $dbConn->lastInsertId();

    if ($userId) {
        $input['userId'] = $userId;
        header("HTTP/1.1 200 OK");
        echo json_encode($input);
        exit();
        }


    }



    } else {
        echo json_encode(array('messaje' => 'ERROR'));
    }






//En caso de que ninguna de las opciones anteriores se haya ejecutado
//header("HTTP/1.1 400 Bad Request");
