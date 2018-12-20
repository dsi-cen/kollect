<?php
include '../../../../global/configbase.php';
include '../../../lib/pdo2.php';

function get_com($cdnom, $observatoire)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->prepare("SELECT commentaire FROM $observatoire.liste WHERE cdnom = :cdnom ;") or die(print_r($bdd->errorInfo()));
    $req->bindValue(':cdnom', $cdnom);
    $req->execute();
    $result = $req->fetch(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $result;
}

function insert_com($cdnom, $observatoire, $new_com)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->prepare("UPDATE $observatoire.liste SET commentaire = :new_com WHERE cdnom = :cdnom ;") or die(print_r($bdd->errorInfo()));
    $req->bindValue(':cdnom', $cdnom);
    $req->bindValue(':new_com', $new_com);
    $req->execute();
    $req->closeCursor();
}

function delete_com($cdnom, $observatoire)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->prepare("UPDATE $observatoire.liste SET commentaire = null WHERE cdnom = :cdnom ;") or die(print_r($bdd->errorInfo()));
    $req->bindValue(':cdnom', $cdnom);
    $req->execute();
    $req->closeCursor();
}

$cdnom = htmlspecialchars($_POST['cdnom']);
$observatoire = htmlspecialchars($_POST['observatoire']);

if (isset($_POST['new_com'])){
    $new_com = htmlspecialchars($_POST['new_com']);
    insert_com($cdnom, $observatoire, $new_com);
}

if (isset($_POST['del']) && $_POST['del'] == 'oui'){
    delete_com($cdnom, $observatoire);
}

$res = get_com($cdnom, $observatoire);
$info['res'] = $res['commentaire'];
$info['stat'] = 'ok';
echo json_encode($info);
?>