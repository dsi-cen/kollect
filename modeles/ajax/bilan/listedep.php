<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function departement() // Liste des département de l'emprise
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->query("SELECT iddep AS id, departement AS emp, poly, geojson FROM referentiel.departement WHERE geojson IS NOT NULL ");
    $commune = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $commune;
}
$res = departement();
echo json_encode($res);
?>
