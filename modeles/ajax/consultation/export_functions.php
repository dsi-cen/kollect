<?php

function convertToISOCharset($array)
{
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $array[$key] = convertToISOCharset($value);
        } else {
            $array[$key] = mb_convert_encoding($value, 'ISO-8859-1', 'UTF-8');
        }
    }
    return $array;
}

function query($where)
{
    if (isset($_POST['choixtax']) && isset($_POST['choixloca'])) {

        $idobservateur = $_POST['idobser'];
        $choixtax = $_POST['choixtax'];
        $choixloca = $_POST['choixloca'];
        $photo = (isset($_POST['photo'])) ? 'oui' : 'non';
        $son = (isset($_POST['son'])) ? 'oui' : 'non';
        $lat = $_POST['lat'];
        $lng = $_POST['lng'];
        $etude = $_POST['etude'];
        $orga = $_POST['orga'];
        $typedon = $_POST['typedon'];
        $flou = $_POST['flou'];
        $pr = $_POST['pr'];
        $habitat = $_POST['habitat'];
        $stade = $_POST['stade'] ? $_POST['stade'] : 0 ;
        $etatbio = $_POST['etatbio'] ? $_POST['etatbio'] : 0 ;
        $methode = $_POST['methode'] ? $_POST['methode'] : 0 ;
        $prospect = $_POST['prospect'] ? $_POST['prospect'] : 0 ;
        $statbio = $_POST['statbio'] ? $_POST['statbio'] : 0 ;
        $acquisition = $_POST['acquisition'] ? $_POST['acquisition'] : 0 ;

        if (!empty($choixtax)) {
            $observa = ($choixtax == 'observa') ? $_POST['rchoixtax'] : null;
            $cdnom = ($choixtax == 'espece') ? $_POST['rchoixtax'] : null;
            $genre = ($choixtax == 'genre') ? $_POST['rchoixtax'] : null;
            $famille = ($choixtax == 'famille') ? $_POST['rchoixtax'] : null;
        } else {
            $observa = null;
            $cdnom = null;
            $genre = null;
            $famille = null;
        }

        if (!empty($choixloca)) {
            $codecom = ($choixloca == 'commune') ? $_POST['rchoixloca'] : null;
            $idsite = ($choixloca == 'site') ? $_POST['rchoixloca'] : null;
            $site = ($choixloca == 'sitee') ? $_POST['sitee'] : null;
            $poly = ($choixloca == 'poly') ? $_POST['poly'] : null;
            $dist = ($choixloca == 'cercle') ? $_POST['rayon'] : null;
        } else {
            $codecom = null;
            $idsite = null;
            $site = null;
            $poly = null;
            $dist = null;
        }

        $date1 = null;
        $date2 = null;
        $typedate = null;
        if (isset($_POST['date']) && !empty($_POST['date'])) {
            $typedate = 'obs';
            $date1 = DateTime::createFromFormat('d/m/Y', $_POST['date']);
            $date1 = $date1->format('Y-m-d');
            $date2 = DateTime::createFromFormat('d/m/Y', $_POST['date2']);
            $date2 = $date2->format('Y-m-d');
        }

        if (isset($_POST['dates']) && !empty($_POST['dates'])) {
            $typedate = 'saisie';
            $date1 = DateTime::createFromFormat('d/m/Y', $_POST['dates']);
            $date1 = $date1->format('Y-m-d');
            $date2 = DateTime::createFromFormat('d/m/Y', $_POST['dates2']);
            $date2 = $date2->format('Y-m-d');
        }

        $decade = ($_POST['decade'] != 'NR') ? $_POST['decade'] : null;
        $vali = ($_POST['vali'] != 'NR') ? $_POST['vali'] : null;
        // $indice = (!empty($_POST['rindice'])) ? $_POST['rindice'] : null;

        if (!empty($_POST['rstatut'])) {
            if (empty($_POST['rlrr']) && empty($_POST['rlre']) && empty($_POST['rlrf'])) { # Liste rouge Région, Europe, France
                $protection = explode(',', $_POST['rstatut']);

            } else {
                $tmp = explode(',', $_POST['rstatut']);
                $statut = null;
                $con = 'non';
                foreach ($tmp as $n) {
                    if ($n == "'LRR'" && !empty($_POST['rlrr'])) { # Liste Rouge Régionale avec précision
                        $statut = ($con == 'non') ? 'type = ' . $n . ' AND lr IN(' . $_POST['rlrr'] . ')' : $statut . ' OR (type = ' . $n . ' AND lr IN(' . $_POST['rlrr'] . '))';
                        $con = 'oui';
                    }
                    if ($n == "'LRE'" && !empty($_POST['rlre'])) {
                        $statut = ($con == 'non') ? 'type = ' . $n . ' AND lr IN(' . $_POST['rlre'] . ')' : $statut . ' OR (type = ' . $n . ' AND lr IN(' . $_POST['rlre'] . '))';
                        $con = 'oui';
                    }
                    if ($n == "'LRF'" && !empty($_POST['rlrf'])) {
                        $statut = ($con == 'non') ? 'type = ' . $n . ' AND lr IN(' . $_POST['rlrf'] . ')' : $statut . ' OR (type = ' . $n . ' AND lr IN(' . $_POST['rlrf'] . '))';
                        $con = 'oui';
                    }
                }
            }
        } else {
            $statut = null;
        }

    }

    // Memory optimization : select only required fields
    $mv = "";

    // Set filter(s)

    if ($idobservateur) {
        ($where == 'non') ? $and = " WHERE " : $and = " AND ";
        $mv .= $and . "(idmainobser = " . $idobservateur . " OR (idobservateur LIKE '" . $idobservateur . ",%' OR idobservateur LIKE '%, " . $idobservateur . "' OR idobservateur LIKE '%, " . $idobservateur . ",%'))";
        $where = 'oui';
    }
    if ($orga != 'NR') {
        ($where == 'non') ? $and = " WHERE " : $and = " AND ";
        $mv .= $and . "idorg = " . $orga;
        $where = 'oui';
    }
    if ($etude) {
        ($where == 'non') ? $and = " WHERE " : $and = " AND ";
        $mv .= $and . "idetude = " . $etude;
        $where = 'oui';
    }
    if ($typedon != 'NR') {
        ($where == 'non') ? $and = " WHERE " : $and = " AND ";
        $mv .= $and . "typedon = '" . $typedon . "'";
        $where = 'oui';
    }
    if ($flou != 'NR') {
        ($where == 'non') ? $and = " WHERE " : $and = " AND ";
        $mv .= $and . "floutage = " . $flou;
        $where = 'oui';
    }
    if ($observa) {
        ($where == 'non') ? $and = " WHERE " : $and = " AND ";
        $mv .= $and . "observatoire IN (" . $observa . ")";
        $where = 'oui';
    }
    if ($cdnom) {
        ($where == 'non') ? $and = " WHERE " : $and = " AND ";
        $mv .= $and . "cdnom IN (" . $cdnom . ")";
        $where = 'oui';
    }
    if ($genre) {   // Genre
        ($where == 'non') ? $and = " WHERE " : $and = " AND ";
        $mv .= $and . "genre IN (" . $genre . ")";
        $where = 'oui';
    }
    if ($famille) { // Famille
        ($where == 'non') ? $and = " WHERE " : $and = " AND ";
        $mv .= $and . "famille IN (" . $famille . ")";
        $where = 'oui';
    }

    if ($stade !== 0) {
        ($where == 'non') ? $and = " WHERE " : $and = " AND ";
        $mv .= $and . "stade = '" . $stade . "' ";
        $where = 'oui';
    }

    if ($etatbio !== 0 ) {
        ($where == 'non') ? $and = " WHERE " : $and = " AND ";
        $mv .= $and . "etatbio = '" . $etatbio . "' ";
        $where = 'oui';
    }
    if ($methode !== 0 ) {
        ($where == 'non') ? $and = " WHERE " : $and = " AND ";
        $mv .= $and . "methode = '" . $methode . "' ";
        $where = 'oui';
    }
    if ($prospect !== 0 ) {
        ($where == 'non') ? $and = " WHERE " : $and = " AND ";
        $mv .= $and . "prospection = '" . $prospect . "' ";
        $where = 'oui';
    }
    if ($statbio !== 0 ) {
        ($where == 'non') ? $and = " WHERE " : $and = " AND ";
        $mv .= $and . "statutbio = '" . $statbio . "' ";
        $where = 'oui';
    }
    if ($acquisition !== 0 ) {
        ($where == 'non') ? $and = " WHERE " : $and = " AND ";
        $mv .= $and . "type_acquisition = '" . $acquisition . "' ";
        $where = 'oui';
    }
    if ($codecom) {
        ($where == 'non') ? $and = " WHERE " : $and = " AND ";
        $mv .= $and . "codecom IN (" . $codecom . ")";
        $where = 'oui';
    }
    if ($idsite) {
        ($where == 'non') ? $and = " WHERE " : $and = " AND ";
        $mv .= $and . "idsite IN (" . $idsite . ")";
        $where = 'oui';
    }
    if ($site) {
        ($where == 'non') ? $and = " WHERE " : $and = " AND ";
        $mv .= $and . "nom_station ILIKE %" . $site . "%";
        $where = 'oui';
    }
    if (!empty($typedate) && $typedate == 'obs') {
        ($where == 'non') ? $and = " WHERE " : $and = " AND ";
        $mv .= $and . "date_debut_obs >= '" . $date1 . "' AND date_fin_obs <= '" . $date2 . "'";
        $where = 'oui';
    }
    if (!empty($typedate) && $typedate == 'saisie') {
        ($where == 'non') ? $and = " WHERE " : $and = " AND ";
        $mv .= $and . "date_derniere_modif >= '" . $date1 . "' AND date_derniere_modif <= '" . $date2 . "'";
        $where = 'oui';
    }
    if ($decade) {
        ($where == 'non') ? $and = " WHERE " : $and = " AND ";
        $mv .= $and . "decade LIKE '" . $decade . "'";
        $where = 'oui';
    }
    if ($habitat != 'NR') {
        ($where == 'non') ? $and = " WHERE " : $and = " AND ";
        $mv .= $and . "code_habitat LIKE '" . $habitat . "%'";
        $where = 'oui';
    }
    if (!empty($vali)) {
        ($where == 'non') ? $and = " WHERE " : $and = " AND ";
        $mv .= $and . "code_validation = " . $vali;
        $where = 'oui';
    }
    if (!empty($poly)) {
        ($where == 'non') ? $and = " WHERE " : $and = " AND ";
        $mv .= $and . "polygon(path'$poly') @> (lng::text || ',' || lat::text)::point";
        $where = 'oui';
    }
    if (!empty($dist)) {
        ($where == 'non') ? $and = " WHERE " : $and = " AND ";
        $mv .= $and . "(6366*acos(cos(radians(" . $lat . "))*cos(radians(lat))*cos(radians(lng)-radians(" . $lng . "))+sin(radians(" . $lat . "))*sin(radians(lat)))) < " . $dist;
        $where = 'oui';
    }
    if ($photo == 'oui') {
        ($where == 'non') ? $and = " WHERE " : $and = " AND ";
        $mv .= $and . "photo LIKE 'oui'";
        $where = 'oui';
    }
    if ($son == 'oui') {
        ($where == 'non') ? $and = " WHERE " : $and = " AND ";
        $mv .= $and . "son LIKE 'oui'";
        $where = 'oui';
    }
    if ($protection) { // TODO niveau inferieur
        ($where == 'non') ? $and = " WHERE " : $and = " AND ";
        if(in_array("'PN'", $protection)){
            $mv .= $and . "pn IS NOT NULL ";
            $where = 'oui';
        }
        if(in_array("'PR'", $protection)){
            $mv .= $and . "pr IS NOT NULL ";
            $where = 'oui';
        }
        if(in_array("'PD'", $protection)){
            $mv .= $and . "pd IS NOT NULL ";
            $where = 'oui';
        }
        if(in_array("'Z'", $protection)){
            $mv .= $and . "znieff IS NOT NULL ";
            $where = 'oui';
        }
        if(in_array("'DH'", $protection)){
            $mv .= $and . "dh IS NOT NULL ";
            $where = 'oui';
        }
        if(in_array("'LRR'", $protection)){
            $mv .= $and . "lrr IS NOT NULL ";
            $where = 'oui';
        }
        if(in_array("'LRE'", $protection)){
            $mv .= $and . "lre IS NOT NULL ";
            $where = 'oui';
        }
        if(in_array("'LRF'", $protection)){
            $mv .= $and . "lrf IS NOT NULL ";
            $where = 'oui';
        }
    }
    if ($pr != 'NR') {
        ($where == 'non') ? $and = " WHERE " : $and = " AND ";
        $mv .= $and . "localisation = " . $pr;
        $where = 'oui';
    }
    return $mv;
}

function rechercheobservateurid($idm)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->prepare("SELECT idobser FROM referentiel.observateur WHERE idm = :idm ");
    $req->bindValue(':idm', $idm);
    $req->execute();
    $idobser = $req->fetchColumn();
    $req->closeCursor();
    return $idobser;
}

function cherche_observateur($idfiche)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->prepare("SELECT nom, prenom, idm, observateur.idobser FROM obs.plusobser
						INNER JOIN referentiel.observateur ON observateur.idobser = plusobser.idobser
						WHERE idfiche = :idfiche
						ORDER BY idplus ");
    $req->bindValue(':idfiche', $idfiche, PDO::PARAM_INT);
    $req->execute();
    $obsplus = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $obsplus;
}

function listephoto($listefiche)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->query("SELECT DISTINCT photo.idobs AS photo, son.idobs AS son FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						LEFT JOIN site.photo USING(idobs)
						LEFT JOIN site.son USING(idobs)
						WHERE idfiche IN($listefiche) AND (photo.idobs IS NOT NULL OR son.idobs IS NOT NULL) ");
    $resultat = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->closeCursor();
    return $resultat;
}

function deleteElement($element, &$array){
    $index = array_search($element, $array);
    if($index !== false){
        unset($array[$index]);
    }
}

function fields()
{
    if( $_POST['fields'] != "[]") {
        $fields = json_decode($_POST['fields'], true);
    } else {
        $fields = array("nomlatin"); // TODO : mettre au moins un champ par defaut
    }
    return $fields;
}


function get_col_names_array()
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $result = $bdd->query('SELECT * FROM obs.synthese_obs_nflou LIMIT 1;');
    $fields = array_keys($result->fetch(PDO::FETCH_ASSOC));
    $result->closeCursor();
    return $fields;
}

function get_col_names_array_status()
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $result = $bdd->query('SELECT * FROM statut.statut_synthese LIMIT 1;');
    $fields = array_keys($result->fetch(PDO::FETCH_ASSOC));
    $result->closeCursor();
    deleteElement('cdnom_status', $fields);
    return $fields;
}

function get_observatoire_validateur($idm)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->prepare('SELECT gestionobs FROM site.membre WHERE idmembre = :idm ;');
    $req->bindValue(':idm', $idm);
    $req->execute();
    $gestion_observatoires = $req->fetch(PDO::FETCH_ASSOC);

    $req = $bdd->prepare('SELECT discipline FROM site.validateur WHERE idmembre = :idm ;');
    $req->bindValue(':idm', $idm);
    $req->execute();
    $validateur_observatoires = $req->fetch(PDO::FETCH_ASSOC);
    $req->closeCursor();

    $observatoires = $gestion_observatoires['gestionobs'] . $validateur_observatoires['discipline'];
    $observatoires = array_unique(explode(",", str_replace(" ", "", $observatoires)));
    return $observatoires ;
}