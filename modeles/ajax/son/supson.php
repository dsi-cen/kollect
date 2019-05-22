<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function sup_son($idson)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->prepare("DELETE FROM site.son WHERE idson = :idson") or die(print_r($bdd->errorInfo()));
    $req->bindValue(':idson', $idson);
    $vali = ($req->execute()) ? 'Oui' : 'Non';
    $req->closeCursor();
    return $vali;
}

if(isset($_POST['idson']))
{
    $idson = $_POST['idson'];
    $retour['statut'] = sup_son($idson);

    // TODO : supprimer le fichier physique ?

}
echo json_encode($retour);