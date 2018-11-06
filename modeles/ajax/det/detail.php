<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

function vali($observa)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT COUNT(vali) AS nb, vali FROM site.photodet
						WHERE observa = :observa
						GROUP BY vali ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':observa', $observa);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;	
}

if(isset($_POST['observa']))
{
	$observa = $_POST['observa'];
	
	if($observa != 'NR')
	{
		$liste = vali($observa);	
		$nb = count($liste);
		if($nb > 1)
		{
			foreach($liste as $n)
			{
				if($n['vali'] == 'oui') { $nomaff = 'Déterminé'; $couleur = '#8cc63d'; }
				elseif($n['vali'] == 'non') { $nomaff = 'En cours de détermination'; $couleur = '#f0ad4e'; }
				elseif($n['vali'] == 'nde') { $nomaff = 'Non déterminable'; $couleur = '#505759'; }
				$tab['name'] = $nomaff;
				$tab['color'] = $couleur;
				$tab['y'] = $n['nb'];
				$data[] = $tab;
			}
			$retour['graph'] = 'Oui';
			$retour['data'] = $data;
		}
		elseif($nb == 1)
		{
			if($liste[0]['vali'] == 'oui') { $nomaff = 'Déterminé'; }
			elseif($liste[0]['vali'] == 'non') { $nomaff = 'En cours de détermination'; }
			elseif($liste[0]['vali'] == 'nde') { $nomaff = 'Non déterminable'; }
			$data = $nomaff.' : '.$liste[0]['nb'].' demande(s)';
			$retour['data'] = $data;
		}			
	}
	$retour['statut'] = 'Oui';	
}
else
{
	$retour['statut'] = 'Non'; 
}	
echo json_encode($retour, JSON_NUMERIC_CHECK);