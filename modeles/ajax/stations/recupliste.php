<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
session_start();

function stations($iddep)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->prepare("SELECT idsite, obs.site.idcoord, obs.site.codecom, site, obs.coordgeo.geo, type, commune,  obs.coordonnee.lat, obs.coordonnee.lng
FROM obs.site
LEFT JOIN referentiel.commune ON referentiel.commune.codecom = obs.site.codecom
LEFT JOIN obs.coordgeo ON obs.site.idcoord = obs.coordgeo.idcoord
LEFT JOIN obs.coordonnee ON obs.site.idcoord = obs.coordonnee.idcoord
                                    WHERE obs.site.codecom LIKE :iddep ") or die(print_r($bdd->errorInfo()));
    $req->bindValue(':iddep', $iddep.'%');
    $req->execute();
    $stations = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $stations;
}

if(isset($_POST['iddep'])) {
    $idm = (isset($_SESSION['idmorigin'])) ? $_SESSION['idmorigin'] : $_SESSION['idmembre'];

    // Recherche par département
    $iddep = $_POST['iddep'];
    $stations = stations($iddep);

    $retour['geo'] = []; // Stocker les géométries
    $retour['point']['lat'] = []; // Stocker les points
    $retour['point']['lng'] = []; // Stocker les points
    $liste = ""; // Stocker le tableau

    $liste .= '<table id="liste_stations" class="table table-hover table-sm">';
    $liste .= '<thead><tr><th></th></th><th>Site</th><th>Commune</th><th>Type</th></tr></thead><tbody>';
    foreach($stations as $n) {
        $liste .= '<tr>';
        $liste .= '<td><i class="fa fa-file-text-o text-info curseurlien"></i>';
        $liste .= '&nbsp;<i class="fa fa-eye text-info focus" onclick="display_station('.$n['idsite'].')"></i></td>';
        $liste .= '<td>'.$n['site'].'</td>';
        $liste .= '<td>'.$n['commune'].'</td>';
        $liste .= '<td>'.$n['type'].'</td>';
        $liste .= '</td>';
        $liste .= '</tr>';
        array_push($retour['geo'], $n['geo']);
        array_push($retour['point']['lat'], $n['lat']);
        array_push($retour['point']['lng'], $n['lng']);
    }
    $liste .= '</tbody></table>';

    $retour['liste'] = $liste;

    unset($stations);

    echo json_encode($retour);
}
