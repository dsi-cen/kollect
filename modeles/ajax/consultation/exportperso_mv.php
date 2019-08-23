<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
require_once('export_functions.php');

session_start();

$idmembre = $_SESSION['idmembre'];
$droits = $_SESSION['droits'];

$idobservateur = $_POST['idobser'];
$observateurmembre = rechercheobservateurid($idmembre);


// Check for custom select
if ($_POST['user_fields'] != "") {
    $fields = $_POST['user_fields'];
} else {
    $fields = implode(',', fields());
}

$all_fields = get_col_names_array();

if ($_POST['status'] == 'oui' || !empty($_POST['rstatut'])){
    $status_fields = implode(',', get_col_names_array_status());
    $fields = $fields . "," . $status_fields;
    $add_status = " LEFT JOIN statut.statut_synthese ON cdref = statut_synthese.cdnom_status ";
} else {$add_status = "";}

if ($droits > 3 || $observateurmembre == $idobservateur) {
    $mv = "SELECT idfiche,idobs,idligne,cdref,observateur,organisme,geom_geojson," . $fields . " FROM obs.synthese_obs_nflou " . $add_status . query($where = "non") . " ORDER BY idfiche,idobs,idligne";
} else if ($droits == 3 || $droits == 2) {
    if ($observateurmembre == $idobservateur) {
        $mv = "SELECT idfiche,idobs,idligne,cdref,observateur,organisme,geom_geojson," . $fields . " FROM obs.synthese_obs_nflou " . $add_status . query($where = "non") . " ORDER BY idfiche,idobs,idligne";
    } else {
        // echo json_encode(get_observatoire_validateur($idmembre));
        $observatoires = implode(",", get_observatoire_validateur($idmembre));
        $observatoires = "('" . str_replace(",", "','", rtrim(trim($observatoires), ",")) . "')";
        $mv1 = "SELECT idfiche,idobs,idligne,cdref,observateur,organisme,geom_geojson," . $fields . " FROM obs.synthese_obs_nflou " . $add_status . "WHERE (((idmainobser = " . $observateurmembre . " OR (idobservateur LIKE '" . $observateurmembre . ",%' OR idobservateur LIKE '%, " . $observateurmembre . "' OR idobservateur LIKE '%, " . $observateurmembre . ",%')) " . query($where = 'oui') . ") OR (( observatoire IN " . $observatoires . " OR (floutage_kollect = 'Pas de dégradation' and taxon_sensible = 'non')) " . query($where = 'oui') . ")) ";
        $mv2 = "SELECT idfiche,idobs,idligne,cdref,observateur,organisme,geom_geojson," . $fields . " FROM obs.synthese_obs_flou " . $add_status . "WHERE (idmainobser != " . $observateurmembre . ") AND (idobservateur NOT LIKE '" . $observateurmembre . ",%' AND idobservateur NOT LIKE '%, " . $observateurmembre . "' AND idobservateur NOT LIKE '%, " . $observateurmembre . ",%') AND observatoire NOT IN " . $observatoires . " " . query($where = 'oui');
        $mv = "((" . $mv1 . ") UNION (" . $mv2 . "))";
    }
} else if ($droits == 1) {
    if ($observateurmembre == $idobservateur) {
        $mv = "SELECT idfiche,idobs,idligne,cdref,observateur,organisme,geom_geojson," . $fields . " FROM obs.synthese_obs_nflou " . $add_status . query($where = "non") . " ORDER BY idfiche,idobs,idligne";
    } else {
        $mv1 = "SELECT idfiche,idobs,idligne,cdref,observateur,organisme,geom_geojson," . $fields . " FROM obs.synthese_obs_nflou " . $add_status . "WHERE (((idmainobser = " . $observateurmembre . " OR (idobservateur LIKE '" . $observateurmembre . ",%' OR idobservateur LIKE '%, " . $observateurmembre . "' OR idobservateur LIKE '%, " . $observateurmembre . ",%')) " . query($where = 'oui') . ") OR (( (floutage_kollect = 'Pas de dégradation' and taxon_sensible = 'non')) " . query($where = 'oui') . ")) ";
        $mv2 = "SELECT idfiche,idobs,idligne,cdref,observateur,organisme,geom_geojson," . $fields . " FROM obs.synthese_obs_flou " . $add_status . "WHERE (idmainobser != " . $observateurmembre . ") AND (idobservateur NOT LIKE '" . $observateurmembre . ",%' AND idobservateur NOT LIKE '%, " . $observateurmembre . "' AND idobservateur NOT LIKE '%, " . $observateurmembre . ",%') " . query($where = 'oui');
        $mv = "((" . $mv1 . ") UNION (" . $mv2 . "))";
    }
}


// Save custom fileds for another export ?
if ($_POST['custom_fields'] != "") {
    $label = $_POST['custom_fields'];
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $bdd->query("CREATE TABLE IF NOT EXISTS site.custom_queries (idm integer, label varchar, fields varchar);");
    $req = $bdd->prepare("INSERT INTO site.custom_queries VALUES (:idm, :label, :fields); ");
    $req->bindValue(':idm', $idmembre);
    $req->bindValue(':label', $label);
    $req->bindValue(':fields', $fields);
    $req->execute();
    $req->closeCursor();
}

// Create a new file
$bytes = random_bytes(45);
$ts = date("Ymd");
$name = $ts . "_" . $idmembre . "_" . bin2hex($bytes);


// Liste des contributeurs à citer si utilisation de l'export dans une publi
$src = fopen('../../../exports/' . $name . ".txt", 'w');

// Data
$fp = fopen('../../../exports/' . $name . ".tsv", 'w');

// GeoJSON
$geofile = fopen('../../../exports/' . $name . ".geojson", 'w');
fwrite($geofile, '{ "type": "FeatureCollection", "features": [');

// Headers
$head = "idfiche,idobs,idligne,cdref,observateur,organisme,geom_geojson," . $fields;
$head = explode(',', $head);
//
fputcsv($fp, $head, chr(9));

// Connect to database
$bdd = PDO2::getInstance();
$bdd->query("SET NAMES 'UTF8'");

// $query->fetchAll(PDO::FETCH_ASSOC) uses too much memory
// Loop each line to limit memory usage

$credits_organisme = [];
$credits_observateur = [];

/*
$e = null;
foreach ($query as $r) {
    if ($e) {
        "...do write here...";
    }
    $e = $r;
}
if ($e) {
    "...last write here...";
}
*/

$result = $bdd->query($mv, PDO::FETCH_ASSOC);
$iterator = new CachingIterator(new IteratorIterator($result), 0);

$ff = explode(',',$fields);

foreach ($iterator as $res) {

    if ($res['geom_geojson'] != "") {
        $geom = str_replace('{"type":"Feature","properties":{},"geometry":', '', $res['geom_geojson']);
        $geom = preg_replace('#}$#', '', $geom);


        $marker = array(
            'type' => 'Feature',
            'properties' => array(),
            'geometry' => array()
        );
        $marker['geometry'] = $geom; // TODO gérer if null
        $geom = null;

        $data = "{";

        foreach ($ff as $f) {
            $value = $res[$f] == "" ? "null" : $res[$f];
            if (!next($ff)) {
                // This is the last $element
                $data .= '"' . $f . '":"' . $value . '"}';
            } else {
                $data .= '"' . $f . '":"' . $value . '",';
            }
        }
        $marker['properties'] = $data;
        $data = null;
        $geojson = json_encode($marker, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $geojson = str_replace('"*', '', (string)$geojson);
        $geojson = str_replace('*"', '', (string)$geojson);
        $geojson = str_replace('\\', '', (string)$geojson);
        $geojson = str_replace('""', '"', (string)$geojson);
        $geojson = str_replace('"{', '{', (string)$geojson);
        $geojson = str_replace('}"', '}', (string)$geojson);
        $geojson = str_replace('"null"', 'null', (string)$geojson);

        if ($iterator->hasNext()) { // If it's not the last row ...
            fwrite($geofile, $geojson . ",");
        } else {
            fwrite($geofile, $geojson);
        }

        $geojson = null;

    }

    // Fichier des sources
    $res = convertToISOCharset($res);
    $res['remarques_obs'] = str_replace('"', "'", $res['rqobs']);
    fputcsv($fp, $res, chr(9));

    $credits_organisme[] = $res['organisme'];
    $credits_observateur[] = $res['observateur'];
}

$credits_organisme = array_map('trim', $credits_organisme);
$credits_organisme = array_unique($credits_organisme);
// $credits_organisme = sort($credits_organisme);
$credits_observateur = explode(",", implode(",", $credits_observateur));
$credits_observateur = array_map('trim',$credits_observateur);
$credits_observateur = array_unique($credits_observateur);
// $credits_observateur = sort($credits_observateur);

fwrite($src, "Liste des organismes\n\n");
fwrite($src, implode(", ", $credits_organisme));
fwrite($src, "\n\nListe des observateurs\n\n");
fwrite($src, implode(", ", $credits_observateur));

// Finalize GeoJSON

fwrite($geofile, ' ] } ');

// Close files
fclose($fp);
fclose($src);
fclose($geofile);

// Send name
echo json_encode($name);
