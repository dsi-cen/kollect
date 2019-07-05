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
    $fields = implode(",", fields());
}

$all_fields = get_col_names_array();

if ($droits > 3 || $observateurmembre == $idobservateur) {
    $mv = "SELECT idfiche,idobs,idligne," . $fields . " FROM obs.synthese_obs_nflou " . query($where = "non") . " ORDER BY idfiche,idobs,idligne";
} else if ($droits == 3 || $droits == 2) {
    if ($observateurmembre == $idobservateur) {
        $mv = "SELECT idfiche,idobs,idligne," . $fields . " FROM obs.synthese_obs_nflou " . query($where = "non") . " ORDER BY idfiche,idobs,idligne";
    } else {
        $observatoires = implode(",", get_observatoire_validateur($idmembre));
        $observatoires = "('" . str_replace(",", "','", rtrim(trim($observatoires), ",")) . "')";

        // mv1 = les obs dont on est observateur ou co-observateur
        $mv1 = "SELECT * FROM obs.synthese_obs_nflou WHERE (idmainobser = " . $observateurmembre . " OR (idobservateur LIKE '" . $observateurmembre . ",%' OR idobservateur LIKE '%, " . $observateurmembre . "' OR idobservateur LIKE '%, " . $observateurmembre . ",%') OR observatoire IN " . $observatoires . ") " . query($where = 'oui'); # . " ORDER BY idfiche,idobs,idligne"
        // mv2 = les obs des autres personnes à flouter si sensible
        deleteElement('codecom', $all_fields);
        deleteElement('commune', $all_fields);
        deleteElement('id_station', $all_fields);
        deleteElement('nom_station', $all_fields);
        deleteElement('lng', $all_fields);
        deleteElement('lat', $all_fields);
        deleteElement('x', $all_fields);
        deleteElement('y', $all_fields);
        deleteElement('codel93', $all_fields);
        deleteElement('codel935', $all_fields);
        deleteElement('idcoord', $all_fields);
        $case = implode(",", $all_fields);
        $mv2 = "SELECT " . $case . hide_loc() . " FROM obs.synthese_obs_nflou WHERE (idmainobser != " . $observateurmembre . ") AND (idobservateur NOT LIKE '" . $observateurmembre . ",%' AND idobservateur NOT LIKE '%, " . $observateurmembre . "' AND idobservateur NOT LIKE '%, " . $observateurmembre . ",%') AND observatoire NOT IN " . $observatoires . query($where = 'oui') . " ORDER BY idfiche,idobs,idligne";
        $mv = "SELECT " . $fields . " FROM ((" . $mv1 . ") UNION (" . $mv2 . ")) AS res; ";
    }
} else if ($droits == 1) {
    if ($observateurmembre == $idobservateur) {
        $mv = "SELECT idfiche,idobs,idligne," . $fields . " FROM obs.synthese_obs_nflou " . query($where = "non") . " ORDER BY idfiche,idobs,idligne";
    } else {
        // mv1 = les obs dont on est observateur ou co-observateur
        $mv1 = "SELECT * FROM obs.synthese_obs_nflou WHERE (idmainobser = " . $observateurmembre . " OR (idobservateur LIKE '" . $observateurmembre . ",%' OR idobservateur LIKE '%, " . $observateurmembre . "' OR idobservateur LIKE '%, " . $observateurmembre . ",%')) " . query($where = 'oui'); # . " ORDER BY idfiche,idobs,idligne"
        // mv2 = les obs des autres personnes à flouter si sensible
        deleteElement('codecom', $all_fields);
        deleteElement('commune', $all_fields);
        deleteElement('id_station', $all_fields);
        deleteElement('nom_station', $all_fields);
        deleteElement('lng', $all_fields);
        deleteElement('lat', $all_fields);
        deleteElement('x', $all_fields);
        deleteElement('y', $all_fields);
        deleteElement('codel93', $all_fields);
        deleteElement('codel935', $all_fields);
        deleteElement('idcoord', $all_fields);
        $case = implode(",", $all_fields);
        $mv2 = "SELECT " . $case . hide_loc() . " FROM obs.synthese_obs_nflou WHERE (idmainobser != " . $observateurmembre . ") AND (idobservateur NOT LIKE '" . $observateurmembre . ",%' AND idobservateur NOT LIKE '%, " . $observateurmembre . "' AND idobservateur NOT LIKE '%, " . $observateurmembre . ",%') " . query($where = 'oui') . " ORDER BY idfiche,idobs,idligne";
        $mv = "SELECT " . $fields . " FROM ((" . $mv1 . ") UNION (" . $mv2 . ")) AS res; ";
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
$head = explode(',', $fields);
//
fputcsv($fp, $head, chr(9));

// Connect to database
$bdd = PDO2::getInstance();
$bdd->query("SET NAMES 'UTF8'");

// $query->fetchAll(PDO::FETCH_ASSOC) uses too much memory
// Loop each line to limit memory usage

$credits['organisme'] = [];
$credits['observateur'] = [];


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

foreach ($iterator as $res) {

    $geom = str_replace('{"type":"Feature","properties":{},"geometry":', '', $res['geom_geojson']);
    $geom = preg_replace('#}$#', '', $geom);
    $marker = array(
        'type' => 'Feature',
        'properties' => array(),
        'geometry' => array()
    );
    $marker['geometry'] = $geom;
    $geom = null;


    // }

    $data = "{";
    foreach ($all_fields as $f) {
        $value = $res[$f] == "" ? "null" : $res[$f];
        if (!next($all_fields)) {
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
        fwrite($geofile, $geojson ); }

    $geojson = null;

    // Fichier des sources
    $res = convertToISOCharset($res);
    fputcsv($fp, $res, chr(9));
    array_push($credits['organisme'], $res['organisme']);
    array_push($credits['observateur'], $res['observateur']);

}

$credits_organisme = sort(array_unique($credits['organisme']));
$credits_observateur = sort(array_unique(explode(",", implode(",", $credits['observateur']))));


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
