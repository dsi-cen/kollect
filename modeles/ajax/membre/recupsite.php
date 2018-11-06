<?php 
include '../../../global/configbase.php';
include '../../../lib/pdo2.php';

/*function cherchesite($site,$codecom,$idobser)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT site, COUNT(idfiche) AS nb, fiche.idcoord, site.idsite FROM obs.fiche 
						INNER JOIN obs.site USING(idsite)
						WHERE site ILIKE :site AND idobser = :idobser AND fiche.codecom = :codecom
						GROUP BY site, fiche.idcoord, site.idsite ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':site', $site);
	$req->bindValue(':idobser', $idobser);
	$req->bindValue(':codecom', $codecom);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}*/
function cherchesite($site,$codecom,$idobser)
{
	$bdd = PDO2::getInstance();
	$bdd->query("SET NAMES 'UTF8'");
	$req = $bdd->prepare("SELECT site, COUNT(idfiche) AS nb, fiche.idcoord, site.idsite FROM obs.fiche 
						INNER JOIN obs.site USING(idsite)
						WHERE site ILIKE :site AND fiche.codecom = :codecom
						GROUP BY site, fiche.idcoord, site.idsite ") or die(print_r($bdd->errorInfo()));
	$req->bindValue(':site', $site);
	//$req->bindValue(':idobser', $idobser);
	$req->bindValue(':codecom', $codecom);
	$req->execute();
	$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	return $resultat;
}

if(isset($_POST['site']))
{	
	$site = $_POST['site'];
	$codecom = $_POST['codecom'];
	$idobser = $_POST['idobser'];
	
	$liste = cherchesite($site,$codecom,$idobser);
	
	if(count($liste) > 1)
	{
		$r = null;
		$r .= '<table class="table table-hover table-sm">';
		$r .= '<thead><th></th><th>Site</th><th>Nb de fiches</th></thead>';
		$r .= '<tbody>';
		foreach($liste as $n)
		{
			$r .= '<tr id="'.$n['idsite'].'"><td><i class="fa fa-eye curseurlien voirsite" title="Voir le site"></i>
											&nbsp;<i id="g'.$n['idsite'].'" class="fa fa-check curseurlien text-success garde" title="Site à garder"></i>
											&nbsp;<i id="s'.$n['idsite'].'" class="fa fa-trash curseurlien text-danger sup" title="Site à supprimer"></i></td>
											<td>'.$n['site'].'</td><td>'.$n['nb'].'</td></tr>';
		}
		$r .= '</tbody></table>';
		$r .= '<p>Cliquer sur le <i class="fa fa-check text-success"></i> du site que vous voulez garder, puis sur les <i class="fa fa-trash text-danger"></i> du site ou des sites que vous voulez supprimer<br>
				Les fiches avec le(s) site(s) à supprimer vont être rattachées au site à garder.<br>Puis cliquer sur le bouton "Valider"<br>En cliquant sur <i class="fa fa-eye"></i>, le site s\'affiche sur la carte à droite.</p>';
		$r .= '<button type="button" class="btn btn-success" id="BttV">Valider</button>';
		
		$retour['liste'] = $r;
		$retour['statut'] = 'Oui';
	}
	else
	{
		$retour['affiche'] = 'non';
		$retour['statut'] = 'Oui';
	}	
}
else
{
	$retour['statut'] = 'Non';	
}
echo json_encode($retour);	
?>