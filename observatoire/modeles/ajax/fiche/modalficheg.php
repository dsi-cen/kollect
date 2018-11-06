<?php
include '../../../../global/configbase.php';
include '../../../lib/pdo2.php';

function recherche_compoly($codecom)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT poly, geojson FROM referentiel.commune WHERE codecom = :codecom ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':codecom', $codecom);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_comdep($iddep)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT poly, geojson FROM referentiel.departement WHERE iddep = :iddep ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':iddep', $iddep);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function recherche_mgrs($id)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT geo FROM referentiel.mgrs10 WHERE mgrs = :utm ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':utm', $id);
	$req->execute();
	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function nbobsdep($nomvar,$genre,$cdnom,$id)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT DISTINCT EXTRACT(YEAR FROM date1) AS annee, COUNT(idobs) AS nb FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						WHERE (obs.cdref = :cdref OR genre = :genre) AND iddep = :id
						GROUP BY annee ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':cdref', $cdnom);
	$req->bindValue(':genre', $genre);
	$req->bindValue(':id', $id);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function nbobscom($nomvar,$genre,$cdnom,$id)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT DISTINCT EXTRACT(YEAR FROM date1) AS annee, COUNT(idobs) AS nb FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						WHERE (obs.cdref = :cdref OR genre = :genre) AND codecom = :id
						GROUP BY annee ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':cdref', $cdnom);
	$req->bindValue(':genre', $genre);
	$req->bindValue(':id', $id);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function nbobsl93($nomvar,$genre,$cdnom,$id)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT DISTINCT EXTRACT(YEAR FROM date1) AS annee, COUNT(idobs) AS nb FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
						WHERE (obs.cdref = :cdref OR genre = :genre) AND codel93 = :id
						GROUP BY annee ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':cdref', $cdnom);
	$req->bindValue(':genre', $genre);
	$req->bindValue(':id', $id);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
function nbobsutm($nomvar,$genre,$cdnom,$id)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT DISTINCT EXTRACT(YEAR FROM date1) AS annee, COUNT(idobs) AS nb FROM obs.fiche
						INNER JOIN obs.obs USING(idfiche)
						INNER JOIN $nomvar.liste ON liste.cdnom = obs.cdref
						INNER JOIN obs.coordonnee ON coordonnee.idcoord = fiche.idcoord
						WHERE (obs.cdref = :cdref OR genre = :genre) AND utm = :id
						GROUP BY annee ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':cdref', $cdnom);
	$req->bindValue(':genre', $genre);
	$req->bindValue(':id', $id);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}
if (isset($_POST['id']) && isset($_POST['type']))
{
	$id = $_POST['id'];
	$type = $_POST['type'];
	$utm = $_POST['utm'];
	$cdnom = $_POST['cdnom'];
	$genre = $_POST['genre'];
	$nomvar = $_POST['nomvar'];
	
	if($type == 'com' || $type == 'dep')
	{
		if($type == 'com')
		{
			$coord = recherche_compoly($id);
			$nbobs = nbobscom($nomvar,$genre,$cdnom,$id);
		}
		elseif($type == 'dep')
		{
			if($id == 1 || $id == 2 || $id == 3 || $id == 4 || $id == 5 || $id == 6 || $id == 7 || $id == 8 || $id == 9)
			{
				$coord = recherche_comdep('0'.$id);
				$nbobs = nbobsdep($nomvar,$genre,$cdnom,'0'.$id);				
			}
			else
			{
				$coord = recherche_comdep($id);
				$nbobs = nbobsdep($nomvar,$genre,$cdnom,$id);
			}
			$retour['nbobs'] = $nbobs;
		}
		$resultats = array('crs'=>array('type'=>'name','properties'=>array('name'=>'urn:ogc:def:crs:EPSG::2154')), 'features' => array());
		$feature = array('type' => 'Feature', 'geometry' => Null);
		$feature['geometry'] = array('type' => $coord['poly'], 'coordinates' => $coord['geojson']);
		$resultats['features'][] = $feature;
		$tmpcarto = json_encode($resultats, JSON_NUMERIC_CHECK);
		$tmpcarto = str_replace('"[','[',$tmpcarto);
		$tmpcarto = str_replace(']"',']',$tmpcarto);
		$resultats = json_decode($tmpcarto);
		$retour['carto'] = $resultats;	
	}
	elseif($type == 'maille')
	{
		if($utm == 'non')
		{		
			$nbobs = nbobsl93($nomvar,$genre,$cdnom,$id);
			$xg = substr($id, 1, -4)*10000;
			$yb = substr($id, 5)*10000;
			$xd = $xg + 10000;
			$yh = $yb + 10000;
			$resultats = array('crs'=>array('type'=>'name','properties'=>array('name'=>'urn:ogc:def:crs:EPSG::2154')), 'features' => array());
			$feature = array('type' => 'Feature', 'geometry' => Null);
			$feature['geometry'] = array('type' => 'Polygon', 'coordinates' => array([[intval($xg), intval($yb)],[intval($xg), intval($yh)],[intval($xd), intval($yh)],[intval($xd), intval($yb)]]));
			$resultats['features'][] = $feature;
			$retour['carto'] = $resultats;
		}
		else
		{
			$coord = recherche_mgrs($id);
			$nbobs = nbobsutm($nomvar,$genre,$cdnom,$id);
			$resultats = array('crs'=>array('type'=>'name','properties'=>array('name'=>'urn:ogc:def:crs:EPSG::2154')), 'features' => array());
			$feature = array('type' => 'Feature', 'geometry' => Null);
			$feature['geometry'] = array('type' => 'Polygon', 'coordinates' => $coord['geo']);
			$resultats['features'][] = $feature;
			$tmpcarto = json_encode($resultats, JSON_NUMERIC_CHECK);
			$tmpcarto = str_replace('"[','[',$tmpcarto);
			$tmpcarto = str_replace(']"',']',$tmpcarto);
			$resultats = json_decode($tmpcarto);
			$retour['carto'] = $resultats;
		}
	}
	elseif($type == 'maille5')
	{
		$xg = substr($id, 2, -5)*1000;
		$yb = substr($id, 6)*1000;
		$xd = $xg + 5000;
		$yh = $yb + 5000;
		$resultats = array('crs'=>array('type'=>'name','properties'=>array('name'=>'urn:ogc:def:crs:EPSG::2154')), 'features' => array());
		$feature = array('type' => 'Feature', 'geometry' => Null);
		$feature['geometry'] = array('type' => 'Polygon', 'coordinates' => array([[intval($xg), intval($yb)],[intval($xg), intval($yh)],[intval($xd), intval($yh)],[intval($xd), intval($yb)]]));
		$resultats['features'][] = $feature;
		$retour['carto'] = $resultats;		
	}
	
	if($type != 'maille5')
	{
		$json_site = file_get_contents('../../../../json/site.json');
		$rjson = json_decode($json_site, true);
		$anneeencours = date('Y');
		if(isset($rjson['fiche']['classefiche'])) 
		{
			$nbclasse = count($rjson['fiche']['classefiche']);
			foreach($rjson['fiche']['classefiche'] as $n)
			{		
				if($n['classe'] == 'classe1') {$an1 = $anneeencours - 1;}
				elseif($n['classe'] == 'classe2') {$an2 = $n['annee'];}
				elseif($n['classe'] == 'classe3') {$an3 = $n['annee'];}
				elseif($n['classe'] == 'classe4') {$an4 = $n['annee'];}
				elseif($n['classe'] == 'classe5') {$an5 = $n['annee'];}
				elseif($n['classe'] == 'classe6') {$an6 = $n['annee'];}
			}		
		}	
		$nb1 = 0; $nb2 = 0; $nb3 = 0; $nb4 = 0; $nb5 = 0; $nb6 = 0;
		foreach ($nbobs as $n)
		{
			if($nbclasse == 3)
			{
				if ($n['annee'] == $anneeencours) {$nb1 = $nb1 + 1;}
				elseif ($n['annee'] >= $an2 && $n['annee'] < $anneeencours) {$nb2 = $n['nb'] + $nb2;}
				elseif ($n['annee'] < $an3) {$nb3 = $n['nb'] + $nb3;}			
			}
			elseif($nbclasse == 4)
			{
				if ($n['annee'] == $anneeencours) {$nb1 = $nb1 + 1;}
				elseif ($n['annee'] >= $an2 && $n['annee'] < $anneeencours) {$nb2 = $n['nb'] + $nb2;}
				elseif ($n['annee'] < $an2 && $n['annee'] >= $an3) {$nb3 = $n['nb'] + $nb3;}
				elseif ($n['annee'] < $an4) {$nb4 = $n['nb'] + $nb4;}				
			}
			elseif($nbclasse == 5)
			{
				if ($n['annee'] == $anneeencours) {$nb1 = $n['nb'] + $nb1;}
				elseif ($n['annee'] >= $an2 && $n['annee'] < $anneeencours) {$nb2 = $n['nb'] + $nb2;}
				elseif ($n['annee'] < $an2 && $n['annee'] >= $an3) {$nb3 = $n['nb'] + $nb3;}
				elseif ($n['annee'] < $an3 && $n['annee'] >= $an4) {$nb4 = $n['nb'] + $nb4;}
				elseif ($n['annee'] < $an5) {$nb5 = $n['nb'] + $nb5;}					
			}
			elseif($nbclasse == 6)
			{
				if($n['annee'] == $anneeencours) {$nb1 = $n['nb'] + $nb1;}
				elseif ($n['annee'] >= $an2 && $n['annee'] < $anneeencours) {$nb2 = $n['nb'] + $nb2;}
				elseif ($n['annee'] < $an2 && $n['annee'] >= $an3) {$nb3 = $n['nb'] + $nb3;}
				elseif ($n['annee'] < $an3 && $n['annee'] >= $an4) {$nb4 = $n['nb'] + $nb4;}
				elseif ($n['annee'] < $an4 && $n['annee'] >= $an5) {$nb5 = $n['nb'] + $nb5;}
				elseif ($n['annee'] < $an6) {$nb6 = $n['nb'] + $nb6;}	
			}
		}
		$listenbobs = null;
		$listenbobs .= '<ul>';
		if($nbclasse == 3)
		{
			$nbt = $nb1 + $nb2;
			$listenbobs .= '<li>Observation entre '.$an2.' et '.$anneeencours.' : <b>'.$nbt.'</b> dont<ul>';
			$listenbobs .= '<li>Observation en '.$anneeencours.' : <b>'.$nb1.'</b></li>';
			$listenbobs .= '<li>Observation entre '.$an2.' et '.$an1.' : <b>'.$nb2.'</b></li></ul></li>';
			$listenbobs .= '<li>Observation avant '.$an3.' : <b>'.$nb3.'</b></li>';		
		}
		elseif($nbclasse == 4)
		{
			$nbt = $nb1 + $nb2;
			$listenbobs .= '<li>Observation entre '.$an2.' et '.$anneeencours.' : <b>'.$nbt.'</b> dont<ul>';
			$listenbobs .= '<li>Observation en '.$anneeencours.' : <b>'.$nb1.'</b></li>';
			$listenbobs .= '<li>Observation entre '.$an2.' et '.$an1.' : <b>'.$nb2.'</b></li></ul></li>';
			$listenbobs .= '<li>Observation entre '.$an3.' et '.$an2.' : <b>'.$nb3.'</b></li>';
			$listenbobs .= '<li>Observation avant '.$an4.' : <b>'.$nb4.'</b></li>';		
		}
		elseif($nbclasse == 5)
		{
			$nbt = $nb1 + $nb2;
			$listenbobs .= '<li>Observation entre '.$an2.' et '.$anneeencours.' : <b>'.$nbt.'</b> dont<ul>';
			$listenbobs .= '<li>Observation en '.$anneeencours.' : <b>'.$nb1.'</b></li>';
			$listenbobs .= '<li>Observation entre '.$an2.' et '.$an1.' : <b>'.$nb2.'</b></li></ul></li>';
			$listenbobs .= '<li>Observation entre '.$an3.' et '.$an2.' : <b>'.$nb3.'</b></li>';
			$listenbobs .= '<li>Observation entre '.$an4.' et '.$an3.' : <b>'.$nb4.'</b></li>';
			$listenbobs .= '<li>Observation avant '.$an5.' : <b>'.$nb5.'</b></li>';		
		}
		elseif($nbclasse == 6)
		{
			$nbt = $nb1 + $nb2;
			$listenbobs .= '<li>Observation entre '.$an2.' et '.$anneeencours.' : <b>'.$nbt.'</b> dont<ul>';
			$listenbobs .= '<li>Observation en '.$anneeencours.' : <b>'.$nb1.'</b></li>';
			$listenbobs .= '<li>Observation entre '.$an2.' et '.$an1.' : <b>'.$nb2.'</b></li></ul></li>';
			$listenbobs .= '<li>Observation entre '.$an3.' et '.$an2.' : <b>'.$nb3.'</b></li>';
			$listenbobs .= '<li>Observation entre '.$an4.' et '.$an3.' : <b>'.$nb4.'</b></li>';
			$listenbobs .= '<li>Observation entre '.$an5.' et '.$an4.' : <b>'.$nb5.'</b></li>';
			$listenbobs .= '<li>Observation avant '.$an6.' : <b>'.$nb6.'</b></li>';		
		}
		$listenbobs .= '</ul>';
		$retour['listenbobs'] = $listenbobs;
		$retour['statut'] = 'Oui';
	}
	else
	{
		$retour['listenbobs'] = '<p>Pas disponible pour ce maillage</p>';
		$retour['statut'] = 'Oui';
	}
	
	echo json_encode($retour, JSON_NUMERIC_CHECK);
}

	