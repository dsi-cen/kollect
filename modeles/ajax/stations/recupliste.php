<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
session_start();

function stations($iddep, $type)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $sql = "SELECT idsite, obs.site.idcoord, obs.site.codecom, site, obs.coordgeo.geo, typestation, commune,  obs.coordonnee.lat, obs.coordonnee.lng, libtypestation
            FROM obs.site
            LEFT JOIN referentiel.commune ON referentiel.commune.codecom = obs.site.codecom
            LEFT JOIN obs.coordgeo ON obs.site.idcoord = obs.coordgeo.idcoord
            LEFT JOIN obs.coordonnee ON obs.site.idcoord = obs.coordonnee.idcoord
            LEFT JOIN referentiel_station.typestation ON referentiel_station.typestation.idtypestation = obs.site.typestation 
            WHERE obs.site.codecom LIKE :iddep ";
    if ($type != 0){
        $sql .= "AND referentiel_station.typestation.idtypestation = :type ";
    }
    $req = $bdd->prepare($sql) or die(print_r($bdd->errorInfo()));
    $req->bindValue(':iddep', $iddep == 0 ? "%" : $iddep . '%');
    $req->bindValue(':type', $type);
    $req->execute();
    $stations = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $stations;
}

if(isset($_POST['iddep']) || isset($_POST['iddep'])) {
    $idm = (isset($_SESSION['idmorigin'])) ? $_SESSION['idmorigin'] : $_SESSION['idmembre'];

    // Recherche par département et/ou par type
    $iddep = $_POST['iddep'];
    $type = $_POST['type'];

    $stations = stations($iddep, $type);

    $retour['geo']['geo'] = []; // Stocker les géométries
    $retour['geo']['lat'] = []; // Stocker les points
    $retour['geo']['lng'] = []; // Stocker les points
    $retour['geo']['nom'] = []; // Stocker le nom
    $retour['geo']['idsite'] = []; // Stocker le idsite

    $liste = ""; // Stocker le tableau

    $liste .= '<table id="liste_stations" class="table table-hover table-sm">';
    $liste .= '<thead><tr><th></th></th><th>Site</th><th>Commune</th><th>Type</th></tr></thead><tbody>';
    foreach($stations as $n) {
        $liste .= '<tr id="'. $n['idsite'] .'">';
        $liste .= '<td><i class="fa fa-file-text-o text-info curseurlien"></i>';
        $liste .= '&nbsp;<i class="fa fa-eye text-info focus"></i></td>';
        $liste .= '<td>'.$n['site'].'</td>';
        $liste .= '<td>'.$n['commune'].'</td>';
        $liste .= '<td>'.$n['libtypestation'].'</td>';
        $liste .= '</td>';
        $liste .= '</tr>';
        array_push($retour['geo']['geo'], $n['geo']);
        array_push($retour['geo']['lat'], $n['lat']);
        array_push($retour['geo']['lng'], $n['lng']);
        array_push($retour['geo']['nom'], $n['site']);
        array_push($retour['geo']['idsite'], $n['idsite']);
    }
    $liste .= '</tbody></table>';

    $retour['liste'] = $liste;

    unset($stations);

    echo json_encode($retour);
}
