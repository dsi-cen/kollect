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

function get_path($id)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $sql = "SELECT nomphoto
            FROM station.photo
            where idphoto = :id";
    $req = $bdd->prepare($sql) or die(print_r($bdd->errorInfo()));
    $req->bindValue(':id', $id);
    $req->execute();
    $path = $req->fetch(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $path;
}

if(isset($_POST['action']) ) {

    if($_POST['action'] == "supprimer" ) {
        $id = $_POST['id'];
        $path = get_path($id);

        // Suppression du fichier dans le filesystem
        unlink('../../../photo/P800/stations/' . $path['nomphoto'] . '.jpg');
        unlink('../../../photo/P400/stations/' . $path['nomphoto'] . '.jpg');
        unlink('../../../photo/P200/stations/' . $path['nomphoto'] . '.jpg');
        // Suppression de la référence en base
        supprimer_photo($id);
    }
}

echo json_encode("ok");