<?php
include "config.php";
include "utils.php";
$dbConn = connect($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if(isset($_POST['userId'])){

       
        //select p.petname,p.descriptions,p.postedUserId,t.fromPostId,t.urlThumb 
        //from petpost p inner join thumbs t on p.petpostId=t.fromPostId where t.thumbNum=1 and p.postedUserId=16;
        $mypets = array();
        $mypets["mypets"] = array();
        $sql = $dbConn->prepare("SELECT p.petpostId,p.petname,p.post_status,pet_weight,t.urlThumb,p.pet_weight,p.gender_pet,p.vaccinated, 
                                        p.dewormed,p.healthy,p.sterilized,p.descriptions,p.created_at 
                                        FROM petpost p INNER JOIN thumbs t ON p.petpostId=t.fromPostId WHERE t.thumbNum=1 AND postedUserId= :userId ;");
        $sql->execute(array(
            ':userId' => $_POST['userId'],
        ));

        
        if ($sql->rowCount()) {
            while ($row = $sql->Fetch(PDO::FETCH_ASSOC)) {
                $item = array(
                    'petpostId' => $row['petpostId'],
                    'petname' => $row['petname'],
                    'status' => $row['post_status'],
                    'urlThumb' => $row['urlThumb'],
                    'pet_weight' => $row['pet_weight'],
                    'gender_pet' => $row['gender_pet'],
                    'vaccinated' => $row['vaccinated'],
                    'dewormed' => $row['dewormed'],
                    'healthy' => $row['healthy'],
                    'sterilized' => $row['sterilized'],
                    'descriptions' => $row['descriptions'],
                    'created_at' => $row['created_at'],
                    
                );
                array_push($mypets['mypets'], $item);
            }
            echo json_encode($mypets);
            header("HTTP/1.1 200 OK");
            exit();
        } else {echo json_encode(array('messaje' => 'Nadaque mostrar'));}
 
    }else{

    //Empieza
    $petname = $_POST['petname'];
    $pet_type = $_POST['pet_type'];
    $pet_weight = $_POST['pet_weight'];
    $gender_pet = $_POST['gender_pet'];
    $vaccinated = $_POST['vaccinated'];
    $dewormed = $_POST['dewormed'];
    $healthy = $_POST['healthy'];
    $sterilized = $_POST['sterilized'];
    $descriptions = $_POST['descriptions'];
    $post_status = $_POST['post_status'];
    $postedUserId = $_POST['postedUserId'];
    
    if (isset($_FILES["image1"]) && isset($_FILES["image2"]) && isset($_FILES["image3"])
        && $_FILES["image1"]["error"] == 0 && $_FILES["image2"]["error"] == 0 && $_FILES["image3"]["error"] == 0) {

        if (file_exists("uploads/" . $_FILES["image1"]["name"])) {
            echo ('El archivo ya existe');
        } else {
            move_uploaded_file($_FILES["image1"]["tmp_name"], "uploads/" . basename($_FILES["image1"]["name"]));
            move_uploaded_file($_FILES["image2"]["tmp_name"], "uploads/" . basename($_FILES["image2"]["name"]));
            move_uploaded_file($_FILES["image3"]["tmp_name"], "uploads/" . basename($_FILES["image3"]["name"]));

            $urlPhoto1 = 'http://192.168.0.8/APIPET/uploads/' . $_FILES["image1"]["name"];
            $urlPhoto2 = 'http://192.168.0.8/APIPET/uploads/' . $_FILES["image2"]["name"];
            $urlPhoto3 = 'http://192.168.0.8/APIPET/uploads/' . $_FILES["image3"]["name"];
            $listUrls = array($urlPhoto1, $urlPhoto2, $urlPhoto3);
            $num = 0;

            //Inserting on petPost
            
            $sql = "INSERT INTO petpost 
                    (petname, pet_type, pet_weight, gender_pet, vaccinated, dewormed, healthy, sterilized, descriptions, post_status, postedUserId)
                    VALUES 
                    (:petname, :pet_type, :pet_weight, :gender_pet, :vaccinated, :dewormed, :healthy, :sterilized, :descriptions, :post_status, :postedUserId)";

            $statement = $dbConn->prepare($sql);
            $statement->execute(array(
                ':petname' => $petname,
                ':pet_type' => $pet_type,
                ':pet_weight' => $pet_weight,
                ':gender_pet' => $gender_pet,
                ':vaccinated' => $vaccinated,
                ':dewormed' => $dewormed,
                ':healthy' => $healthy,
                ':sterilized' => $sterilized,
                ':descriptions' => $descriptions,
                ':post_status' => $post_status,
                ':postedUserId' => $postedUserId,
            ));
            //Taking the last row added
            $petpostId = $dbConn->lastInsertId();
            $petpostId['petpostId'] = $petpostId;

            //Inserting images on  thumbs
            for ($i = 0; $i < count($listUrls); $i++) {
                $num++;
                $sql = "INSERT INTO thumbs(fromPostId, urlThumb, thumbNum) VALUES (:fromPostId, :urlThumb, :thumbNum)";
                $statement = $dbConn->prepare($sql);
                $statement->execute(array(
                    ':fromPostId' => $petpostId,
                    ':urlThumb' => $listUrls[$i],
                    ':thumbNum' => $num,
                ));
            }

            echo("TODOOOOOOOOOO BIEN!");
        }
    } else {echo ('No existe la variables');}

    //Finaliza
    }//Finaliza busqueda por usuario
}


    //Gettig all 
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {

        $petposts = array();
        $petposts["petposts"] = array();
        $sql = $dbConn->prepare("SELECT p.petpostId,p.petname,p.post_status,pet_weight,t.urlThumb,p.pet_weight,p.gender_pet,p.vaccinated, 
                                        p.dewormed,p.healthy,p.sterilized,p.descriptions,p.created_at,p.postedUserId FROM petpost p INNER JOIN thumbs t ON p.petpostId=t.fromPostId WHERE t.thumbNum=1;");
        $sql->execute();

        

        if ($sql->rowCount()) {
            while ($row = $sql->Fetch(PDO::FETCH_ASSOC)) {
                $item = array(
                    'petpostId' => $row['petpostId'],
                    'petname' => $row['petname'],
                    'status' => $row['post_status'],
                    'urlThumb' => $row['urlThumb'],
                    'pet_weight' => $row['pet_weight'],
                    'gender_pet' => $row['gender_pet'],
                    'vaccinated' => $row['vaccinated'],
                    'dewormed' => $row['dewormed'],
                    'healthy' => $row['healthy'],
                    'sterilized' => $row['sterilized'],
                    'descriptions' => $row['descriptions'],
                    'created_at' => $row['created_at'],
                    'postedUserId' => $row['postedUserId']
                    
                );
                array_push($petposts['petposts'], $item);
            }
            echo json_encode($petposts);
           // header("HTTP/1.1 200 OK");
            exit();
        } else {echo json_encode(array('messaje' => 'Nadaque mostrar'));}
    }