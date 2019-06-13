<?php
include "config.php";
include "utils.php";
$dbConn = connect($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fromPostId = $_POST['fromPostId'];

    $sql = $dbConn->prepare('SELECT urlThumb FROM thumbs WHERE  fromPostId = :fromPostId ');
    $sql->execute(array(
        ':fromPostId' => $fromPostId
    ));
    $images = array();
    $images["images"] = array();
        
    if ($sql->rowCount()) {
        while ($row = $sql->Fetch(PDO::FETCH_ASSOC)) {
            $item = array(
                'urlThumb' => $row['urlThumb'],
            );
            array_push($images['images'], $item);
        }
        echo json_encode($images);
        header("HTTP/1.1 200 OK");
        exit();
    } else {echo json_encode(array('messaje' => 'Nadaque mostrar'));}
}
