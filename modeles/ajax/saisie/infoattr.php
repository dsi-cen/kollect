<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function methode($listeid)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query('SELECT idmethode AS id, methode AS lib, libelle AS desc FROM referentiel.methode WHERE idmethode IN('.$listeid.') ORDER BY methode ') or die(print_r($bdd->errorInfo()));
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;		
}	
function prospection($listeid)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query('SELECT idpros AS id, prospection AS lib, libelle AS desc FROM referentiel.prospection WHERE idpros IN('.$listeid.') ORDER BY prospection ') or die(print_r($bdd->errorInfo()));
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;		
}
function occstatutbio($listeid)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query('SELECT idstbio AS id, statutbio AS lib, libelle AS desc FROM referentiel.occstatutbio WHERE idstbio IN('.$listeid.') ORDER BY statutbio ') or die(print_r($bdd->errorInfo()));
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;		
}
function stade($listeid)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query('SELECT idstade AS id, stade AS lib, libelle AS desc FROM referentiel.stade WHERE idstade IN('.$listeid.') ORDER BY stade ') or die(print_r($bdd->errorInfo()));
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;		
}
function mort($listeid)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	$req = $bdd->query('SELECT idmort AS id, cause AS lib, libelle AS desc FROM referentiel.occmort WHERE idmort IN('.$listeid.') ORDER BY cause ') or die(print_r($bdd->errorInfo()));
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;		
}

if(isset($_POST['sel']) && isset($_POST['id']))
{
	$nomvar = $_POST['sel'];
	$id = $_POST['id'];
	
	$json = file_get_contents('../../../json/'.$nomvar.'.json');
	$rjson = json_decode($json, true);
	
	
	if($id == 'info1')
	{
		if(isset($rjson['saisie']['methode']))
		{ 
			foreach($rjson['saisie']['methode'] as $cle => $n)
			{
				$tab[] = $n;
			}
			$listeid = implode(",", $tab);
			$table = methode($listeid);			
		}
		$sinp = 'ObservationMethodeValue';
	}
	elseif($id == 'info2')
	{
		if(isset($rjson['saisie']['collecte']))
		{ 
			foreach($rjson['saisie']['collecte'] as $cle => $n)
			{
				$tab[] = $n;
			}
			$listeid = implode(",", $tab);
			$table = prospection($listeid);			
		}
	}
	elseif($id == 'info3')
	{
		if(isset($rjson['saisie']['statutbio']))
		{ 
			foreach($rjson['saisie']['statutbio'] as $cle => $n)
			{
				$tab[] = $n;
			}
			$listeid = implode(",", $tab);
			$table = occstatutbio($listeid);			
		}
		$sinp = 'OccurrenceStatutBiologiqueValue';
	}
	elseif($id == 'info4')
	{
		if(isset($rjson['saisie']['stade']))
		{ 
			foreach($rjson['saisie']['stade'] as $cle => $n)
			{
				$tab[] = $n;
			}
			$listeid = implode(",", $tab);
			$table = stade($listeid);			
		}
		$sinp = 'OccurrenceStadeDeVieValue';
	}
	elseif($id == 'info5')
	{
		if(isset($rjson['saisie']['mort']))
		{ 
			foreach($rjson['saisie']['mort'] as $cle => $n)
			{
				$tab[] = $n;
			}
			$listeid = implode(",", $tab);
			$table = mort($listeid);			
		}
	}
	if(isset($table))
	{
		$ref = null;
		if($id == 'info2' || $id == 'info5')
		{
			$ref .= '<p>Champ hors SINP</p>';
		}
		else
		{
			$ref .= '<p>Champ SINP : '.$sinp.'<br /> Les attributs rajoutés au Standard "Occurrences de taxon V1.2.1" figure en italique</p>';
		}
		$ref .= '<table class="table table-sm table-hover"><thead><tr><th>Libellé</th><th>Description</th></tr></thead><tbody>';
		foreach($table as $n)
		{
			if($n['id'] >= 100)
			{
				$ref .= '<tr><td><i>'.$n['lib'].'</i></td><td>'.$n['desc'].'</td></tr>';
			}
			else
			{
				$ref .= '<tr><td>'.$n['lib'].'</td><td>'.$n['desc'].'</td></tr>';
			}
		}
		$ref .= '</tbody></table>';		
		$liste = $ref;		
	}
	else
	{
		$liste = 'ras';
	}
	
	echo $liste;
}
