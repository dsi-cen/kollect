<?php
include '../../../../global/configbase.php';
include '../../../../lib/pdo2.php';	

function liste($table)
{
	$bdd = PDO2::getInstance();
	$bdd->query('SET NAMES "utf8"');
	if($table == 'methode')
	{
		$req = $bdd->query("SELECT idmethode, methode, libelle FROM referentiel.methode");
	}	
	elseif($table == 'stade')
	{
		$req = $bdd->query("SELECT idstade, stade, idval, libelle FROM referentiel.stade ORDER BY idstade ");
	}
	elseif($table == 'prospection')
	{
		$req = $bdd->query("SELECT idpros, prospection, libelle FROM referentiel.prospection");
	}
	elseif($table == 'occstatutbio')
	{
		$req = $bdd->query("SELECT idstbio, statutbio, libelle FROM referentiel.occstatutbio ORDER BY idstbio");
	}
	elseif($table == 'occmort')
	{
		$req = $bdd->query("SELECT idmort, cause, libelle FROM referentiel.occmort");
	}
	$liste = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();	
	return $liste;
}
if (isset($_POST['table']))
{
	$table = $_POST['table'];
	$liste = liste($table);
	$ref = '<div class="alert alert-dismissible" role="alert"><button type="button" title="masquer" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
	$ref .= '<table class="table table-sm"><thead><tr>';
	if($table == 'stade')
	{
		$ref .= '<th>Id</th><th>Stade</th><th>idval</th><th>descriptif</th>';
	}
	elseif($table == 'methode')
	{
		$ref .= '<th>Id</th><th>Methode</th><th>descriptif</th>';
	}
	elseif($table == 'prospection')
	{
		$ref .= '<th>Id</th><th>Type de collecte</th><th>descriptif</th>';
	}
	elseif($table == 'occstatutbio')
	{
		$ref .= '<th>Id</th><th>Type de statut</th><th>descriptif</th>';
	}
	elseif($table == 'occmort')
	{
		$ref .= '<th>Id</th><th>Cause de la mort</th><th>descriptif</th>';
	}
	$ref .= '</tr></thead><tbody>';
	foreach($liste as $n)
	{
		if($table == 'stade')
		{
			$ref .= '<tr><td>'.$n['idstade'].'</td><td>'.$n['stade'].'</td><td>'.$n['idval'].'</td><td>'.$n['libelle'].'</td></tr>';
		}
		elseif($table == 'methode')
		{
			$ref .= '<tr><td>'.$n['idmethode'].'</td><td>'.$n['methode'].'</td><td>'.$n['libelle'].'</td></tr>';
		}
		elseif($table == 'prospection')
		{
			$ref .= '<tr><td>'.$n['idpros'].'</td><td>'.$n['prospection'].'</td><td>'.$n['libelle'].'</td></tr>';
		}
		elseif($table == 'occstatutbio')
		{
			$ref .= '<tr><td>'.$n['idstbio'].'</td><td>'.$n['statutbio'].'</td><td>'.$n['libelle'].'</td></tr>';
		}
		elseif($table == 'occmort')
		{
			$ref .= '<tr><td>'.$n['idmort'].'</td><td>'.$n['cause'].'</td><td>'.$n['libelle'].'</td></tr>';
		}
	}
	$ref .= '</tbody></table>';
	$ref .= '</div>';
	$retour['statut'] = 'Oui';
	$retour['ref'] = $ref;	
}
else
{
	$retour['statut'] = 'Non';	
}
echo json_encode($retour);