<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
session_start();

function stations($iddep, $type)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $sql = "SELECT case when idparent is null then idsite else idparent end as ordre, idsite, obs.site.idcoord, obs.site.codecom, site, idstatusstation, libidstatusstation, wsite, idparent, obs.coordgeo.geo, typestation, commune,  obs.coordonnee.lat, obs.coordonnee.lng, libtypestation
            FROM obs.site
            LEFT JOIN referentiel.commune ON referentiel.commune.codecom = obs.site.codecom
            LEFT JOIN obs.coordgeo ON obs.site.idcoord = obs.coordgeo.idcoord
            LEFT JOIN obs.coordonnee ON obs.site.idcoord = obs.coordonnee.idcoord
            LEFT JOIN referentiel_station.typestation ON referentiel_station.typestation.idtypestation = obs.site.typestation 
            LEFT JOIN referentiel_station.statusstation ON referentiel_station.statusstation.idstatusstation = obs.site.idstatus 
            WHERE obs.site.codecom LIKE :iddep ";
    if ($type != 0){
        $sql .= "AND referentiel_station.typestation.idtypestation = :type ";
    }
    $sql .= " order by ordre asc, idsite";
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
    $liste .= '<thead><tr><th></th></th><th>Site</th><th>Commune</th><th>Type</th><th>Actif</th><th>Status</th></tr></thead><tbody>';
    foreach($stations as $n) {
        empty($n['idparent']) ? $p = "" : $p = "&#8627;  ";
        $liste .= '<tr id="'. $n['idsite'] .'">';
        $liste .= '<td><i onclick="detail(' . $n['idsite'] . ')" class="fa fa-file-text-o text-info curseurlien" ></i>';
        $liste .= '&nbsp;<i class="fa fa-eye text-info focus"></i></td>';
        $n['wsite'] == "non" ? $liste .= '<td style="color: grey;">'. $p . $n['site']. '</td>' : $liste .= '<td>'. $p . $n['site']. '</td>' ;
        $n['wsite'] == "non" ? $liste .= '<td style="color: grey;">'.$n['commune'].'</td>' : $liste .= '<td>'.$n['commune'].'</td>' ;
        $n['wsite'] == "non" ? $liste .= '<td style="color: grey;">'.$n['libtypestation'].'</td>' : $liste .= '<td>'.$n['libtypestation'].'</td>' ;
        $n['wsite'] == "non" ? $liste .= '<td style="color: grey;">'.$n['wsite'].'</td>' : $liste .= '<td>'.$n['wsite'].'</td>' ;
        $n['wsite'] == "non" ? $liste .= '<td style="color: grey;">'.$n['libidstatusstation'].'</td>' : $liste .= '<td>'.$n['libidstatusstation'].'</td>' ;

        // $liste .= '</td>';
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
