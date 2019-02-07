<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

$reqvue = "WITH concat_otherobser AS
(
 SELECT idfiche,string_agg(observateur.observateur, ', ') AS otherobser, string_agg(observateur.idobser::varchar,', ') AS idotherobser
 FROM obs.plusobser
 JOIN referentiel.observateur ON observateur.idobser = plusobser.idobser
 GROUP BY idfiche
 ORDER BY idfiche,otherobser
),

infos_validateur AS
(
 SELECT DISTINCT idobs,m.nom || ' ' || m.prenom AS validateur,vali,typevali, max(dateval) AS dateval
 FROM vali.histovali h
 JOIN site.membre m ON m.idmembre = h.idm
 GROUP BY idobs,validateur,vali,typevali
 ORDER BY idobs
),

infos_collection AS
(
 SELECT idobs,max(idcol) AS idcol
 FROM obs.obscoll
 GROUP BY idobs
),

infos_photo AS
(
 SELECT idobs
 FROM site.photo
 GROUP BY idobs
),

infos_son AS
(
 SELECT idobs
 FROM site.son
 GROUP BY idobs
),

infos_plante AS
(
  SELECT idobs, obsplte.cdnom, liste.nom || ' ' || liste.auteur AS nomlatincomplet,obsplte.stade
  FROM obs.obsplte
  JOIN referentiel.liste ON liste.cdnom = obsplte.cdnom
),

infos_fiche AS
(
SELECT
  fiche.idfiche,fiche.idorg,organisme.organisme,fiche.idetude,etude.etude,fiche.typedon,
  CASE WHEN fiche.typedon = 'Pr' THEN 'Origine privée' WHEN fiche.typedon = 'Ac' THEN 'Publique' WHEN fiche.typedon = 'NSP' THEN 'Inconnue' END AS type_donnee,
  fiche.plusobser, fiche.codecom,fiche.floutage,
  CASE WHEN fiche.floutage = 0 THEN 'Pas de dégradation' WHEN fiche.floutage = 1 THEN 'Commune' WHEN fiche.floutage = 2 THEN 'Maille 10kmx10km' WHEN fiche.floutage = 3 THEN 'Département' END AS floutage_kollect,
  fiche.localisation,
  CASE WHEN fiche.localisation = '1' THEN 'Coordonnée' WHEN fiche.localisation = '2' THEN 'Commune' WHEN fiche.localisation = '3' THEN 'Département' WHEN fiche.localisation = '4' THEN 'Emprise de site' END AS type_localisation,
  commune.commune, site.idsite,site.site, fiche.iddep, fiche.date1, fiche.date2, fiche.decade,fiche.idobser AS idmainobser, observateur.observateur AS mainobser,
  CASE WHEN plusobser = 'oui' THEN observateur.observateur || ', ' || concat_otherobser.otherobser WHEN plusobser = 'non' THEN observateur.observateur END AS observateur,
  CASE WHEN plusobser = 'oui' THEN fiche.idobser::varchar || ', ' || concat_otherobser.idotherobser WHEN plusobser = 'non' THEN fiche.idobser::varchar END AS idobservateur,
  CASE WHEN cg.geo LIKE '{\"type\":\"Feature\",\"properties\":{},\"geometry\":{\"type\":\"Polygon\"%' THEN 'Polygone' WHEN cg.geo LIKE '{\"type\":\"Feature\",\"properties\":{},\"geometry\":{\"type\":\"LineString\"%' THEN 'Linéaire' WHEN cg.geo IS NULL THEN 'Point' END AS type_geometrie,
  c.x, c.y, c.lng, c.lat, c.codel93, c.codel935, cp.lbpreci AS precision_coord
FROM obs.fiche
LEFT JOIN concat_otherobser ON concat_otherobser.idfiche = fiche.idfiche
JOIN referentiel.commune ON commune.codecom = fiche.codecom
LEFT JOIN obs.site ON site.idsite = fiche.idsite
JOIN obs.coordonnee c ON c.idcoord = fiche.idcoord
JOIN referentiel.observateur ON observateur.idobser = fiche.idobser
JOIN referentiel.organisme ON organisme.idorg = fiche.idorg
JOIN referentiel.etude ON etude.idetude = fiche.idetude
JOIN referentiel.coordprecision cp ON cp.idpreci = fiche.idpreci
LEFT JOIN obs.coordgeo cg ON cg.idcoord = c.idcoord
ORDER BY idfiche
),

infos_obs AS
(
SELECT
  infos_fiche.idfiche,obs.idobs,obs.cdnom,obs.cdref,infos_fiche.date1 AS date_debut_obs, infos_fiche.date2 AS date_fin_obs,infos_fiche.decade,
  CASE WHEN se.sensible IS NOT NULL THEN 'oui' ELSE 'non' END AS taxon_sensible,
  CASE WHEN se.sensible = 1 THEN 'Commune' WHEN se.sensible = 2 THEN 'Maille 10kmx10km' WHEN se.sensible = 3 THEN 'Département' END AS floutage_sensible,
  'TAXREF v12.0' AS referentiel,obs.nom_cite, hos.date_insert AS date_insertion, hos.date_update AS date_derniere_modif, obs.rqobs,p.protocole AS type_acquisition, obs.statutobs,
  CASE WHEN obs.statutobs = 'No' THEN 'Non observé' WHEN obs.statutobs = 'Pr' THEN 'Présent' WHEN obs.statutobs = 'NR' THEN 'Inconnu' ELSE 'Inconnu' END AS statut_observation,
  obs.validation AS code_validation,
  CASE WHEN obs.validation = 1 THEN '1 - Certain - très probable' WHEN obs.validation = 2 THEN '2 - Probable' WHEN obs.validation = 3 THEN '3 - Douteux' WHEN obs.validation = 4 THEN '4 - Invalide' WHEN obs.validation = 5 THEN '5 - Non réalisable' WHEN obs.validation = 6 THEN '6 - Non évalué (validation en cours)' END AS statut_validation,
  iv.validateur,
  CASE WHEN iv.typevali IS NOT NULL THEN iv.dateval WHEN iv.typevali IS NULL AND obs.validation = 1 THEN date_insert END AS date_validation,
  CASE WHEN iv.typevali = 1 THEN 'Validation informatique' WHEN iv.typevali = 2 THEN 'Validation manuelle' WHEN obs.validation = 1 AND iv.typevali IS NULL THEN 'Non soumis à validation' END AS type_validation,
  liste.nom AS nomlatin,liste.nom || ' ' || liste.auteur AS nomlatincomplet, liste.nomvern,tx.nomvern AS nomverncomplet, liste.observatoire,tx.rang,tx.regne,tx.classe,tx.ordre,tx.famille, infos_fiche.idmainobser,infos_fiche.idobservateur,infos_fiche.observateur, observateur.observateur AS determinateur,oc.typedet AS type_determination,
  CASE WHEN oc.iddetcol IS NULL THEN 'non' WHEN oc.iddetcol IS NOT NULL THEN 'oui'END as en_collection,
  infos_fiche.idorg,infos_fiche.organisme,infos_fiche.idetude,infos_fiche.etude,infos_fiche.typedon,infos_fiche.type_donnee,infos_fiche.codecom,
  infos_fiche.commune,infos_fiche.idsite,infos_fiche.site,infos_fiche.iddep,infos_fiche.date1,infos_fiche.date2,infos_fiche.type_geometrie,
  infos_fiche.localisation, infos_fiche.type_localisation,infos_fiche.x,infos_fiche.y,infos_fiche.lng,infos_fiche.lat,infos_fiche.precision_coord,
  infos_fiche.floutage,infos_fiche.floutage_kollect,infos_fiche.codel93,infos_fiche.codel935
FROM infos_fiche
JOIN obs.obs ON obs.idfiche = infos_fiche.idfiche
JOIN referentiel.liste ON liste.cdnom = obs.cdref
JOIN referentiel.taxref tx ON tx.cdnom = obs.cdref
JOIN referentiel.observateur ON obs.iddet = observateur.idobser
LEFT JOIN infos_validateur iv ON (iv.idobs = obs.idobs) AND (iv.vali = obs.validation)
LEFT JOIN referentiel.protocole p ON p.idprotocole = obs.idprotocole
LEFT JOIN obs_historique.histo_obs_synthese hos ON hos.idobs = obs.idobs
LEFT JOIN infos_collection ic ON ic.idobs = obs.idobs
LEFT JOIN obs.obscoll oc ON oc.idcol = ic.idcol
LEFT JOIN referentiel.sensible se ON se.cdnom = obs.cdref
ORDER BY infos_fiche.idfiche, obs.idobs)

SELECT
  i.idfiche,i.idobs,l.idligne,i.code_validation,i.statut_validation,i.date_debut_obs,i.date_fin_obs,i.decade,i.cdnom,i.cdref,i.referentiel,i.taxon_sensible,i.floutage_sensible,i.nom_cite,
  i.rang,i.regne,i.classe,i.ordre,i.famille,i.observatoire,
  i.nomlatin,i.nomlatincomplet,i.nomvern,i.nomverncomplet,i.idmainobser,i.observateur,i.idobservateur,i.determinateur,i.type_determination,i.en_collection,
  i.idorg,i.organisme,i.idetude,i.etude,i.typedon,i.type_donnee,i.codecom,i.commune,i.iddep,i.floutage,i.floutage_kollect,i.type_geometrie,i.idsite AS id_station, i.site AS nom_station,
  i.localisation, i.type_localisation,i.lng,i.lat,i.x,i.y,i.precision_coord,i.codel93,i.codel935,
  i.type_acquisition,i.statutobs,i.statut_observation,oe.etatbio,occmort.cause AS cause_mort,
  s.stade,(l.nbmin + l.nbmax)/2 AS nb_tot, l.ndiff, l.male, l.femelle, l.nbmin, l.nbmax,
  CASE WHEN l.denom = 'Co' THEN 'Compté' WHEN denom = 'Es' THEN 'Estimé' WHEN denom = 'NSP' THEN 'Non Renseigné' END AS denom,
  ot.typedenom,m.methode,p.prospection,c.libcomp AS comportement,ipl.cdnom AS cdnom_plante_associee, ipl.nomlatincomplet AS nomlatin_plante_associee,os.statutbio, aves.code AS code_reproduction,
  i.rqobs,eunis.lbcode AS code_habitat, eunis.lbcode || ' - ' ||eunis.lbhabitat as nom_habitat,
  CASE WHEN ip.idobs IS NULL THEN 'non' ELSE 'oui'END AS photo,
  CASE WHEN son.idobs IS NULL THEN 'non' ELSE 'oui'END AS son,
  i.date_insertion,i.date_derniere_modif,i.date_validation,i.type_validation,i.validateur
FROM infos_obs i
LEFT JOIN obs.ligneobs l ON l.idobs = i.idobs
LEFT JOIN referentiel.stade s ON s.idstade = l.stade
LEFT JOIN referentiel.occtype ot ON ot.tdenom = l.tdenom
LEFT JOIN referentiel.occetatbio oe ON oe.idetatbio = l.idetatbio
LEFT JOIN referentiel.methode m ON m.idmethode = l.idmethode
LEFT JOIN referentiel.prospection p ON p.idpros = l.idpros
LEFT JOIN referentiel.comportement c ON c.idcomp = l.idcomp
LEFT JOIN referentiel.occstatutbio os ON os.idstbio = l.idstbio
LEFT JOIN obs.aves ON (aves.idobs = i.idobs) AND (aves.stade = l.stade)
LEFT JOIN infos_photo ip ON ip.idobs = i.idobs
LEFT JOIN infos_son son ON son.idobs = i.idobs
LEFT JOIN obs.obshab ON obshab.idobs = i.idobs
LEFT JOIN referentiel.eunis ON obshab.cdhab = eunis.cdhab
LEFT JOIN obs.obsmort om ON om.idobs = i.idobs AND om.stade = l.stade
LEFT JOIN infos_plante ipl ON ipl.idobs = i.idobs AND ipl.stade = l.stade
LEFT JOIN referentiel.occmort ON om.mort = occmort.idmort";

if(isset($_POST['choixtax']) && isset($_POST['choixloca']))
{
    $idobser = $_POST['idobser'];
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

    if(!empty($choixtax))
    {
        $observa = ($choixtax == 'observa') ? $_POST['rchoixtax'] : null;
        $cdnom = ($choixtax == 'espece') ? $_POST['rchoixtax'] : null;
    }
    else
    {
        $observa = null; $cdnom = null;
    }
    if(!empty($choixloca))
    {
        $codecom = ($choixloca == 'commune') ? $_POST['rchoixloca'] : null;
        $idsite = ($choixloca == 'site') ? $_POST['rchoixloca'] : null;
        $site = ($choixloca == 'sitee') ? $_POST['sitee'] : null;
        $poly = ($choixloca == 'poly') ? $_POST['poly'] : null;
        $dist = ($choixloca == 'cercle') ? $_POST['rayon'] : null;
    }
    else
    {
        $codecom = null; $idsite = null; $site = null; $poly = null; $dist = null;
    }
    $date1 = null; $date2 = null; $typedate = null;
    if(isset($_POST['date']) && !empty($_POST['date']))
    {
        $typedate = 'obs';
        $date1 = DateTime::createFromFormat('d/m/Y', $_POST['date']);
        $date1 = $date1->format('Y-m-d');
        $date2 = DateTime::createFromFormat('d/m/Y', $_POST['date2']);
        $date2 = $date2->format('Y-m-d');
    }
    if(isset($_POST['dates']) && !empty($_POST['dates']))
    {
        $typedate = 'saisie';
        $date1 = DateTime::createFromFormat('d/m/Y', $_POST['dates']);
        $date1 = $date1->format('Y-m-d');
        $date2 = DateTime::createFromFormat('d/m/Y', $_POST['dates2']);
        $date2 = $date2->format('Y-m-d');
    }
    $decade = ($_POST['decade'] != 'NR') ? $_POST['decade'] : null;
    $vali = ($_POST['vali'] != 'NR') ? $_POST['vali'] : null;
    $indice = (!empty($_POST['rindice'])) ? $_POST['rindice'] : null;

    if(!empty($_POST['rstatut']))
    {
        if(empty($_POST['rlrr']) && empty($_POST['rlre']) && empty($_POST['rlrf']))
        {
            $statut = 'type IN('.$_POST['rstatut'].')';
        }
        else
        {
            $tmp = explode(',', $_POST['rstatut']);
            $statut = null; $con = 'non';
            foreach($tmp as $n)
            {
                if($n == "'LRR'" && !empty($_POST['rlrr']))
                {
                    $statut = ($con == 'non') ? 'type = '.$n.' AND lr IN('.$_POST['rlrr'].')' : $statut.' OR (type = '.$n.' AND lr IN('.$_POST['rlrr'].'))';
                    $con = 'oui';
                }
                if($n == "'LRE'" && !empty($_POST['rlre']))
                {
                    $statut = ($con == 'non') ? 'type = '.$n.' AND lr IN('.$_POST['rlre'].')' : $statut.' OR (type = '.$n.' AND lr IN('.$_POST['rlre'].'))';
                    $con = 'oui';
                }
                if($n == "'LRF'" && !empty($_POST['rlrf']))
                {
                    $statut = ($con == 'non') ? 'type = '.$n.' AND lr IN('.$_POST['rlrf'].')' : $statut.' OR (type = '.$n.' AND lr IN('.$_POST['rlrf'].'))';
                    $con = 'oui';
                }
            }
        }
    }
    else
    {
        $statut = null;
    }
}

if ($idobser) { $reqvue .= " WHERE (i.idmainobser = " . $idobser . " OR (idobservateur LIKE '" . $idobser . "%' OR idobservateur LIKE '%, " . $idobser . "' OR idobservateur LIKE '%, " . $idobser . ",%'))"; }
if ($orga != 'NR') { $reqvue .= " WHERE i.idorg = " . $orga ; }
if ($etude) { $reqvue .= " WHERE i.idetude = " . $etude ; }
if ($typedon != 'NR') { $reqvue .= " WHERE typedon = '" . $typedon . "'"; }
if ($flou != 'NR') { $reqvue .= " WHERE floutage = " . $flou ; }
if ($observa) { $reqvue .= " WHERE i.observatoire IN (" . $observa . ")"; }
if ($cdnom) { $reqvue .= " WHERE i.cdnom IN (" . $cdnom . ")"; }
if ($codecom) { $reqvue .= " WHERE i.codecom IN (" . $codecom . ")"; }

// WHERE localisation =

$reqvue2 .= "-- 
-- 
--
--
--WHERE i.idsite = '8'
--WHERE i.site LIKE '%33%'
--WHERE date_debut_obs >= '2019-01-01' AND date_fin_obs <= '2019-01-31'
--AND date_insertion = '2019-02-07'
--AND date_derniere_modif = '2019-02-07'
--WHERE decade = 'N2'
--WHERE eunis.lbcode = 'I1.1'
--WHERE i.code_validation = 6
--WHERE ip.idobs IS NOT NULL
--WHERE son.idobs IS NOT NULL
--WHERE statutobs != 'No'";

$reqvue .= " ORDER BY i.idobs, l.idligne";

    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $query = $bdd->query($reqvue);
    $res = $query->fetchAll(PDO::FETCH_ASSOC);

    function convertToISOCharset($array) {
        foreach($array as $key => $value) {
            if(is_array($value)) {
                $array[$key] = convertToISOCharset($value);
            }
            else {
                $array[$key] = mb_convert_encoding($value, 'ISO-8859-1', 'UTF-8');
            }
        }
        return $array;
    }

    $res = convertToISOCharset($res);

    $bytes = random_bytes(45);
    $name = bin2hex($bytes);

    $fp = fopen('../../../exports/' . $name . ".csv", 'w');

    $first = true;
    foreach ($res as $val) {
        if ($first) {
            fputcsv($fp, array_keys($val),chr(9));
            $first = false;
        }
        fputcsv($fp, $val, chr(9));
    }

    fclose($fp);

    echo json_encode($name);