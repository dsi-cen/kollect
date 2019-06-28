<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

$bdd = PDO2::getInstance();
$bdd->query("SET NAMES 'UTF8'");

// Refresh MV
$req = $bdd->prepare( "REFRESH MATERIALIZED VIEW obs.synthese_obs_nflou ;" );
$req->execute();

$req->closeCursor();

if($req){
    echo json_encode("mv_ok");
} else {
    echo json_encode("mv_ko");
}