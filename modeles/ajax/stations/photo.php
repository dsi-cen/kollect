<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
session_start();


function supprimer_photo($id)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $sql = "DELETE FROM station.photo
            WHERE idphoto = :id";
    $req = $bdd->prepare($sql) or die(print_r($bdd->errorInfo()));
    $req->bindValue(':id', $id);
    $req->execute();
    $req->closeCursor();
}

if(isset($_POST['action']) ) {

    if($_POST['action'] == "supprimer" ) {
        $id = $_POST['id'];
        supprimer_photo($id);
    }
}

echo json_encode("ok");



