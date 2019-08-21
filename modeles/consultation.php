<?php
function chercheobmembre($idm)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT idobser, observateur.nom, observateur.prenom FROM site.membre
						LEFT JOIN referentiel.observateur ON observateur.idm = membre.idmembre
						WHERE idmembre = :idm ");
	$req->bindValue(':idm', $idm);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_typeobs($idobser)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT COUNT(idobs) AS nb, floutage FROM obs.obs
						INNER JOIN obs.fiche USING(idfiche)
						WHERE idobser = :idobser
						GROUP BY floutage ");
	$req->bindValue(':idobser', $idobser);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function etude()
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT idetude, etude FROM referentiel.etude WHERE masquer = 'oui' ORDER BY etude ");
	$resultats = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultats;
}
function organisme()
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT idorg, organisme FROM referentiel.organisme ORDER BY organisme ");
	$resultats = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultats;
}
function habitat()
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT cdhab, lbcode, lbhabitat FROM referentiel.eunis WHERE locale = 'oui' ORDER BY lbcode ");
	$resultats = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultats;
}
function statut()
{
	$bdd = PDO2::getInstance();		
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->query("SELECT DISTINCT type FROM statut.statutsite ");
	$resultats = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultats;
}

function deleteElement($element, &$array){
    $index = array_search($element, $array);
    if($index !== false){
        unset($array[$index]);
    }
}

function get_col_names() # Liste des champs sélectionnable à l'export
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $result = $bdd->query('SELECT * FROM obs.synthese_obs_nflou LIMIT 1;');
    $fields = array_keys($result->fetch(PDO::FETCH_ASSOC));
    $result->closeCursor();

    deleteElement('idfiche', $fields);
    deleteElement('idobs', $fields);
    deleteElement('idligne', $fields);
    deleteElement('cdref', $fields);
    deleteElement('organisme', $fields);
    deleteElement('observateur', $fields);

    $select = "<select id='fields' multiple='multiple'>";
    foreach($fields as $field){
        $select .= '<option value="' . $field . '">' . $field . '</option>';
    }
    $select .= '</select>';
    return $select;
}

function get_custom_fields($idm)
{
    $bdd = PDO2::getInstance();
    $bdd->query("SET NAMES 'UTF8'");
    $req = $bdd->prepare('SELECT "label", fields FROM site.custom_queries WHERE idm = :idm;');
    $req->bindValue(':idm', $idm);
    $req->execute();
    $queries = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->closeCursor();
    $select = "<select id='user_fields'>";
    $select .= '<option value="">--</option>';
    foreach($queries as $query){
        $select .= '<option value="' . $query['fields'] . '">' . $query['label'] . '</option>';
    }
    $select .= '</select>';
    return $select;
}

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