<?php
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';
	
function liste($nomvar)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT cdnom, nom, nomvern, vali, stade, photo, son, loupe, bino FROM referentiel.liste
						LEFT JOIN vali.critere USING(cdnom) 
						WHERE observatoire = :observa AND (rang = 'ES' OR rang = 'SSES') 
						ORDER BY nom ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':observa', $nomvar);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}
function listechoix($nomvar,$choix)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT cdnom, nom, nomvern, vali, stade, photo, son, loupe, bino FROM referentiel.liste
						LEFT JOIN vali.critere USING(cdnom) 
						WHERE observatoire = :observa AND (rang = 'ES' OR rang = 'SSES') AND vali = :vali
						ORDER BY nom ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':observa', $nomvar);
	$req->bindValue(':vali', $choix);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}

if(isset($_POST['sel']))
{
	$nomvar = $_POST['sel'];
	$choix = $_POST['choix'];
	
	if($choix == 'NR')
	{
		$liste = liste($nomvar);
	}
	else
	{
		$liste = listechoix($nomvar,$choix);
	}

	if($liste != false)
	{
		$l = '<table id="tblliste" class="table table-sm table-striped" cellspacing="0" width="100%">';
		$l .= '<thead><tr><th>Nom</th><th>Nom français</th><th>Type</th><th>Stade</th><th>Photo</th><th>Son</th><th>En main/loupe</th><th>Examen sous binoculaire</th></tr></thead>';
		$l .= '</table>';
		foreach($liste as $n)
		{
			if($n['vali'] == 0) { $type = 'Non soumis'; }
			elseif($n['vali'] == 1) { $type = 'Filtre informatique'; }
			elseif($n['vali'] == 2) { $type = 'Manuelle'; }
			
			$data[] = [$n['nom'],$n['nomvern'],$type,$n['stade'],$n['photo'],$n['son'],$n['loupe'],$n['bino']];
		}
		$retour['data'] = $data;
	}	
	else
	{
		if($choix == 'tous') { $l = 'Aucune espèce pour cet observatoire'; }
		elseif($choix == 1) { $l = 'Aucune espèce en validation par filtre informatique'; }
		elseif($choix == 2) { $l = 'Aucune espèce en validation manuelle'; }
		elseif($choix == 0) { $l = 'Aucune espèce non soumise à validation'; }
	}
	
	$retour['liste'] = $l;	
	$retour['statut'] = 'Oui';	
}
else
{
	$retour['statut'] = 'Non';
}
echo json_encode($retour);