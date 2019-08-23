<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';


function get_update_date()
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->query("SELECT actualisation FROM obs.synthese_obs_nflou LIMIT 1; ");
    $resultats = $req->fetch(PDO::FETCH_ASSOC);
    $req->closeCursor();
    $date['jour'] = date('d/m/Y', strtotime($resultats['actualisation']));
    $date['heure'] = date('H:i', strtotime($resultats['actualisation']));
    return $date;
}

$bdd = PDO2::getInstance();
$bdd->query("SET NAMES 'UTF8'");

// Refresh MV
$req = $bdd->prepare( "REFRESH MATERIALIZED VIEW obs.synthese_obs_nflou ; REFRESH MATERIALIZED VIEW obs.synthese_obs_flou ;" );
$req->execute();

$req->closeCursor();

if($req){
    $ret['status'] = "mvok";
    $actualisation = get_update_date();
    $ret['newdate'] = 'Dernière actualisation : Le ' . $actualisation['jour'] . ' à ' . $actualisation['heure'] ;
} else {
    $ret['status'] = "mvko";
}



echo json_encode($ret);